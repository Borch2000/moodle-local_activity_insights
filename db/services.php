<?php
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_activity_insights_get_scores' => array(
        'classname'   => 'local_activity_insights\external\api',
        'methodname'  => 'get_scores',
        'classpath'   => 'local/activity_insights/classes/external/api.php',
        'description' => 'Get computed scores since timestamp',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> 'local/activity_insights:view'
    ),
);

$services = array(
    'Activity Insights service' => array(
        'functions' => array ('local_activity_insights_get_scores'),
        'restrictedusers' => 0,
        'enabled' => 1,
    )
);
