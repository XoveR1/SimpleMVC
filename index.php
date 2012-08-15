<?php

// Most usage constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', dirname(__FILE__));
define('INSIDE_ACCESS', 1);

// Include need files
require_once ROOT_PATH . DS . 'core' . DS . 'Core.php';

// Create core object and run it
Core::instance()->run();

?>
