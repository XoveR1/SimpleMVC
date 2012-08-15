<?php

//error_reporting(E_ERROR);
define('SECURITY_STATISTIC', true);

function redirect($page = '/p-2-contact.html') {
  //  file_put_contents('php://stdout', '');
    header('Location: ' . $page);
    $error = error_get_last();
    if (intval($error['type']) == 2) {
        $script = '<script type="text/javascript">';
        $script .= 'window.location.href="' . $page . '";';
        $script .= '</script>';
        die($script);
    }
    exit;
}

// Most usage constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', str_replace('/', DS, $_SERVER['DOCUMENT_ROOT']) . DS . 'securityp');
define('INSIDE_ACCESS', 1);

// Include need files
require_once ROOT_PATH . DS . 'core' . DS . 'Core.php';
?>
