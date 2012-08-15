<?php

if(!defined('INSIDE_ACCESS')){
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
        $scriptsList = array_merge($config->scripts, self::$includeScript);
        foreach ($scriptsList as $scriptName) {
            $scriptPath = BASE_URI . 'app/views/layout/js/' . $scriptName . '.js';
            $scripts[] = $scriptPath;
        }
        return $scripts;
    }

    /**
     * Load array of styles with full path
     * @return array 
     */
    public static function getStylesList() {
        $config = Core::instance()->getConfig();
        $styles = array();
        $stylesList = array_merge($config->styles, self::$includeStyles);
        foreach ($stylesList as $styleName) {
            $stylePath = BASE_URI . 'app/views/layout/css/' . $styleName . '.css';
            $styles[] = $stylePath;
        }
        return $styles;
    }

    protected function renderMenuItem($item) {
        if ($item['menuKey'] == $this->selectedMenu) {
            $item['selected'] = true;
        } else {
            $item['selected'] = false;
        }
        if(isset($item['url'])){
            $item['url'] = BASE_URI . $item['url'];
        }
        if(isset($item['fullUrl'])){
            $item['url'] = $item['fullUrl'];
            unset($item['fullUrl']);
        }
        return $item;
    }

    /**
     * Render menu items
     * @return array
     */
    public function getMenuList() {
        $config = Core::instance()->getConfig();
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

}

?>
