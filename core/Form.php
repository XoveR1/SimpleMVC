<?php

if(!defined('INSIDE_ACCESS')){
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

require_once ROOT_PATH . DS . 'core' . DS . 'interfaces' . DS .'IDataContains.php';

/**
 * Class for forms
 */
 abstract class Form implements IDataContains {    
    public abstract function isValid();
    
    protected $data;
    protected $fieldErrors = array();
    protected $dataErrors = array();
    
    protected function addError($message, $type = 'error', $field = null) {
        $error = array(
            'message' => $message,
            'type' => $type
        );
        if(!is_null($field)){
            $this->fieldErrors[$field] = $error;
        } else {
            $this->dataErrors[] = $error;
        }
    }
    
    public function getErrors() {
        return $this->dataErrors;
    }
    
    public function getFieldsErrors() {
        return $this->fieldErrors;
    }
        
    public function getData() {
        return $this->data;
    }
    
    public function setData($data) {
        $this->data = $data;
    }
}

?>
