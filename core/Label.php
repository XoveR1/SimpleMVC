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
        // Load labels in array
        self::loadLabels();
        // If label not defined 
        if(!isset(self::$labels[$key])){
            // return value of key
            $label = $key;
        } else {
            // get label data
            $label = self::$labels[$key];
            // If parametrs was obtained
            if(count($values) > 0){
                // Replace them in label
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
            $config = CFactory::getConfig();
            // Create path to lang file
            $langPath = LANG_DIR . DS . $config->defaultLang . DS;
            $langFiles = array();
            // Include global language file
            $langFiles[] = $langPath . $config->globalLangFile;
            if(self::$controllerName){
                // Include current controller language file
                $langFiles[] = $langPath . strtolower(self::$controllerName) . '.ini';
            }
            // Load all data in one array from all language files
            foreach ($langFiles as $langFile) {
                if(file_exists($langFile)){
                    self::$labels = array_merge(parse_ini_file($langFile), self::$labels);
                }
            }
            
        }
    }
}

?>
