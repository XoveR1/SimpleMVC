<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class AppConfig
 * 
 * @version	$Id: AppConfig.php Feb 15, 2012 5:58:44 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
// Include config classes
require_once ROOT_PATH . DS . 'core' . DS . 'CConfig.php';

/**
 * Class for app config properties
 * Extend CConfig class
 *         
 * @property array      $menu               Menu tree for view in template
 */
class AppConfig extends CConfig {

}

?>
