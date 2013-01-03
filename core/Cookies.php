<?php

/**
 * Contains class Cookies
 * 
 * @version	$Id: Cookies.php 27.09.2012 16:17:28 Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for cookies work
 */
class Cookies {

    /**
     * Set cookies value by key in time peiod in seconds.
     * Using http only cookies for moew security.
     * @param string $key Key index for cookies
     * @param string $value Value for cookies
     * @param int $time Time in seconds that was added to current time.
     *                  In defolt cookies live only befor brawser will close
     */
    public static function setValue($key, $value, $time = null) {
        if (is_null($time)) {
            $expire = 0;
        } else {
            $expire = time() + $time;
        }
        if (!setcookie($key, $value, $expire, '/')) {
            throw new AppException('Cookies disabled! Turn on it please.');
        }
    }

    /**
     * Get value from cookies by key index
     * @param string $key Key index for cookies
     * @return string Value from cookies
     */
    public static function getValue($key) {
        return $_COOKIE[$key];
    }

    /**
     * Checks exissting of value in cookies by key
     * @param string $key Key index for cookies
     * @return bool Exist value with such key or no
     */
    public static function isExist($key) {
        return isset($_COOKIE[$key]);
    }

    /**
     * Remove value from cookies by key
     * @param string $key
     * @return string
     */
    public static function removeByKey($key) {
        if (self::isExist($key)) {
            $oldValue = $_COOKIE[$key];
            setcookie($key, '', 0, '/');
            return $oldValue;
        }
        return null;
    }

}

?>
