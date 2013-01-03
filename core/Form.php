<?php

if (!defined('INSIDE_ACCESS')) {
    die('No access to script!');
}

/**
 * Contains class Form
 * 
 * @version	$Id: Form.php Feb 16, 2012 3:18:35 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
require_once ROOT_PATH . DS . 'core' . DS . 'interfaces' . DS . 'IDataContains.php';

/**
 * Class for forms
 */
abstract class Form implements IDataContains {

    protected $data;
    protected $token;
    protected $fieldErrors = array();
    protected $dataErrors = array();

    /**
     * Add error message that will show after page be redirect
     * @param string $message   String with message. 
     *                          Message will check on multilanguage support.
     * @param string $type      Type can be 'error', 'success' or 'info'
     *                          @see Controller for messages constants
     * @param string $field     Field name with error
     */
    protected function addError($message, $type = 'error', $field = null) {
        $error = array(
            'message' => $message,
            'type' => $type
        );
        if (!is_null($field)) {
            $this->fieldErrors[$field] = $error;
        } else {
            $this->dataErrors[] = $error;
        }
    }

    /**
     * Gets errors list
     * @return array
     */
    public function getErrors() {
        return $this->dataErrors;
    }

    /**
     * Get array with fields errors
     * @return type
     */
    public function getFieldsErrors() {
        return $this->fieldErrors;
    }

    /**
     * Get data of form in normolaised view
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Set data in form object from post
     * @param array $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * Generate token for form security.
     * Token depend from IP, secret word and if user is logined 
     * from his login, session id and increment value.
     * For every call this function will be return new token.
     * @return string
     */
    public function generateToken() {
        // Generate new token
        return $this->generateIncToken();
    }
    
    /**
     * Set token value for validation from post
     * @param string $token
     * @return \Form
     */
    public function setExternalToken($token){
        $this->token = $token;
        return $this;
    }

    /**
     * Check token from post and correct token on equals
     * and generate error message if they not equal
     * @return boolean Equal or not tokens
     */
    protected function isTokenValid() {
        if(!isset($this->token)){
            throw new AppException('External token is not set!');
        }
        if ($this->token !== $this->generateIncToken(false)) {
            $this->addError('ERROR_TOKEN_INVALID');
            $this->addError('TRY_POST_FORM_AGAIN', 'info');
            return false;
        }
        return true;
    }

    /**
     * Generate token with increment value
     * Token depend from IP, secret word and if user is logined 
     * from his login and session id
     * @param type $isNewToken
     * @return type
     */
    private function generateIncToken($isNewToken = true){
        $tokenIncrementField = 'tokenIncrement';
        if(Session::isExist($tokenIncrementField)){
            // Load token increment from session
            $tokenIncrement = (int)Session::getValue($tokenIncrementField);
        } else {
            // If no token icrement in session generate start token value
            $tokenIncrement = rand(100, 1000) - 1;
        }        
        if($isNewToken){
            // If it is generation of new token function should increment it 
            $tokenIncrement++;
            // and save new value in session
            Session::setValue('tokenIncrement', $tokenIncrement);
        }
        // Generation of token from user ip
        $tokenString = $_SERVER['REMOTE_ADDR'];
        $user = Factory::getCurrentUser();
        // If user is logined as normal user and program know his name
        if (!$user->isGuest()) {
            // Add in token his login
            $tokenString .= $user->getLogin();
            // and session id
            $tokenString .= Session::getSessionId();
        }
        // Add secret word
        $tokenString .= Factory::getConfig()->secretWord;
        // Add token increment for unique token
        $tokenString .= $tokenIncrement;
        // Create hash
        return md5($tokenString);
    }
}

?>