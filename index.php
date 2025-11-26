<?php
require_once(__DIR__.'/../../config.php');
require_login();
$context = context_system::instance();
require_capability('local/activity_insights:view', $context);

$PAGE->set_url(new moodle_url('/local/activity_insights/index.php'));
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('dashboard', 'local_activity_insights'));
$PAGE->set_heading(get_string('dashboard', 'local_activity_insights'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('dashboard', 'local_activity_insights'));

// Simple container: JS will fetch data via the webservice
echo html_writer::start_div('local-activity-insights');
echo html_writer::tag('canvas', '', ['id' => 'aiChart', 'width' => 600, 'height' => 200]);
echo html_writer::end_div();

$PAGE->requires->js_call_amd('local_activity_insights/insights', 'init', array());
echo $OUTPUT->footer();
