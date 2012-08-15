<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class SystemConfig
 * 
 * @version	$Id: SystemConfig.php Feb 15, 2012 5:58:44 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
// Include config classes
require_once ROOT_PATH . DS . 'core' . DS . 'Config.php';

/**
 * Class for system config
 * @property string $siteNameLabelKey
 * @property string $titleSeparator
 * @property string $dbname
 * @property string $dbhost
 * @property string $dbuser
 * @property string $dbpassword
 * @property array $languages
 * @property string $defaultLang
 * @property string $globalLangFile
 * @property array $scripts
 * @property array $styles
 * @property array $menu
 * @property string $googleApiKey
 * @property string $registrationUrl
 * @property string $admin_login
 * @property string $admin_password
 * @property int $lifeSessionTime
 * @property string $pathToLogs
 * @property string $defaultAction
 * @property string $useRewriteUrl
 */
class SystemConfig extends ArrayConfig {

    public function getConnectionString() {
        $connection = 'mysql://' . $this->dbuser;
        $connection .= ':' . $this->dbpassword;
        $connection .= '@' . $this->dbhost;
        $connection .= '/' . $this->dbname;
        return $connection;
    }

}

?>
