<?php

return array(
    'siteNameLabelKey' => 'SITE_NAME',
    'titleSeparator' => ' / ',
    'dbhost' => 'localhost',
    'dbname' => 'test',
    'dbuser' => 'root',
    'dbpassword' => '',
    // Use nice urls like "nice/url/with/params"
    'useRewriteUrl' => true,
    // Default action
    // First index - controller, second - action
    'defaultAction' => array('users', 'index'),
    'admin_login' => 'Admin',
    'admin_password' => '60bf1347cc0eca25ac4tc613f0d6a9e0',
    // Time of life session in minutes
    'lifeSessionTime' => 300, //2h
    'languages' => array(
        'en-GB'
    ),
    'defaultLang' => 'en-GB',
    'globalLangFile' => 'global.ini',
    'scripts' => array(
        'jquery-1.7.2.min',
        'jquery-ui-1.8.16.custom.min',
        'bootstrap.min',
        'bootstrap-tooltip',
        'bootstrap-tab',
        'bootstrap-dropdown',
        'jquery.tmpl.min',
        'global'
    ),
    'styles' => array(
        'bootstrap.min',
        'jquery-ui-1.8.16.custom',
        'jquery.ui.1.8.16.ie',
        'styles'
    ),
    // Menu tree
    'menu' => array(        
    ),
);
?>
