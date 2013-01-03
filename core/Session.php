<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Session
 * 
 * @version	$Id: Session.php Feb 17, 2012 12:03:07 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for work with independ session
 */
class Session {

    protected static $storageName = "storage";
    protected static $appSessionName = 'appSession';
    protected static $sessionId = '';

    /**
     * Start session
     */
    public static function startSessions() {
        session_start();
        self::$sessionId = session_id();
    }

    /**
     * Save value in session only on one redirect
     * All data will be clean after redirect
     * @param string $key
     * @param mixed $value 
     */
    public static function inTempStorage($key, $value) {
        $_SESSION[self::$appSessionName][self::$storageName][$key] = $value;
    }

    /**
     * Load data from temp srorage 
     * @param string $key
     * @return mixed
     */
    public static function fromTempStorage($key) {
        $data = $_SESSION[self::$appSessionName][self::$storageName][$key];
        return $data;
    }

    /**
     * Clear storage item by key
     * @param string $key 
     */
    public static function cleanTempByKey($key) {
        unset($_SESSION[self::$appSessionName][self::$storageName][$key]);
    }

    /**
     * Clean all data from temp storage
     */
    public static function cleanTempStorage() {
        unset($_SESSION[self::$appSessionName][self::$storageName]);
    }

    /**
     * Check exist of record by key
     * @param string $key
     * @return bool 
     */
    public static function isExistInTemp($key) {
        return isset($_SESSION[self::$appSessionName][self::$storageName][$key]);
    }

    /**
     * Set value in session
     * @param string $key
     * @param mixed $value 
     */
    public static function setValue($key, $value) {
        $_SESSION[self::$appSessionName][$key] = $value;
    }

    /**
     * Get value from session
     * @param string $key
     * @return mixed 
     */
    public static function getValue($key) {
        return $_SESSION[self::$appSessionName][$key];
    }

    /**
     * Check exist of key 
     * @param string $key
     * @return bool 
     */
    public static function isExist($key) {
        return isset($_SESSION[self::$appSessionName][$key]);
    }

    /**
     * Remove value by key
     * @param string $key 
     * @return string Old value from session
     */
    public static function removeValue($key) {
        $value = $_SESSION[self::$appSessionName][$key];
        unset($_SESSION[self::$appSessionName][$key]);
        return $value;
    }
    
    /**
     * Return current session id
     * @return string String with current session id
     */
    public static function getSessionId(){
        return self::$sessionId;
    }
    
    /**
     * Destroy all sessionj data
     * @return bool TRUE on success or FALSE on failure.
     */
    public static function destroy(){
        unset($_SESSION[self::$appSessionName]);
    }

}

?>
