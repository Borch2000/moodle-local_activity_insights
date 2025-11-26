<?php
namespace local_activity_insights\task;
defined('MOODLE_INTERNAL') || die();

class run_scoring extends \core\scheduled_task {
    public function get_name() {
        return get_string('runtask', 'local_activity_insights');
    }

    public function execute() {
        global $DB;
        // Get active users (simple filter)
        $users = $DB->get_records_sql('SELECT id FROM {user} WHERE deleted = 0 AND suspended = 0');
        $calculator = new \local_activity_insights\analytics\riskcalculator();

        foreach ($users as $u) {
            $res = $calculator->compute_score_for_user($u->id);
            $record = new \stdClass();
            $record->userid = $res['userid'];
            $record->courseid = 0;
            $record->score = $res['score'];
            $record->risklevel = $res['level'];
            $record->factors = json_encode($res['factors']);
            $record->timecomputed = time();
            $DB->insert_record('local_activity_insights_scores', $record);
        }
    }
}
