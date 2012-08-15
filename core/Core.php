<?php

if(!defined('INSIDE_ACCESS')){
    die('No access to script!');
}

/**
 * Contains class Core
 * 
 * @version	$Id: Core.php Feb 15, 2012 5:24:32 PM Z slava.poddubsky $
 * @package	SimpleMVC
 * @subpackage	Core
 * @copyright	Copyright (C) 2012, Inc. All rights reserved.
 * @license	see LICENSE.txt
 */

define('APP_PATH', ROOT_PATH . DS . 'app');
define('SUB_URI', str_replace(str_replace('/', DS, $_SERVER['DOCUMENT_ROOT']) . DS, '', ROOT_PATH));
define('BASE_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/' . (SUB_URI != '' ? SUB_URI . '/' : ''));
define('CONTROLLERS_DIR', APP_PATH . DS . 'controllers');
define('MODELS_DIR', APP_PATH . DS . 'models');
define('VIEWS_DIR', APP_PATH . DS . 'views');
define('LANG_DIR', APP_PATH . DS . 'languages');
define('LIBS_DIR', ROOT_PATH . DS . 'libs');
define('SYSTEM_CONFIG_PATH', ROOT_PATH . DS . 'configs' . DS . 'system.php');

require_once ROOT_PATH . DS . 'app' . DS . 'classes' . DS . 'SystemConfig.php'; // Include system config
require_once ROOT_PATH . DS . 'core' . DS . 'Registry.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Exceptions.php';
require_once APP_PATH . DS . 'classes' . DS . 'Exceptions.php';
require_once ROOT_PATH . DS . 'core' . DS . 'CFactory.php';
require_once APP_PATH . DS . 'classes' . DS . 'Factory.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Router.php'; // Include router
require_once ROOT_PATH . DS . 'core' . DS . 'Json.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Session.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Label.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Plugin.php';

/**
 * Singleton for application core class
 */
class Core {

    private function __construct() {
        
    }

    /**
     * Singleton object.
     *
     * @var Core
     */
    private static $instance;
    private static $currentUrl;
    private static $internalControl = false;
    
    public static function isInternalControl($bFlag = true){
        self::$internalControl = $bFlag;
    }

    public static function getCurrentUrl() {
        return self::$currentUrl;
    }

    /**
     * Static method for instantiating a singleton object.
     *
     * @return Core
     */
    final public static function instance() {
        if (!isset(self::$instance)){
            self::$instance = new Core();
            self::$instance->prepareApp();
        }
        return self::$instance;
    }

    /**
     * Router object
     * @var Router
     */
    private $router;

    /**
     * System config object
     * @var SystemConfig
     */
    private $config;

    /**
     * System config object
     * @return SystemConfig 
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Prepare Router
     */
    private function prepareRouter() {
        // Include router configuration
        $routes = ROOT_PATH . DS . 'app' . DS . 'config' . DS . 'routes.php';

        // Create route
        $this->router = new Router($routes);

        // Save current url
        self::$currentUrl = $this->router->getCurrentUrl();
    }

    /**
     * Prepare database connection
     */
    private function prepareDBConnection() {
        // Include Active Record classes
        require_once LIBS_DIR . DS . 'ActiveRecord' . DS . 'ActiveRecord.php';

        // Configure Active Record
        $cfg = ActiveRecord\Config::instance();
        $cfg->set_model_directory(MODELS_DIR);
        $cfg->set_connections(array(
            'development' => $this->config->getConnectionString()));
    }

    /**
     * Execute plugins by event name
     * @param string $eventName name of event
     */
    public static function executePlugin($eventName) {
        $pathToPlugins = APP_PATH . DS . 'plugins' . DS;
        $pluginPrefix = 'Plugin';
        $pluginClass = $eventName . $pluginPrefix;
        $pluginPath = $pathToPlugins . $pluginClass . '.php';
        if (!class_exists($pluginClass)) {
            if (file_exists($pluginPath)) {
                include $pluginPath;
            } else {
                return;
            }
        }
        $plugin = new $pluginClass();
        $methods = get_class_methods($plugin);
        foreach ($methods as $method) {
            if (strpos($method, 'execute') !== false) {
                if (is_callable(array($plugin, $method))) {
                    call_user_func_array(array($plugin, $method), array());
                }
            }
        }
    }

    /**
     * Initialisation of application
     * @return /Core
     */
    protected function prepareApp() {
        $this->config = new SystemConfig(SYSTEM_CONFIG_PATH);
        $this->config->load();
        Session::startSessions();
        $this->prepareRouter();
        $this->prepareDBConnection();
        if(!self::$internalControl){
            self::executePlugin(Plugin::AFTER_CORE_PREPARE);
        }
        return $this;
    }

    /**
     * Create and return need controller in the context of the application
     * @param string $controllerName controller name
     * @example Core::instance()->execute('Users')->actionExample($params);
     * @return Controller 
     */
    public function execute($controllerShortName) {
        $controllerName = ucfirst($controllerShortName) . 'Controller';
        if (!class_exists($controllerName)) {
            $controllerPath = CONTROLLERS_DIR . DS . $controllerName . '.php';
            if (file_exists($controllerPath)) {
                include $controllerPath;
            } else {
                throw new ControllerException("$controllerName controller not exist!");
            }
        }
        return new $controllerName($controllerShortName);
    }

    /**
     * Run application as web site 
     */
    public function run() {
        try {
            $this->router->run();
            if (Session::isExistInTemp('isRedirect')) {
                Session::cleanTempStorage();
            }
        } catch (Exception $ex) {
            $view = new View();
            $view->setControllerName('system');
            $view->render('exception', array('ex' => $ex));
        }
    }

}

