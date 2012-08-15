<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class CFactory
 * 
 * @version	$Id: CFactory.php Mar 5, 2012 1:25:21 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for core factory methods
 */
class CFactory {

    /**
     * Get system config object
     * @return SystemConfig 
     */
    public static function getConfig() {
        return Core::instance()->getConfig();
    }

}

?>
