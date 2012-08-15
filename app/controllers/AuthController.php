<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class AuthController
 * 
 * @version	$Id: AuthController.php Mar 1, 2012 4:29:37 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

require_once ROOT_PATH . DS . 'core' . DS . 'Controller.php';
require_once MODELS_DIR . DS . 'User.php';
require_once APP_PATH . DS . 'forms' . DS . 'LoginForm.php';

/**
 * Class for user login
 */
class AuthController extends Controller {

    public function actionLogin() {
        $viewValues = array(
            'dataErrors' => array(),
            'fieldsErrors' => array(),
            'loginData' => array()
        );
        if(Session::isExistInTemp('loginDataErrors')){
            $viewValues['dataErrors'] = 
                Session::fromTempStorage('loginDataErrors');
        }
        if(Session::isExistInTemp('loginFieldsErrors')){
            $viewValues['fieldsErrors'] = 
                Session::fromTempStorage('loginFieldsErrors');
        }
        if(Session::isExistInTemp('loginData')){
            $viewValues['loginData'] = 
                Session::fromTempStorage('loginData');
        }
        $this->view->setTitleLabelKey('LOGIN');
        $this->view->setSelectedMenu('home');
        $this->view->setShowTitle(false);
        $this->view->render('login', $viewValues);        
    }
    
    public function actionSign() {
        $post = $_POST['loginData'];
        $form = new LoginForm();
        $form->load($post);
        if(!$form->isValid()){
            Session::inTempStorage('loginDataErrors', 
                    $form->getErrors());
            Session::inTempStorage('loginFieldsErrors', 
                    $form->getFieldsErrors());
            Session::inTempStorage('loginData', 
                    $form->getData());
        } else {
            if(!User::enter($form->getLogin(), $form->getPassword())){
                Session::inTempStorage('loginDataErrors', 
                        array(Controller::createMessage('USER_NOT_EXIST')));
            } else {
                if($post['returnUrl']){
                    $this->redirect($post['returnUrl']);
                } else {
                    $this->redirect('');
                }
            }
        }
        $this->redirect('auth/login');
    }
    
    public function actionExit(){
        Session::removeValue(User::USER_AUTH_FIELD);
        $this->redirect('auth/login');
    }

}

?>
