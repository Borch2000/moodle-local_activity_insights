<?php
namespace local_activity_insights\analytics;
defined('MOODLE_INTERNAL') || die();

class riskcalculator {
    protected $weights;

    public function __construct($weights = []) {
        // default weights read from config if present
        $this->weights = array_merge([
            'lastaccess_days' => (float)get_config('local_activity_insights', 'weight_lastaccess') ?: 0.3,
            'missing_submissions' => (float)get_config('local_activity_insights', 'weight_missing') ?: 0.25,
            'low_grades' => (float)get_config('local_activity_insights', 'weight_lowgrades') ?: 0.25,
            'forum_participation' => 0.1,
            'resource_views' => 0.1,
        ], $weights);
    }

    public function compute_score_for_user($userid) {
        global $DB;

        $lastaccess = $this->get_days_since_last_access($userid);
        $missing = $this->get_missing_submissions_count($userid);
        $lowgrades = $this->get_low_grade_ratio($userid);
        $forum = $this->get_forum_participation($userid);
        $views = $this->get_resource_views($userid);

        $ndays = min($lastaccess / 30.0, 1.0);
        $nmissing = min($missing / 5.0, 1.0);
        $nlow = min($lowgrades, 1.0);
        $nforum = 1 - min($forum / 5.0, 1.0);
        $nviews = 1 - min($views / 20.0, 1.0);

        $score = (
            $ndays * $this->weights['lastaccess_days'] +
            $nmissing * $this->weights['missing_submissions'] +
            $nlow * $this->weights['low_grades'] +
            $nforum * $this->weights['forum_participation'] +
            $nviews * $this->weights['resource_views']
        );

        $score = max(0, min(1, $score));
        $level = $this->score_to_level($score);

        return [
            'userid' => $userid,
            'score' => round($score, 4),
            'level' => $level,
            'factors' => [
                'lastaccess_days' => round($ndays, 3),
                'missing_submissions' => round($nmissing, 3),
                'low_grades' => round($nlow, 3),
                'forum_participation' => round($nforum, 3),
                'resource_views' => round($nviews, 3)
            ]
        ];
    }

    protected function score_to_level($score) {
        if ($score >= (float)get_config('local_activity_insights', 'threshold_high') ?: 0.7) return 3;
        if ($score >= 0.4) return 2;
        return 1;
    }

    protected function get_days_since_last_access($userid) {
        global $DB;
        $last = $DB->get_field_sql('SELECT MAX(timeaccess) FROM {user_lastaccess} WHERE userid = ?', [$userid]);
        if (!$last) return 365;
        return (time() - $last) / 86400;
    }

    protected function get_missing_submissions_count($userid) {
        global $DB;
        // Count assignments where user has no submission and due date passed (simplified)
        $sql = "SELECT COUNT(DISTINCT a.id)
                  FROM {assign} a
                  JOIN {course} c ON c.id = a.course
                  LEFT JOIN {assign_submission} s ON s.assignment = a.id AND s.userid = ?
                  WHERE a.duedate > 0 AND a.duedate < ? AND (s.status IS NULL OR s.status = '')";
        $count = $DB->count_records_sql($sql, [$userid, time()]);
        return (int)$count;
    }

    protected function get_low_grade_ratio($userid) {
        global $DB;
        // Simplified: ratio of graded items with grade < 50%
        $sql = "SELECT COUNT(gi.id) AS low, (SELECT COUNT(gi2.id) FROM {grade_items} gi2 WHERE gi2.itemtype = 'mod') AS total
                FROM {grade_items} gi
                JOIN {grade_grades} gg ON gg.itemid = gi.id AND gg.userid = ?
                WHERE gg.finalgrade IS NOT NULL AND gg.finalgrade < (gi.grademax * 0.5)";
        $low = $DB->get_field_sql('SELECT COUNT(1) FROM {grade_grades} gg JOIN {grade_items} gi ON gg.itemid = gi.id WHERE gg.userid = ? AND gg.finalgrade < (gi.grademax * 0.5)', [$userid]);
        $total = $DB->get_field_sql('SELECT COUNT(1) FROM {grade_grades} gg JOIN {grade_items} gi ON gg.itemid = gi.id WHERE gg.userid = ?', [$userid]);
        if (!$total) return 0.0;
        return round($low / $total, 4);
    }

    protected function get_forum_participation($userid) {
        global $DB;
        $count = $DB->count_records('forum_posts', array('userid' => $userid));
        return (int)$count;
    }

    protected function get_resource_views($userid) {
        global $DB;
        // Use logstore_standard_log to count resource views (simplified)
        $sql = "SELECT COUNT(1) FROM {logstore_standard_log} WHERE userid = ? AND action = 'view' AND component != 'core' ";
        $count = $DB->count_records_sql($sql, [$userid]);
        return (int)$count;
    }
}
