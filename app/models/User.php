<?php

if (!defined('INSIDE_ACCESS')) {
    die('No access to script!');
}

/**
 * Contains class User
 * 
 * @version	$Id: User.php Mar 1, 2012 4:34:14 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
require_once ROOT_PATH . DS . 'core' . DS . 'interfaces' . DS . 'IAuthentication.php';

/**
 * Class for site user
 */
class User implements IAuthentication {

    public function __construct($login = null) {
        if (!is_null($login)) {
            $this->isAdmin = true;
            $this->login = $login;
        }
    }

    protected $login;
    protected $isAdmin = false;

    const USER_AUTH_FIELD = 'login';

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getLogin() {
        return $this->login;
    }

    public function isAdmin() {
        return $this->isAdmin;
    }

    public function isGuest() {
        return !$this->isAdmin;
    }

    /**
     * Generate secret field to securite save in cookies.
     * Field depend from IP and secret word from configuration
     * @return string
     */
    public static function generateSecretField() {
        return md5($_SERVER['REMOTE_ADDR'] .
                        Factory::getConfig()->secretWord);
    }

    /**
     * Generate secret value to securite save in cookies.
     * @param string $value
     * @return string
     */
    public static function generateSecretValue($value) {
        return md5($value . $_SERVER['REMOTE_ADDR'] .
                        Factory::getConfig()->secretWord);
    }

    /**
     * Check cookies on user data save
     * @return \User If user exist in cookies return user object,
     *               otherwhise return null
     */
    public static function fromCookies() {
        $currentUser = null;
        // Session finished. Check in cookies
        if (Cookies::isExist(self::USER_AUTH_FIELD)) {
            // If user login in cookies load it from
            $login = Cookies::getValue(self::USER_AUTH_FIELD);
            // Additional check by secret field
            $secretField = self::generateSecretField();
            // If such field exist
            if (Cookies::isExist($secretField)) {
                // If secret field equal generated
                if (Cookies::getValue($secretField) == self::generateSecretValue($login)) {
                    // User is ok
                    $currentUser = new User($login);
                }
            }
        }
        return $currentUser;
    }
    
    /**
     * Clean all data from cookies and sessions about user
     */
    public static function closeSeance(){
        Cookies::removeByKey(self::USER_AUTH_FIELD);
        Cookies::removeByKey(self::generateSecretField());
        Session::destroy();
    }

    /**
     * Enter on site as user with same login and password
     * @param string $login
     * @param string $password
     * @param string $remember
     * @return User or boolean  
     */
    public static function enter($login, $password, $remember = false) {
        if (md5($password) == Factory::getConfig()->admin_password &&
                strcasecmp($login, Factory::getConfig()->admin_login) == 0) {
            if ($remember) {
                $time = CFactory::getConfig()->lifeSessionTime * 60;
                Cookies::setValue(self::USER_AUTH_FIELD, $login, $time);
                Cookies::setValue(self::generateSecretField(), self::generateSecretValue($login), $time);
            }
            Session::setValue(self::USER_AUTH_FIELD, $login);
            return true;
        } else {
            return false;
        }
    }

}

?>
