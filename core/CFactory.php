<?php

if (!defined('INSIDE_ACCESS')) {
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
     * Array with loggers
     * @var array 
     */
    protected static $loggers = array();
    
    /**
     * Core config object
     * @var CConfig 
     */
    protected static $cConfig;

    /**
     * Get core config object
     * @return CConfig 
     */
    public static function getConfig() {
        if (!isset(self::$cConfig)) {
            self::$cConfig = new CConfig(SYSTEM_CONFIG_PATH);
            self::$cConfig->load();
        }
        return self::$cConfig;
    }

    /**
     * Get system configured logger object
     * @param string $logName Name of log. Can be 'system', 'debug' or your implementation
     * @return Logger
     */
    public static function getLogger($logName = 'system') {
        // If logger not created
        if (!isset(self::$loggers[$logName])) {
            // Load log4php classes
            require_once LIBS_DIR . DS . 'Log4php' . DS . 'Logger.php';
            // Load configure class
            $className = ucfirst($logName) . 'LogConfigurator';
            require_once ROOT_PATH . DS . 'core' . DS . 'logs' . DS . $className . '.php';
            // Create new log object
            $logger = Logger::getLogger($logName);
            $configuration = array();
            // Configure new logger
            $logger->configure($configuration, new $className());
            self::$loggers[$logName] = $logger;
        }
        return self::$loggers[$logName];
    }

    /**
     * Debug value in different sources (stdoutput or/and log)
     * @param mixed $dumpValue
     * @param bool $isShowInOutput
     * @param bool $isWriteInLog
     * @param bool $isStopScript
     */
    public static function debug($dumpValue, $isShowInOutput = false, $isWriteInLog = true, $isStopScript = false) {
        if (!CFactory::getConfig()->debugMode) {
            throw new AppException('Remove current method call in production server for more performance');
        }
        if (is_object($dumpValue) || is_array($dumpValue)) {
            ob_start();
            print_r($dumpValue);
            $outputData = ob_get_clean();
        }
        if ($isShowInOutput) {
            echo '<pre>' . $outputData . '</pre>';
        }
        if ($isWriteInLog) {
            CFactory::getLogger('debug')->debug($outputData);
        }
        if ($isStopScript) {
            exit();
        }
    }

}

?>
