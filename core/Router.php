<?php

if (!defined('INSIDE_ACCESS')) {
    die('No access to script!');
}

/**
 * Contains class Router
 * 
 * @version	$Id: Router.php Feb 15, 2012 1:24:07 PM Z slava.poddubsky $
 * @package	CDA
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

/**
 * Class for load controller actions by input url
 */
class Router {

    private static $currentUrl;

    /**
     * Return short url without domain
     * @return type 
     */
    public static function getCurrentUrl() {
        return self::$currentUrl;
    }

    /**
     * Return full url with domain
     * @return string
     */
    public static function getFullCurrentUrl() {
        return BASE_URI . self::$currentUrl;
    }

    /**
     * Path to roures configuration file
     * @param string $routesPath 
     */
    function __construct() {
        // Load currect url
        self::$currentUrl = $this->getURI();
        self::$currentUrl = $this->prepareURI(self::$currentUrl);
    }

    /**
     * Gets URI with many ways for safety
     * @return string
     */
    protected function getURI() {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if (!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if (!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
    }

    /**
     * Run controller by clean URL
     * @param string $internalRoute
     */
    protected function runController($segments) {
        // First segment - controller.
        $controllerName = ucfirst(array_shift($segments));
        $controllerClass = $controllerName . 'Controller';
        // Second - action.
        $actionSuffix = ucfirst(array_shift($segments));
        // If no action - use default Index action
        if ($actionSuffix == '') {
            $actionSuffix = 'Index';
        }
        $action = 'action' . $actionSuffix;
        // Other segments - params.
        $parameters = $segments;

        // Include file of controller if it exist
        $controllerFile = CONTROLLERS_DIR . DS . $controllerClass . '.php';
        if (file_exists($controllerFile)) {
            include($controllerFile);
            $controller = new $controllerClass($controllerName);
        } else {
            self::show404page();
            return;
        }

        // If controller class or method not exist show 404 page
        if (!is_callable(array($controller, $action))) {
            self::show404page();
            return;
        }

        // Call contriller action with params
        call_user_func_array(array($controller, $action), $parameters);
    }

    /**
     * Cleans URI
     * @param string $uri 
     * @return string clean URI
     */
    protected function prepareURI($uri) {
        if (CFactory::getConfig()->useRewriteUrl) {
            // Cleans index.php?...
            $index = strpos($uri, 'index.php');
            if ($index !== false) {
                $uri = str_replace(substr($uri, $index), '', $uri);
            }
            $getParams = strpos($uri, '?');
            if ($getParams !== false) {
                $uri = str_replace(substr($uri, $getParams), '', $uri);
            }
        }
        // Remove subdir part
        if (SUB_URI != '') {
            $uri = str_replace(SUB_URI . '/', '', $uri);
            $uri = str_replace(SUB_URI, '', $uri);
        }
        return $uri;
    }

    /**
     * Show default 404 page
     */
    public static function show404page() {
        header("HTTP/1.0 404 Not Found");
        require_once ROOT_PATH . DS . 'core' . DS . 'View.php';
        $currentUrl = Router::getFullCurrentUrl();
        CFactory::getLogger()->warn("404. Not found page: {$currentUrl}");
        $view = new View();
        $view->setControllerName('system');
        $view->render('404page');
    }

    /**
     * Convert controller short and action names in url  
     * @param string $controller
     * @param string $action
     * @param string $params
     * @return string 
     */
    public static function toUrl($controller, $action, $params = array()) {
        $url = '';
        if (CFactory::getConfig()->useRewriteUrl) {
            $url = BASE_URI . $controller . '/' .
                    $action;
            if (count($params) > 0) {
                $url .= '/' . implode('/', $params);
            }
        } else {
            $url = BASE_URI . 'index.php?c=' . $controller . '&a=' . $action;
            if (count($params) > 0) {
                foreach ($params as $key => $param) {
                    $url .= "&$key=$param";
                }
            }
        }
        return $url;
    }

    /**
     * Execute controllers by input URI
     */
    public function run() {
        // Load config data
        $config = CFactory::getConfig();
        $action = array();
        // If url is empty or main page
        if (self::$currentUrl == '' ||
                self::$currentUrl == 'index.php') {
            $action = $config->defaultAction;
        } elseif ($config->useRewriteUrl) {
            // If rewrite urls using
            $action = explode('/', self::$currentUrl);
        } else {
            // If usually urls
            foreach ($_GET as $key => $param) {
                if ($key == 'c') {
                    $action[0] = $param;
                } elseif ($key == 'a') {
                    $action[1] = $param;
                } else {
                    $action[] = $param;
                }
            }
        }
        // Run controller
        $this->runController($action);
    }

}

?>
