<?php
defined('MOODLE_INTERNAL') || die();

function local_activity_insights_extend_navigation(global_navigation $nav) {
    if (has_capability('local/activity_insights:view', \context_system::instance())) {
        $url = new moodle_url('/local/activity_insights/index.php');
        $nav->add(get_string('dashboard', 'local_activity_insights'), $url);
    }
}
