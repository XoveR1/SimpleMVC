<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class AfterCorePreparePlugin
 * 
 * @version	$Id: AfterCorePreparePlugin.php Mar 3, 2012 11:30:08 AM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

require_once ROOT_PATH . DS . 'core' . DS . 'Plugin.php';
require_once ROOT_PATH . DS . 'core' . DS . 'View.php';

/**
 * Class for after core prepare event
 */
class AfterCorePreparePlugin extends Plugin {
    
    protected $notCheckUrls = array(
        'auth/sign',
        'auth/login',
        'logger/client'
    );
    
    /**
     * Check access of user 
     */
    public function executeUserAuth(){
        $user = Factory::getCurrentUser();
        if(strcasecmp($user->getLogin(), Factory::getConfig()->admin_login) != 0){
            if(!in_array(Core::getCurrentUrl(), $this->notCheckUrls)){
                $user->setReturnUrl(Core::getCurrentUrl());
                require_once ROOT_PATH . DS . 'core' . DS . 'Controller.php';
                Session::inTempStorage('loginDataErrors', array(Controller::createMessage('ERROR_NO_ACCESS')));
                Core::instance()->execute('Auth')->actionLogin();
                exit;
            }
        }
    }
    
    public static function executeGlobalInclude(){
        require_once APP_PATH . DS . 'classes' . DS . 'RegexLib.php';
    } 
}

?>
