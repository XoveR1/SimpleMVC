<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains interface IAuthentication
 * 
 * @version	$Id: IAuthentication.php Mar 2, 2012 9:34:30 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Interface for authentication models
 */
interface IAuthentication {
    public static function enter($login, $password); 
}

?>
