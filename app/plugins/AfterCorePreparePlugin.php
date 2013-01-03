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
    
    /**
     * Not checked urls
     * @var array 
     */
    protected $notCheckUrls = array(
        'auth/sign',
        'auth/login',
        'logger/client'
    );
    
    /**
     * Check access of user 
     */
    public function executeUserAuth(){
        // Get current user obkect
        $user = Factory::getCurrentUser();
        // If is guest check access rights
        if($user->isGuest()){
            // Guest have access only on some pages
            if(!in_array(Router::getCurrentUrl(), $this->notCheckUrls)){
                // Save redirect url for back after
                Session::setValue('redirect_url', Router::getCurrentUrl());
                // Save post for transmit after login
                Session::setValue('post', $_POST);
                // Run login action in current page
                require_once ROOT_PATH . DS . 'core' . DS . 'Controller.php';
                // Create error message
                Session::inTempStorage('loginDataErrors', array(Controller::createMessage('ERROR_NO_ACCESS')));
                // Execute action
                Core::instance()->execute('Auth')->actionLogin();
                exit;
            }
        }
        if($user->isAdmin()){
            if(Session::isExist('post')){
                $_POST = Session::removeValue('post');
            }
        }
    }
    
    /**
     * Include global includes of scripts that using everywhere
     */
    public static function executeGlobalInclude(){
        require_once APP_PATH . DS . 'classes' . DS . 'RegexLib.php';
    } 
}

?>
