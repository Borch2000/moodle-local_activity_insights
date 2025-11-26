<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_activity_insights', get_string('settings_heading', 'local_activity_insights'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_activity_insights/weight_lastaccess',
        'Weight: last access (days)', 'Days since last access weight', 0.3, PARAM_FLOAT));
    $settings->add(new admin_setting_configtext('local_activity_insights/weight_missing',
        'Weight: missing submissions', 'Weight for missing submissions', 0.25, PARAM_FLOAT));
    $settings->add(new admin_setting_configtext('local_activity_insights/weight_lowgrades',
        'Weight: low grades', 'Weight for low grades', 0.25, PARAM_FLOAT));
    $settings->add(new admin_setting_configtext('local_activity_insights/threshold_high',
        'High risk threshold', 'Threshold >= for high risk', 0.7, PARAM_FLOAT));
}
