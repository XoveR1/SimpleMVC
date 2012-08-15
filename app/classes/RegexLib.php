<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class RegexLib
 * 
 * @version	$Id: RegexLib.php Mar 20, 2012 4:09:40 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for regex container
 */
class RegexLib {
    
    protected static $onlyRegEx = false;
    
    private static function preparePattern($reqEx){
        if(self::$onlyRegEx){
            return $reqEx;
        } else {
            return "/$reqEx/";
        };
    }
    
    /**
     * Set status of regex prepare way with add /^$reqEx$/ or not
     * ATTETION: If using for attribute "pattern" in html 
     * you should set this flag in true
     * @param bool $onlyRegEx
     * @return bool Return old status of regex prepare way 
     */
    public static function isOnlyRegEx($onlyRegEx = true){
        $oldOnlyRegEx = self::$onlyRegEx;
        self::$onlyRegEx = $onlyRegEx;
        return $oldOnlyRegEx;
    }
    
    public static function Mail(){
        $reqEx = '([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)';
        return self::preparePattern($reqEx);
    }
    
    public static function Host(){
        $reqEx = '[\-\w]+.[\w]+';
        return self::preparePattern($reqEx);
    }
    
    public static function Letters(){
        $reqEx = '[a-zA-Z\s]+';
        return self::preparePattern($reqEx);
    }
    
    public static function IPMask(){
        //$reqEx = '[0-9?*]{1,3}.[0-9?*]{1,3}.[0-9?*]{1,3}.[0-9?*]{1,3}';
        $reqEx = '^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$';
        return self::preparePattern($reqEx);
    }
    
    public static function PositiveNumber(){
        $reqEx = '[\d]+';
        return self::preparePattern($reqEx);
    }     
    
    public static function Number(){
        $reqEx = '[\-\d]+';
        return self::preparePattern($reqEx);
    }        
    
    public static function MoreOrPositiveNumber(){
        $reqEx = '[\d]+|[\>]{1}';
        return self::preparePattern($reqEx);
    }      
    
    public static function MoreOrNumber(){
        $reqEx = '[\-\d]+|[\>]{1}';
        return self::preparePattern($reqEx);
    } 
    
    public static function LessOrNumber(){
        $reqEx = '[\-\d]+|[\<]{1}';
        return self::preparePattern($reqEx);
    }        
    
}

?>
