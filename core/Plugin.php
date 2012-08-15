<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Plugin
 * 
 * @version	$Id: Plugin.php Mar 3, 2012 11:30:33 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for event code
 * @uses use prefix "execute" for methods
 */
abstract class Plugin {
    const AFTER_CORE_PREPARE = 'AfterCorePrepare';
}

?>
