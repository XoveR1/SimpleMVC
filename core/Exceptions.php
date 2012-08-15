<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains classe for exceptions
 * 
 * @version	$Id: Exceptions.php Feb 16, 2012 11:34:10 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for language exception
 */
class LanguagesException extends Exception {
    
}

/**
 * Class for array exception
 */
class ArrayConfigException extends Exception {
    
}

/**
 * Class for config exception
 */
class IniConfigException extends Exception {
    
}

/**
 * Class for controller exception 
 */
class ControllerException extends Exception {
    
}

?>
