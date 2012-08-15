<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains interface IList
 * 
 * @version	$Id: IList.php Feb 28, 2012 4:05:28 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Interface for list obkect 
 */
interface IList {
    public function add($item);
    public function removeAt($index);
    public function remove($item);
    public function item($index);
    public function count();
    public function isExist($index);
}

?>
