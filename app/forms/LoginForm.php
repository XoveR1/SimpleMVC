<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class LoginForm
 * 
 * @version	$Id: LoginForm.php Mar 2, 2012 9:38:48 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
require_once ROOT_PATH . DS . 'core' . DS . 'Form.php';

/**
 * Class for login form
 */
class LoginForm extends Form {

    protected $login;
    protected $password;
    protected $remember = false;

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function getRemember() {
        return $this->remember;
    }

    public function setRemember($remember) {
        $this->remember = $remember;
    }
    
    public function load($post) {
        $this->login = $post['login'];
        $this->password = $post['password'];
        if(isset($post['remember'])){
            $this->remember = (bool)$post['remember'];
        }
    }

    public function getData() {
        return array(
            'login' => $this->login,
            'password' => $this->password,
            'remember' => $this->remember
        );
    }

    public function setData($data) {
        $this->load($data);
    }

    public function isValid() {
        $isValid = true;
        if(!$this->isTokenValid()){
            return false;
        }
        if ($this->login == '') {
            $this->addError(Label::_('ERROR_FIELD_REQUIRED'), 'error', 'login');
            $isValid = false;
        } else {
            if (!preg_match('/^[\w\d]+$/', $this->login)) {
                $this->addError('ERROR_NOT_VALID_CHARACTERS', 'error', 'login');
                $isValid = false;
            }
        }
        if ($this->password == '') {
            $this->addError(Label::_('ERROR_FIELD_REQUIRED'), 'error', 'password');
            $isValid = false;
        }
        return $isValid;
    }

}

?>
