<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Factory
 * 
 * @version	$Id: Factory.php Mar 1, 2012 3:14:37 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

require_once ROOT_PATH . DS . 'core' . DS . 'CFactory.php';

/**
 * Class for main system objects create
 */
class Factory extends CFactory {

    protected function __construct() {
        
    }
    
    /**
     * Create new CSV parser object
     * @return \parseCSV 
     */
    public static function getCSVParser(){
        require_once LIBS_DIR . DS . 'Parsecsv' . DS . 'parsecsv.lib.php';
        return new parseCSV();
    } 

}

?>
