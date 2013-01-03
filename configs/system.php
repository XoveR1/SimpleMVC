<?php

return array(
    // Host name of MySQL server
    'dbhost' => '127.0.0.1',
    // Name of using database
    'dbname' => 'simple_mvc',
    // Name of user with access to dev_cashserv_sl database
    'dbuser' => 'root',
    // Password of user with access to dev_cashserv_sl database
    'dbpassword' => '',
    
    
    // If useRewriteUrl is true - you can use ONLY urls like:
    // "http://host_name/{controller_name}/{action_name}/{param_1}/{param_2}/..."
    // Don't forget use .htaccess file
    // If useRewriteUrl is false - you can use ONLY urls like:
    // "http://host_name/index.php?a={controller_name}&a={action_name}&0={param_1}&1={param_2}&2=..."
    'useRewriteUrl' => true,
    // Default action
    // First index - controller, second - action
    'defaultAction' => array('auth', 'login'),
    // Time of life cookies in minutes for "Remember me" in login form
    'lifeSessionTime' => 120, //2h 
    // Secret word is using for cookies security
    'secretWord' => '5sh&3*0sdG5j',   
    // If debug mode in false 'errorReportingLevel' will be ignored and equal 0 
    // and all Factory::debug() calls will show exceptions
    'debugMode' => true,
    // PHP error reporting level:
    // E_NOTICE => throw exception for notices, warnings and errors 
    // E_WARNING => throw exception for warnings and errors 
    // E_ERROR => throw exception for errors 
    // 0 => turn off all errors
    'errorReportingLevel' => E_NOTICE,
    // Path to folder where logs files are located (without last slash in name of folder)
    'logsFolderPath' => ROOT_PATH . DS . 'logs',
    
    
    // Version of application
    'appVersion' => '1.0.0',
    // Label constant from lang files for site title
    'siteNameLabelKey' => 'SITE_NAME',
    // Separator between site and page titles in tag <title>
    'titleSeparator' => ' / ',
    // Available languages in site
    'languages' => array(
        'en-GB',
        // Not using
        'ru-RU'
    ),
    // Default language
    'defaultLang' => 'en-GB',
    // Name of global lang file that should be in folder 
    // [app_name]/app/languages/[lang_name]/
    'globalLangFile' => 'global.ini',
    // File of jQuery that using in system
    'jQueryFile' => 'jquery-1.7.2.min',
    // List of scripts files for include in all pages
    'scripts' => array(
        'bootstrap.min',
        'global'
    ),
    // List of css files for include in all pages
    'styles' => array(
        'bootstrap.min',
        'styles'
    ),
    // Menu tree for view in template (should be overrided in application)
    'menu' => array()
);
?>
