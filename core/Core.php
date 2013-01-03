<?php

if (!defined('INSIDE_ACCESS')) {
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
define('DOMAIN_URI', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('BASE_URI', DOMAIN_URI . (SUB_URI != '' ? SUB_URI . '/' : ''));
define('CONTROLLERS_DIR', APP_PATH . DS . 'controllers');
define('MODELS_DIR', APP_PATH . DS . 'models');
define('VIEWS_DIR', APP_PATH . DS . 'views');
define('LANG_DIR', APP_PATH . DS . 'languages');
define('LIBS_DIR', ROOT_PATH . DS . 'libs');
define('SYSTEM_CONFIG_PATH', ROOT_PATH . DS . 'configs' . DS . 'system.php');

require_once ROOT_PATH . DS . 'core' . DS . 'CConfig.php'; // Include system config
require_once ROOT_PATH . DS . 'core' . DS . 'Registry.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Exceptions.php';
require_once APP_PATH . DS . 'classes' . DS . 'Exceptions.php';
require_once ROOT_PATH . DS . 'core' . DS . 'CFactory.php';
require_once APP_PATH . DS . 'classes' . DS . 'Factory.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Router.php'; // Include router
require_once ROOT_PATH . DS . 'core' . DS . 'Json.php';
require_once ROOT_PATH . DS . 'core' . DS . 'Cookies.php';
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

    /**
     * Internal control flag
     * 
     * @var bool 
     */
    private static $isInternalControl = false;

    /**
     * Router object
     * @var Router
     */
    private $router;

    /**
     * Static method for instantiating a singleton core object.
     *
     * @return Core
     */
    final public static function instance() {
        if (!isset(self::$instance)) {
            self::$instance = new Core();
            self::$instance->prepareApp();
        }
        return self::$instance;
    }

    /**
     * Internal control can be from other application without HTTP protocol.
     * Before create instance application you shold set this flag.
     * If this flag was set in true, check of session and access rights are not exist.
     * 
     * @param bool $bFlag
     */
    public static function isInternalControl($isInternalControl = true) {
        self::$isInternalControl = $isInternalControl;
    }

    /**
     * Execute plugins by event name
     * @param string $eventName name of event
     */
    public static function executePlugin($eventName) {
        // Search plugin files in plugin folder by event name
        $pathToPlugins = APP_PATH . DS . 'plugins' . DS;
        $pluginPrefix = 'Plugin';
        $pluginClass = $eventName . $pluginPrefix;
        $pluginPath = $pathToPlugins . $pluginClass . '.php';
        // If plugin class not loaded
        if (!class_exists($pluginClass)) {
            // Check existing of plugin for event
            if (file_exists($pluginPath)) {
                include $pluginPath;
            } else {
                return;
            }
        }
        // Create plugin object
        $plugin = new $pluginClass();
        // Gets all methods of plugin
        $methods = get_class_methods($plugin);
        // Execute only with prefix 'execute'
        foreach ($methods as $method) {
            if (strpos($method, 'execute') !== false) {
                if (is_callable(array($plugin, $method))) {
                    call_user_func_array(array($plugin, $method), array());
                }
            }
        }
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
            // Bind standart error output and exceptions
            PhpException::setErrorsHandlers();
            // Run application as HTTP response
            $this->router->run();
            // Checks temporary storage 
            if (Session::isExistInTemp('isRedirect')) {
                // for cleaning data which transferred between page loads
                Session::cleanTempStorage();
            }
        } catch (PhpException $ex) {
            // Catch php errors exceptions and view it
            View::showExeption($ex);
        } catch (AppException $ex) {
            // Catch application exceptions and view it
            View::showExeption($ex);
            // Write info about exception in log file
            CFactory::getLogger()->error($ex->getLogMessage());
        }
    }

    /**
     * Initialisation of application
     * @return /Core
     */
    protected function prepareApp() {
        // Init session
        Session::startSessions();
        // Setup configuation of database connection
        $this->prepareDBConnection();
        // If it is HTTP request
        if (!self::$isInternalControl) {
            // Create route
            $this->router = new Router();
            // Execute plugin for after core prepare event
            self::executePlugin(Plugin::AFTER_CORE_PREPARE);
        }
        return $this;
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
            'development' => CFactory::getConfig()->getConnectionString()));
    }

}

