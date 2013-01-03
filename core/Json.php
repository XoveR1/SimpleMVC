<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Json
 * 
 * @version	$Id: Json.php Feb 20, 2012 4:24:53 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for json responses
 */
class Json {
    
    /**
     * Show json page from object without stop of script
     * @param string $response
     */
    public static function response($response){
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Show json string without stop of script
     * @param string $json
     */
    public static function show($json){
        header('Content-Type: application/json');
        echo $json;
    }
    
}

?>
