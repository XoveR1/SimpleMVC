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
 * Class for work with session
 */
class Session {

    protected static $storageName = "storage";

    /**
     * Start session
     */
    public static function startSessions() {
        $lifeTime = CFactory::getConfig()->lifeSessionTime * 60;
        //ini_set('session.save_path', $_SERVER['DOCUMENT_ROOT'] .'/../tmp/');
        //ini_set('session.gc_maxlifetime', $lifeTime);
        //ini_set('session.cookie_lifetime', $lifeTime);
        session_start();
    }

    /**
     * Save value in session only on one redirect
     * All data will be clean after redirect
     * @param string $key
     * @param mixed $value 
     */
    public static function inTempStorage($key, $value) {
        $_SESSION[self::$storageName][$key] = $value;
        $session = $_SESSION[self::$storageName];
        $session = 0;
    }

    /**
     * Load data from temp srorage 
     * @param string $key
     * @return mixed
     */
    public static function fromTempStorage($key) {
        $data = $_SESSION[self::$storageName][$key];
        return $data;
    }

    /**
     * Clear storage item by key
     * @param string $key 
     */
    public static function cleanTempByKey($key) {
        unset($_SESSION[self::$storageName][$key]);
    }

    /**
     * Clean all data from temp storage
     */
    public static function cleanTempStorage() {
        unset($_SESSION[self::$storageName]);
    }

    /**
     * Check exist of record by key
     * @param string $key
     * @return bool 
     */
    public static function isExistInTemp($key) {
        return isset($_SESSION[self::$storageName][$key]);
    }

    /**
     * Set value in session
     * @param string $key
     * @param mixed $value 
     */
    public static function setValue($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Get value from session
     * @param string $key
     * @return mixed 
     */
    public static function getValue($key) {
        return $_SESSION[$key];
    }

    /**
     * Check exist of key 
     * @param string $key
     * @return bool 
     */
    public static function isExist($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove value by key
     * @param string $key 
     */
    public static function removeValue($key) {
        unset($_SESSION[$key]);
    }

}

?>
