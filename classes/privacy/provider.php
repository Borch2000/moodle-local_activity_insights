<?php
namespace local_activity_insights\privacy;
defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\plugin\provider {
    public static function get_metadata(\core_privacy\local\metadata\collection $collection) {
        $collection->add_database_table('local_activity_insights_scores', ['userid' => 'User id', 'score' => 'Computed score'], 'Risk scores computed by Activity Insights');
        return $collection;
    }

    public static function get_contexts_for_userid($userid) {
        // all contexts
        return new \core_privacy\local\request\contextlist();
    }

    public static function export_user_data(\core_privacy\local\request\user_request $request) {
        // Implement export if needed
        return [];
    }

    public static function delete_data_for_all_users_in_context(\context $context) {
        return;
    }

    public static function delete_data_for_user(\core_privacy\local\request\user_request $request) {
        return;
    }
}
