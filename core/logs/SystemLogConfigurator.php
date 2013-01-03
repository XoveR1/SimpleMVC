<?php

if (!defined('INSIDE_ACCESS')) {
    die('No access to script!');
}

/**
 * Contains class SystemLogConfigurator
 * 
 * @version	$Id: SystemLogConfigurator.php Oct 4, 2012 18:40:21 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
require_once LIBS_DIR . DS . 'Log4php' . DS . 'LoggerConfigurator.php';

/**
 * Class for configure system logger
 */
class SystemLogConfigurator implements LoggerConfigurator {
    
    const LOG_NAME = 'system';

    public function configure(LoggerHierarchy $hierarchy, $input = null) {
        
        // Use a different layout for the next appender
        $layout = new LoggerLayoutTTCC();
        $layout->setContextPrinting(false);
        $layout->setDateFormat('%Y-%m-%d %H:%M:%S');
        $layout->activateOptions();

        // Create an appender which logs to file
        $appFile = new LoggerAppenderFile(self::LOG_NAME);
        $filePath = CFactory::getConfig()->logsFolderPath . DS . self::LOG_NAME . '.log';
        $appFile->setFile($filePath);
        $appFile->setAppend(true);
        $appFile->setThreshold('all');
        $appFile->setLayout($layout);
        $appFile->activateOptions();

        // Add both appenders to the root logger
        $root = $hierarchy->getRootLogger();
        $root->addAppender($appFile);
    }

}

?>
