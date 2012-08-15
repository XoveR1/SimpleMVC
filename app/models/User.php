<?php

if(!defined('INSIDE_ACCESS')){
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
require_once ROOT_PATH . DS . 'core' . DS . 'Model.php';
require_once ROOT_PATH . DS . 'core' . DS . 'interfaces' . DS . 'IAuthentication.php';

/**
 * Class for site user
 */
class User extends ARModel implements IAuthentication {

    static $table_name = 'site_users';

    const USER_AUTH_FIELD = 'UserId';
    
    protected $returnUrl = '';

    public function getId() {
        return (int) $this->u_code;
    }

    public function getLogin() {
        return strtolower($this->u_id);
    }

    public function getEmail() {
        return strtolower($this->u_mail);
    }

    public function isAdmin() {
        return $this->u_id != '';
    }
    
    public function getReturnUrl() {
        return $this->returnUrl;
    }

    public function setReturnUrl($returnUrl) {
        $this->returnUrl = $returnUrl;
    }
    
    /**
     * Enter on site as user with same login and password
     * @param string $login
     * @param string $password
     * @return User or boolean  
     */
    public static function enter($login, $password) {
        //$user = User::find('first', array('conditions' => 
        // array('(u_id = ? OR u_mail = ?) AND u_pass = ?', $login, $login, md5($password))));
        if (md5($password) == Factory::getConfig()->admin_password &&
                strcasecmp($login, Factory::getConfig()->admin_login) == 0) {
            Session::setValue(self::USER_AUTH_FIELD, $login);
            return true;
        } else {
            return false;
        }
    }

}

?>
