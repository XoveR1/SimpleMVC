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
        $form = new LoginForm();
        $viewValues = array(
            'dataErrors' => array(),
            'fieldsErrors' => array(),
            'loginData' => array(),
            'formToken' => $form->generateToken()
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
        $form->setExternalToken($_POST['formToken']);
        $form->load($post);
        if(!$form->isValid()){
            Session::inTempStorage('loginDataErrors', 
                    $form->getErrors());
            Session::inTempStorage('loginFieldsErrors', 
                    $form->getFieldsErrors());
            Session::inTempStorage('loginData', 
                    $form->getData());
        } else {
            if(!User::enter($form->getLogin(), 
                    $form->getPassword(), $form->getRemember())){
                Session::inTempStorage('loginDataErrors', 
                        array(Controller::createMessage('USER_NOT_EXIST')));
            } else {
                if(Session::isExist('redirect_url')){
                    $redirectUrl = Session::removeValue('redirect_url');
                    $this->redirect($redirectUrl);
                }
                $this->redirect('');
            }
        }
        $this->redirect('auth/login');
    }
    
    public function actionExit(){
        User::closeSeance();
        $this->redirect('auth/login');
    }

}

?>
