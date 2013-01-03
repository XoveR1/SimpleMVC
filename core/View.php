<?php

if (!defined('INSIDE_ACCESS')) {
    die('No access to script!');
}

/**
 * Contains class View
 * 
 * @version	$Id: View.php Feb 15, 2012 3:16:05 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for view
 */
class View {

    protected $titleLabelKey;
    protected $controllerName;
    protected $selectedMenu;
    protected $isShowTitle = true;
    protected $messages;
    protected static $includeScript = array();
    protected static $includeStyles = array();

    public function getTitleLabelKey() {
        return $this->titleLabelKey;
    }

    public function setTitleLabelKey($titleLabelKey) {
        $this->titleLabelKey = $titleLabelKey;
    }

    public function isTitle() {
        return isset($this->titleLabelKey);
    }

    public function getControllerName() {
        return $this->controllerName;
    }

    public function setControllerName($controllerName) {
        $this->controllerName = $controllerName;
    }

    public function getSelectedMenu() {
        return $this->selectedMenu;
    }

    public function setSelectedMenu($selectedMenu) {
        $this->selectedMenu = $selectedMenu;
    }

    public function setMessages($messages) {
        $this->messages = $messages;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getShowTitle() {
        return $this->isShowTitle;
    }

    public function setShowTitle($isShowTitle) {
        $this->isShowTitle = $isShowTitle;
    }

    public function isMessages() {
        return isset($this->messages);
    }

    public function addScript($scriptName) {
        self::$includeScript[] = $scriptName;
    }

    public function addStyle($styleName) {
        self::$includeStyles[] = $styleName;
    }

    /**
     * Render specific template with $params
     * @param string $template - template name (without extensions and path)
     * @param array $params - associative array with values
     * @return string - rendered html 
     */
    public function fetchPartial($template, $params = array()) {
        extract($params);
        ob_start();
        if ($template == 'layout' || isset($params['layout'])) {
            $template = 'layout' . DS . $template;
        } else {
            $template = strtolower($this->controllerName) . DS . $template;
        }

        include VIEWS_DIR . DS . $template . '.php';
        return ob_get_clean();
    }

    /**
     * Show rendered template with $params
     * @param string $template - template name (without extensions and path)
     * @param array $params - associative array with values 
     */
    public function renderPartial($template, $params = array()) {
        echo $this->fetchPartial($template, $params);
    }

    /**
     * Insert in value $content rendered template for layout
     * @param string $template - template name (without extensions and path)
     * @param array $params - associative array with values 
     * @return string - rendered html 
     */
    public function fetch($template, $params = array()) {
        $content = $this->fetchPartial($template, $params);
        return $this->fetchPartial('layout', array('content' => $content));
    }

    /**
     * Show value $content rendered template for layout 
     * @param string $template - template name (without extensions and path)
     * @param array $params - associative array with values 
     */
    public function render($template, $params = array()) {
        echo $this->fetch($template, $params);
    }

    /**
     * Load array of scripts with full path
     * @return array 
     */
    public static function getScriptsList() {
        $config = Factory::getConfig();
        $scripts = array();
        $scriptsList = array_merge(array($config->jQueryFile), 
                $config->scripts, self::$includeScript);
        foreach ($scriptsList as $scriptName) {
            $scripts[] = self::viewFileToPath($scriptName . '.js');
        }
        return $scripts;
    }

    /**
     * Load array of styles with full path
     * @return array 
     */
    public static function getStylesList() {
        $config = CFactory::getConfig();
        $styles = array();
        $stylesList = array_merge($config->styles, self::$includeStyles);
        foreach ($stylesList as $styleName) {
            $styles[] = self::viewFileToPath($styleName . '.css');
        }
        return $styles;
    }

    /**
     * Render menu items
     * @return array
     */
    public function getMenuList() {
        $config = CFactory::getConfig();
        $menuItems = array();
        // Render all items
        foreach ($config->menu as $menuItem) {
            // If exist sub items
            if (isset($menuItem['items'])) {
                $subItems = array();
                // Render subitems
                foreach ($menuItem['items'] as $subItem) {
                    $subItems[$subItem['menuKey']] =
                            $this->renderMenuItem($subItem);
                }
                $menuItem['items'] = $subItems;
            }
            $menuItems[$menuItem['menuKey']] =
                    $this->renderMenuItem($menuItem);
        }
        return $menuItems;
    }

    /**
     * Show exception to user in standart output
     * @param Exception $ex
     */
    public static function showExeption(Exception $ex) {
        $view = new View();
        $view->setControllerName('system');
        $view->render('exception', array('ex' => $ex));
    }
    
    /**
     * Convert file name that using in views in full path.
     * For type detect using extension of file.
     * If file is not contains extension 'js' or'css' throw exception.
     * If file is not exist throw exception.
     * @param  string       $fileName       Name of file with extension. 
     *                                      If parametr equal null return jQuery path.
     * @return string       Full path to view file.
     * @throws AppException
     */
    public static function viewFileToPath($fileName = null){
        $filePath = '';
        $fileUrl = '';
        // If file name equal null using jQuery file name
        if(is_null($fileName)){
            $fileName = CFactory::getConfig()->jQueryFile . '.js';
        }
        $pathInfo = pathinfo($fileName);
        // If file have 'js' extension 
        if($pathInfo['extension'] == 'js'){
            $fileUrl = BASE_URI . 'app/views/layout/js/' . $fileName;
            $filePath = APP_PATH . DS . 'views' . DS . 'layout' . DS . 'js' . DS . $fileName;
        // If file have 'css' extension 
        } elseif($pathInfo['extension'] == 'css') {
            $fileUrl = BASE_URI . 'app/views/layout/css/' . $fileName;
            $filePath = APP_PATH . DS . 'views' . DS . 'layout' . DS . 'css' . DS . $fileName;
        // Otherwise throw exception
        } else {
            throw new AppException('Undefined type of included file.');
        }
        // Checks existing of file
        if(!file_exists($filePath)){
            throw new AppException("File '$filePath' not exist!");
        }
        return $fileUrl;
    }

    /**
     * Renders menu item
     * @param array $item
     * @return array
     */
    protected function renderMenuItem($item) {
        // If current item of menu - select it
        if ($item['menuKey'] == $this->selectedMenu) {
            $item['selected'] = true;
        } else {
            $item['selected'] = false;
        }
        // If usually internal system url
        if (isset($item['url'])) {
            $item['url'] = BASE_URI . $item['url'];
        }
        // If external full url
        if (isset($item['fullUrl'])) {
            $item['url'] = $item['fullUrl'];
            unset($item['fullUrl']);
        }
        return $item;
    }

}

?>
