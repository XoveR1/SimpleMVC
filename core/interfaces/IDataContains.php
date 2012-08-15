<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains interface IDataContains
 * 
 * @version	$Id: IDataContains.php Feb 15, 2012 5:37:31 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

interface IDataContains {
    public function getData();

    public function setData($data);
}
?>