<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains classes Config, ArrayConfig
 * 
 * @version	$Id: Config.php Feb 15, 2012 5:37:31 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
set_include_path(LIBS_DIR . DS);

require_once ROOT_PATH . DS . 'core' . DS . 'interfaces' . DS . 'IDataContains.php';
require_once LIBS_DIR . DS . 'Zend' . DS . 'Config.php';
require_once LIBS_DIR . DS . 'Zend' . DS . 'Config' . DS . 'Ini.php';
require_once LIBS_DIR . DS . 'Zend' . DS . 'Config' . DS . 'Writer' . DS . 'Ini.php';

/**
 * Class for configs
 */
abstract class Config implements IDataContains {

    /**
     * Create config from file
     * @param string $configPath 
     */
    public function __construct($configPath) {
        $this->configPath = $configPath;
    }

    protected $configPath;

    public abstract function load();

    public function setData($data) {
        $this->params = $data;
    }

    public function getData() {
        return $this->params;
    }

}

/**
 * Class for array configs
 */
class ArrayConfig extends Config {

    public function load() {
        if (file_exists($this->configPath)) {
            $params = include($this->configPath);
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }
    }

}

/**
 * Class for array configs
 */
class IniConfig extends Zend_Config_Ini {
    
    protected $params;

}

?>
