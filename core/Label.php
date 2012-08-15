<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Label
 * 
 * @version	$Id: Label.php Feb 16, 2012 11:18:58 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for show label on differents languages
 */
class Label {
    protected function __construct() {}
    
    protected static $labels = array();
    public static $controllerName;      
    
    /**
     * Shows label text from language file
     * @param string $key - Key or value for translate
     * @param array $values - Array of values for insert in key
     * @example use in label value %num%,%name%,etc.
     * @return string 
     */
    public static function _($key, $values = array()){
        self::loadLabels();
        if(!isset(self::$labels[$key])){
            $label = $key;
        } else {
            $label = self::$labels[$key];
            if(count($values) > 0){
                foreach ($values as $name => $value) {
                    $label = str_replace("%$name%", $value, $label);
                }
            }
        }
        return $label;
    }
    
    /**
     * Loads labels from files
     */
    protected static function loadLabels(){
        if(!self::$labels){
            $config = Core::instance()->getConfig();
            $langPath = LANG_DIR . DS . $config->defaultLang . DS;
            $langFiles = array();
            $langFiles[] = $langPath . $config->globalLangFile;
            if(self::$controllerName){
                $langFiles[] = $langPath . strtolower(self::$controllerName) . '.ini';
            }
            foreach ($langFiles as $langFile) {
                if(file_exists($langFile)){
                    self::$labels = array_merge(parse_ini_file($langFile), self::$labels);
                }
            }
            
        }
    }
}

?>
