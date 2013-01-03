<?php

if (!defined('INSIDE_ACCESS')) {
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

    protected $configFields = array();

    /**
     * Create array config with extend of parent config
     * 
     * @param string $configPath
     * @param AppConfig $parentConfig
     */
    public function __construct($configPath, ArrayConfig $parentConfig = null) {
        parent::__construct($configPath);
        if (!is_null($parentConfig)) {
            $this->merge($parentConfig);
        }
    }

    /**
     * Load config from array file
     */
    public function load() {
        if (file_exists($this->configPath)) {
            $params = include($this->configPath);
            foreach ($params as $key => $value) {
                $this->$key = $value;
                $this->configFields[$key] = $value;
            }
        }
    }

    /**
     * Merge current config witn new
     * @param ArrayConfig $config
     */
    public function merge(ArrayConfig $config) {
        $fieldsArray = $config->getFieldsList();
        foreach ($fieldsArray as $key) {
            $this->$key = $config->$key;
            $this->configFields[$key] = $config->$key;
        }
    }

    /**
     * Return list of all existing fields in current config
     * 
     * @return array
     */
    public function getFieldsList() {
        return array_keys($this->configFields);
    }

}

/**
 * Class for array configs
 */
class IniConfig extends Zend_Config_Ini {

    protected $params;

}

?>
