<?php

if (!defined('INSIDE_ACCESS')) {
    die('No access to script!');
}

/**
 * Contains classe for exceptions
 * 
 * @version	$Id: Exceptions.php Feb 16, 2012 11:34:10 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Override default exceptions
 */
class AppException extends Exception {

    /**
     * Build string with exception info for save in log
     * @return string
     */
    public function getLogMessage() {
        $logMessage = $this->message;

        if ($this->getLine()) {
            $logMessage .= ' - line: ' . $this->getLine();
        }

        if ($this->getFile()) {
            $logMessage .= ' - file: ' . $this->getFile();
        }

        if ($this->getTraceAsString()) {
            $logMessage .= ' - trace: ' . PHP_EOL . $this->getTraceAsString();
        }

        return $logMessage;
    }

}

/**
 * Class for override PHP errors
 */
class PhpException extends AppException {

    /**
     * Init exeption object from default PHP reporting
     * @param string $message Message with error text
     * @param int $errorLevel Error level value
     * @param string $errorFile Path to file with error
     * @param int $errorLine Number of line with error
     */
    public function __construct($message, $errorLevel = 0, $errorFile = '', $errorLine = 0) {
        parent::__construct($message, $errorLevel);
        $this->file = $errorFile;
        $this->line = $errorLine;
    }

    /**
     * Handler function for standart PHP errors
     * @param int $errorLevel Error level value
     * @param string $errorMessage Message with error text
     * @param string $errorFile Path to file with error
     * @param int $errorLine Number of line with error
     * @throws PhpNoticeException
     * @throws PhpWarningException
     * @throws PhpErrorException
     */
    public static function throwPhpException($errorLevel, $errorMessage, $errorFile, $errorLine) {
        // Create exception by error level
        if ($errorLevel === E_NOTICE) {
            $phpExeption = new PhpNoticeException($errorMessage, $errorLevel, $errorFile, $errorLine);
        } elseif ($errorLevel === E_WARNING) {
            $phpExeption = new PhpWarningException($errorMessage, $errorLevel, $errorFile, $errorLine);
        } elseif ($errorLevel === E_ERROR) {
            $phpExeption = new PhpErrorException($errorMessage, $errorLevel, $errorFile, $errorLine);
        } else {
            $phpExeption = new PhpException($errorMessage, $errorLevel, $errorFile, $errorLine);
        }
        // Save info about exception in log file
        self::logException($phpExeption);
        // Decide about throw of exception
        self::decideThrowException($phpExeption, $errorLevel);
    }

    /**
     * Check stroutput on fatal error message
     * @param type $stdOutput
     * @return type
     */
    public static function checkFatalError($stdOutput) {
        if (strpos($stdOutput, "Fatal error")) {
            $errorMessage = trim(strip_tags($stdOutput));
            CFactory::getLogger()->fatal($errorMessage);
        }
        return $stdOutput;
    }

    /**
     * Binds default errors output and exceptions
     */
    public static function setErrorsHandlers() {
        // Add error handler for notices, warnings and errors
        set_error_handler('PhpException::throwPhpException', E_ALL);

        // Add error handler for fatal PHP errors
        ob_start('PhpException::checkFatalError');
    }

    /**
     * Logs info about exception in file by differents type of message
     * @param PhpException $ex
     */
    protected static function logException(PhpException $ex) {
        if ($ex instanceof PhpWarningException) {
            CFactory::getLogger()->warn($ex->getLogMessage());
        } elseif ($ex instanceof PhpNoticeException) {
            CFactory::getLogger()->info($ex->getLogMessage());
        } else {
            CFactory::getLogger()->error($ex->getLogMessage());
        }
    }

    /**
     * Decide about throw exception or no
     * @param PhpException $phpExeption
     * @param int $errorLevel
     * @throws PhpException
     */
    protected static function decideThrowException(PhpException $phpExeption, $errorLevel) {
        $reportLevel = CFactory::getConfig()->errorReportingLevel;
        // If it is production
        if (!CFactory::getConfig()->debugMode) {
            // Ignore report level: no one error shows
            $reportLevel = 0;
        }
        // If error level without exception show
        if ($reportLevel == 0) {
            return;
        }
        // If report level in maximum: show all exception
        if ($errorLevel === E_NOTICE && $reportLevel == E_NOTICE) {
            throw $phpExeption;
            // If report level in middle: show only warnings and errors    
        } elseif ($errorLevel === E_WARNING &&
                ($reportLevel == E_NOTICE || $reportLevel == E_WARNING)) {
            throw $phpExeption;
            // If report level in minimum: show only errors 
        } elseif ($errorLevel === E_ERROR &&
                ( $reportLevel == E_NOTICE || $reportLevel == E_WARNING || $reportLevel == E_ERROR)) {
            throw $phpExeption;
        } else {
            // Show other types of errors everytime
            throw $phpExeption;
        }
    }

}

/**
 * Class for PHP notices
 */
class PhpNoticeException extends PhpException {
    
}

/**
 * Class for PHP warnings
 */
class PhpWarningException extends PhpException {
    
}

/**
 * Class for PHP errors
 */
class PhpErrorException extends PhpException {
    
}

/**
 * Class for PHP fatal errors
 */
class PhpFatalException extends PhpException {
    
}

/**
 * Class for language exception
 */
class LanguagesException extends AppException {
    
}

/**
 * Class for array config exception
 */
class ArrayConfigException extends AppException {
    
}

/**
 * Class for ini config exception
 */
class IniConfigException extends AppException {
    
}

/**
 * Class for controller exception 
 */
class ControllerException extends AppException {
    
}

?>
