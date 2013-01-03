<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class CConfig
 * 
 * @version	$Id: CConfig.php Feb 15, 2012 5:58:44 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
// Include config classes
require_once ROOT_PATH . DS . 'core' . DS . 'Config.php';

/**
 * Class for core config properties
 * 
 * @property string     $dbname                 Host name of MySQL server
 * @property string     $dbhost                 Name of using database
 * @property string     $dbuser                 Name of user with access to dev_cashserv_sl database
 * @property string     $dbpassword             Password of user with access to dev_cashserv_sl database
 *
 * 
 * @property string     $useRewriteUrl          If useRewriteUrl is true - you can use ONLY urls like:
 *                                              "http://host_name/{controller_name}/{action_name}/{param_1}/{param_2}/..."
 *                                              Don't forget use .htaccess file
 *                                              If useRewriteUrl is false - you can use ONLY urls like:
 *                                              "http://host_name/index.php?a={controller_name}&a={action_name}&0={param_1}&1={param_2}&2=..."
 * @property string     $defaultAction          Default action. First index - controller, second - action
 * @property int        $lifeSessionTime        Time of life cookies in minutes for "Remember me" in login form
 * @property string     $secretWord             Secret word is using for cookies security
 * @property string     $debugMode              If debug mode in false 'errorReportingLevel' will be ignored and equal 0 
 *                                              and all Factory::debug() calls will show exceptions
 * @property string     $errorReportingLevel    PHP error reporting level:
 *                                              E_NOTICE => throw exception for notices, warnings and errors 
 *                                              E_WARNING => throw exception for warnings and errors 
 *                                              E_ERROR => throw exception for errors 
 *                                              0 => turn off all errors
 * @property string     $logsFolderPath         Path to folder where logs files are located (without last slash in name of folder)

 * 
 * @property string     $appVersion             Version of application
 * @property string     $siteNameLabelKey       Label constant from lang files for site title
 * @property string     $titleSeparator         Separator between site and page titles in tag <title>
 * @property array      $languages              Available languages in site
 * @property string     $defaultLang            Default language
 * @property string     $globalLangFile         Name of global lang file that should be in folder 
 *                                              [app_name]/app/languages/[lang_name]/
 * @property string     $jQueryFile             File of jQuery that using in system
 * @property array      $scripts                List of scripts files for include in all pages
 * @property array      $styles                 List of css files for include in all pages
 * @property array      $menu                   Menu tree for view in template (should be overrided in application)
 */
class CConfig extends ArrayConfig {

    /**
     * Renders connection string for PDO
     * @return string
     */
    public function getConnectionString() {
        $connection = 'mysql://' . $this->dbuser;
        $connection .= ':' . $this->dbpassword;
        $connection .= '@' . $this->dbhost;
        $connection .= '/' . $this->dbname;
        return $connection;
    }

}

?>
