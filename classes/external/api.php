<?php
namespace local_activity_insights\external;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_multiple_structure;
use external_single_structure;

class api extends external_api {
    public static function get_scores_parameters() {
        return new external_function_parameters([
            'since' => new external_value(PARAM_INT, 'Timestamp minimal', VALUE_DEFAULT, 0),
            'limit' => new external_value(PARAM_INT, 'Limit', VALUE_DEFAULT, 100)
        ]);
    }

    public static function get_scores($since = 0, $limit = 100) {
        global $DB;
        $params = self::validate_parameters(self::get_scores_parameters(), ['since' => $since, 'limit' => $limit]);
        $sql = 'SELECT userid, score, risklevel, timecomputed, factors FROM {local_activity_insights_scores} WHERE timecomputed >= ? ORDER BY timecomputed DESC LIMIT ?';
        $records = $DB->get_records_sql($sql, [$params['since'], $params['limit']]);
        $out = [];
        foreach ($records as $r) {
            $out[] = [
                'userid' => (int)$r->userid,
                'score' => (float)$r->score,
                'risklevel' => (int)$r->risklevel,
                'timecomputed' => (int)$r->timecomputed,
                'factors' => json_decode($r->factors, true)
            ];
        }
        return $out;
    }

    public static function get_scores_returns() {
        return new external_multiple_structure(
            new external_single_structure(array(
                'userid' => new external_value(PARAM_INT, 'User id'),
                'score' => new external_value(PARAM_FLOAT, 'Score'),
                'risklevel' => new external_value(PARAM_INT, 'Risk level'),
                'timecomputed' => new external_value(PARAM_INT, 'Timestamp'),
                'factors' => new external_value(PARAM_RAW, 'Factors JSON')
            ))
        );
    }
}
