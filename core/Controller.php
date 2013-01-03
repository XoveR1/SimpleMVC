<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Controller
 * 
 * @version	$Id: Controller.php Feb 15, 2012 2:56:54 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */
require_once ROOT_PATH . DS . 'core' . DS . 'View.php';

/**
 * Base class for controllers 
 */
class Controller {

    /**
     * View object
     * @var View 
     */
    protected $view;

    /**
     * Messages after redirect
     * @var array
     */
    protected $messages = array();

    const MSG_INFO = 'info';
    const MSG_SUCCESS = 'success';
    const MSG_ERROR = 'error';

    /**
     * Contains controller name
     * @example Users
     * @var string
     */
    protected $controllerName;

    function __construct($controllerName = '') {
        $this->controllerName = $controllerName;
        Label::$controllerName = $controllerName;
        $this->view = new View();
        $this->view->setControllerName($controllerName);
        if (Session::isExistInTemp('messages')) {
            $this->view->setMessages(Session::fromTempStorage('messages'));
        }
    }

    /**
     * Add message for show after redirect
     * @param string $message
     * @param string $type use constants MSG_INFO, MSG_SUCCESS, MSG_ERROR
     */
    protected function addMessage($message, $type = self::MSG_ERROR) {
        $this->messages[] = self::createMessage($message, $type);
        Session::inTempStorage('messages', $this->messages);
    }

    /**
     * Add messages for show after redirect
     * @param array $messages
     */
    protected function addMessages($messages) {
        $this->messages = array_merge($this->messages, $messages);
        Session::inTempStorage('messages', $this->messages);
    }

    /**
     * Create message
     * @param string $message
     * @param string $type use constants MSG_INFO, MSG_SUCCESS, MSG_ERROR
     * @return array Message array
     */
    public static function createMessage($message, $type = self::MSG_ERROR) {
        $error = array(
            'message' => $message,
            'type' => $type
        );
        return $error;
    }

    /**
     * Redirect with message
     * @param string $url if null - current url
     */
    protected function redirect($url = null) {
        if (is_null($url)) {
            $url = Router::getCurrentUrl();
        }
        $url = BASE_URI . $url;
        Session::inTempStorage('isRedirect', true);
        header('Location: ' . $url);
        exit;
    }

}

?>
