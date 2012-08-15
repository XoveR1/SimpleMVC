<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Registry
 * 
 * @version	$Id: Registry.php Jul 19, 2012 16:03:07 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for save data in static registy for transfer data between levels
 */
class Registry {

    protected static $storageArray = array();

    public static function setData($key, $value) {
        self::$storageArray[$key] = $value;
    }

    public static function getData($key) {
        return self::$storageArray[$key];
    }
    
    public static function isExist($key) {
        return isset(self::$storageArray[$key]);
    }    

}

?>
