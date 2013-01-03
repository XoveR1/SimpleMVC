<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class ObjectList
 * 
 * @version	$Id: ObjectList.php Feb 28, 2012 4:26:59 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
require_once ROOT_PATH . DS . 'core' . DS . 'interfaces' . DS . 'IList.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Exceptions.php';

/**
 * AppException for no item by key situation
 */
class NoItemException extends AppException {
    
}

/**
 * Class for objects list
 */
abstract class ObjectList implements IList, Iterator {

    protected $list = array();

    /**
     * Create list of objects
     * @param array $list 
     */
    function __construct($list = array()) {
        if (is_array($list) && count($list) > 0) {
            foreach ($list as $item) {
                if (is_object($item)) {
                    $this->add($item);
                }
            }
        }
    }

    /**
     * Add item in list
     * @param object $item
     * @param mixed $key 
     */
    public function add($item, $key = null) {
        if (is_null($key)) {
            $this->list[] = $item;
        } else {
            $this->list[$key] = $item;
        }
    }

    /**
     * Get count objects in list
     * @return int
     */
    public function count() {
        return count($this->list);
    }

    /**
     * Get item by index
     * @param int $index
     * @return object
     * @throws NoItemException 
     */
    public function item($index) {
        if (!isset($this->list[$index])) {
            throw new NoItemException("No index: $index");
        } else {
            return $this->list[$index];
        }
    }

    /**
     * Remove object from list by object
     * @param object $item
     */
    public function remove($item) {
        foreach ($this->list as $index => $element) {
            if ($element == $item) {
                unset($this->list[$index]);
                return;
            }
        }
    }

    /**
     * Remove object from list by index
     * @param mixed $index
     * @throws NoItemException 
     */
    public function removeAt($index) {
        if (isset($this->list[$index])) {
            throw new NoItemException("No index: $index");
        } else {
            unset($this->list[$index]);
            return;
        }
    }

    /**
     * Checks exist of index
     * @param mixed $index
     * @return bool 
     */
    public function isExist($index) {
        return isset($this->list[$index]);
    }

    public function current() {
        return current($this->list);
    }

    public function key() {
        return key($this->list);
    }

    public function next() {
        next($this->list);
    }

    public function rewind() {
        reset($this->list);
    }

    public function valid() {
        return key_exists(key($this->list), $this->list);
    }

    /**
     * Get item by paiement key
     * @param string $fieldName
     * @param string $fieldValue
     * @return mixed
     */
    public function getItemBy($fieldName, $fieldValue) {
        if ($this->count() > 0) {
            $methodName = 'get' . ucfirst($fieldName);
            // If using getters
            if (method_exists($this->list[0], $methodName)) {
                foreach ($this->list as $value) {
                    if ($value->$methodName() == $fieldValue) {
                        return $value;
                    }
                }
            } else { // If using public fields
                foreach ($this->list as $value) {
                    if ($value->$fieldName == $fieldValue) {
                        return $value;
                    }
                }
            }
        }
        return false;
    }

}

?>
