<?php

   /**
    * Clansuite - just an eSports CMS
    * Jens-Andrï¿½ Koch ï¿½ 2005 - onwards
    * http://www.clansuite.com/
    *
    *        _\|/_
    *        (o o)
    +-----oOO-{_}-OOo------------------------------------------------------------------+
    |                                                                                  |
    | LICENSE:                                                                         |
    |                                                                                  |
    |    This program is free software; you can redistribute it and/or modify          |
    |    it under the terms of the GNU General Public License as published by          |
    |    the Free Software Foundation; either version 2 of the License, or             |
    |    (at your option) any later version.                                           |
    |                                                                                  |
    |    This program is distributed in the hope that it will be useful,               |
    |    but WITHOUT ANY WARRANTY; without even the implied warranty of                |
    |    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                 |
    |    GNU General Public License for more details.                                  |
    |                                                                                  |
    |    You should have received a copy of the GNU General Public License             |
    |    along with this program; if not, write to the Free Software                   |
    |    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA    |
    |                                                                                  |
    +----------------------------------------------------------------------------------+
    */

   /** =====================================================================
    *    WARNING: DO NOT MODIFY FILES, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *             READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

namespace Clansuite;

class Application
{
    /**
     * @var object Dependency Injector Phemto
     */
    private static $injector;

    /**
     * @var object Clansuite_Config
     */
    private static $config;

    /**
     * @var object \Doctrine\ORM\EntityManager
     */
    public static $doctrine_em;

    /**
     * @var array Static Array with all prefilter classnames
     */
    private static $prefilter_classes;

    /**
     * @var array Static Array with all postfilter classnames
     */
    private static $postfilter_classes;

    public static function run()
    {
        /**
         * @var STARTTIME shows Application Starttime
         */
        define('STARTTIME', microtime(1));

        self::perform_startup_checks();
        self::initialize_ConstantsAndPaths();
        self::initialize_Version();
        self::initialize_Loader();
        self::initialize_DependencyInjection();
        self::initialize_Config();
        #self::initialize_Logger();
        self::initialize_UTF8();
        self::initialize_Debug();
        self::initialize_Timezone();
        self::initialize_Eventdispatcher();
        self::initialize_Errorhandling();
        self::register_DI_Core();
        self::register_DI_Filters();
        self::initialize_Database();
        self::start_Session();
        self::executeFrontController();
        self::shutdown();
    }

    /**
     *  ================================================
     *     Startup Checks
     *  ================================================
     */
    private static function perform_startup_checks()
    {
        /**
         * Check if install.php is still available..
         * This means Clansuite is installed, but without any security steps performed.
         */
        if (defined('CS_LIVE') and CS_LIVE == true and is_file('Installation/install.php') === true) {
            header('Location: Installation/check_security.php');
        }

        /**
         * PHP Version Check
         */
        $REQUIRED_PHP_VERSION = '5.3.2';
        if (version_compare(PHP_VERSION, $REQUIRED_PHP_VERSION, '<=') === true) {
            $msg =  _('Your PHP Version is <b><font color="#FF0000">$s</font></b>');
            $msg .= _('Clansuite requires PHP Version <b><font color="#4CC417">%s</font></b> or newer.');
            throw new \RuntimeException(sprintf($msg, PHP_VERSION, $REQUIRED_PHP_VERSION));
        }
        unset($REQUIRED_PHP_VERSION);

        /**
         * Check if clansuite config file is found, else we are
         * not installed at all and redirect to installation page.
         */
        if (is_file(__DIR__.'/Configuration/clansuite.php') === false) {
            header('Location: Installation/index.php');
        }
    }

    /**
     * Sets the PHP memory limit
     *
     * @param string $memory_limit The memory limit in megabytes, e.g. '32' or '128'.
     */
    private static function setMemoryLimit($limit)
    {
        // in general the memory limit is determined by php.ini, it's only raised if lower 32MB and not -1
        $memory_limit = intval(ini_get('memory_limit'));
        if ($memory_limit != -1 and $memory_limit < (int) $limit ) {
            ini_set('memory_limit', $limit + 'M');
        }
        unset($memory_limit);
    }

    /**
     *  ================================================
     *     Define Constants
     *  ================================================
     *
     *   1. Define Shorthands and Syntax Declarations
     *      - NL, TAB, LF,  CR, CRT, CRLF
     *   2. Path Assignments
     *      - ROOT & ROOT_*
     *      - WWW_ROOT & WWW_ROOT_*
     *   3. Setup include_path for 3th party libraries
     *  ------------------------------------------------
     */
    public static function define_ConstantsAndPaths()
    {
        /**
         * 1) Shorthands and Syntax Declarations
         */

        define('NL', '<br />'. PHP_EOL);  // NL is a shorthand for a HTML NEWLINE (HTML Break + Carriage Return)
        define('TAB', chr(9));            // Tabulator (\t) (horizontal tab)
        define('LF', chr(10));            // Line Feed
        define('CR', chr(13));            // Carriage Return (\n)
        define('CRT', CR . TAB);          // Carriage Return and Tabulator (\n\t)
        define('CRLF', CR . LF);          // Carriage Return and Line Feed Combo (\r\n)

        /**
         * 2) Path Assignments
         *    ROOT and directories related to ROOT as absolute path shortcuts.
         */

        /**
         * ROOT is the APPLICATION PATH
         * @var Purpose of ROOT is to provide the absolute path to the current working dir of clansuite
         */
        define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

        /**
         * @var Root path of the cache directory (with trailing slash)
         */
        define('ROOT_CACHE', ROOT . 'Cache/');

        /**
         * @var Root path of the config directory (with trailing slash)
         */
        define('ROOT_CONFIG', ROOT . 'Configuration/');

        /**
         * @var Root path of the framework directory (with trailing slash)
         */
        define('KOCH_FRAMEWORK', dirname(ROOT) . '/framework/Koch/');

        /**
         * @var Root path of the languages directory (with trailing slash)
         */
        define('ROOT_LANGUAGES', ROOT . 'languages/');

        /**
         * @var Root path of the libraries directory (with trailing slash)
         */
        define('ROOT_LIBRARIES', dirname(ROOT) .'/libraries/');

        /**
         * @var Root path of the vendors directory (with trailing slash)
         */
        define('ROOT_VENDOR', dirname(ROOT) .'/vendor/');

        /**
         * @var Root path of the logs directory (with trailing slash)
         */
        define('ROOT_LOGS', ROOT . 'Logs/');

        /**
         * @var ROOT_MOD Root path of the modules directory (with trailing slash)
         */
        define('ROOT_MOD', ROOT . 'Modules/');

        /**
         * @var Root path of the themes directory (with trailing slash)
         */
        define('ROOT_THEMES', ROOT . 'themes/');
        define('ROOT_THEMES_BACKEND', ROOT_THEMES . 'backend/');
        define('ROOT_THEMES_FRONTEND', ROOT_THEMES . 'frontend/');
        define('ROOT_THEMES_CORE', ROOT_THEMES . 'core/');

        /**
         * @var Root path of the upload directory (with trailing slash)
         */
        define('ROOT_UPLOAD', ROOT . 'Uploads/');

        /**
         * @var Determine Type of Protocol for Webpaths (http/https)
         */
        if (isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on') {
            define('PROTOCOL', 'https://');
        } else {
            define('PROTOCOL', 'http://');
        }

        /**
         * @var SERVER_URL
         */
        define('SERVER_URL', PROTOCOL . $_SERVER['SERVER_NAME']);

        /**
         * @var WWW_ROOT is a complete www-path with servername from SERVER_URL, depending on os-system
         */
        if (dirname($_SERVER['PHP_SELF']) === '\\') {
            define('WWW_ROOT', SERVER_URL . '/Clansuite/');
        } else {
            define('WWW_ROOT', SERVER_URL . dirname($_SERVER['PHP_SELF']) . '/Clansuite/');
        }

        /**
         * @var WWW_ROOT_THEMES defines the themes folder
         */
        define('WWW_ROOT_THEMES', WWW_ROOT . 'themes/');
        define('WWW_ROOT_THEMES_BACKEND', WWW_ROOT_THEMES . 'backend/');
        define('WWW_ROOT_THEMES_FRONTEND', WWW_ROOT_THEMES . 'frontend/');
        define('WWW_ROOT_THEMES_CORE', WWW_ROOT_THEMES . 'core/');
    }

    /**
     * Load Constants from APC
     */
    public static function initialize_ConstantsAndPaths()
    {
        // ensure that apc is loaded as extension
        define('APC', (bool) extension_loaded('apc'));

        // try to load constants from APC
        if (APC === true) {
            // constants retrieved from APC
            apc_load_constants('CLANSUITE_CONSTANTS', true);
        }

        // if apc is off or
        // if apc is on, but apc_load_constants did not retrieve any constants yet (first run)
        // then define constants
        if (APC === false or defined('NL') == false) {
            self::define_ConstantsAndPaths();

            /**
             * Store Constants to APC (on first run)
             */
            if (APC === true) {
                // catch user-defined constants as array
                $constantsarray = get_defined_constants(true);

                // remove unwanted constants before inserting into cache
                unset($constantsarray['user']['STARTTIME']);
                unset($constantsarray['user']['APC']);

                apc_define_constants('CLANSUITE_CONSTANTS', $constantsarray['user'], false);

                unset($constantsarray);
            }
        }

        /**
         * SET INCLUDE PATHS
         *
         * We set INCLUDE PATHS for PEAR and other 3th party Libraries by defining an paths array first.
         * We are not setting the clansuite core path here, because files located there are handled via autoloading.
         *
         * The $paths array is set to the php environment with set_include_path().
         * Note, that for set_include_path() to work properly the path order is important!
         * <first path to look>:<second path>:<etc>:
         *
         * If you need to add something: use or absolute path constants (ROOT*) or realpath($your_path).
         */
        $paths = array(
            dirname(KOCH_FRAMEWORK),
            dirname(ROOT),
            ROOT_VENDOR, // composer dir
            ROOT_LIBRARIES,
            ROOT_LIBRARIES . 'PEAR/'
        );

        // attach original include paths
        set_include_path(implode($paths, PATH_SEPARATOR) . PATH_SEPARATOR . get_include_path());
        //var_dump(get_include_path());

        unset($paths);
    }

    /**
     *  ================================================
     *     Clansuite Version Information
     *  ================================================
     */
    private static function initialize_Version()
    {
        include __DIR__ . '/Version.php';

        Version::setVersionInformation();
        Version::setVersionInformationToCaches();
    }

    /**
     *  ================================================
     *     Initialize UTF8 via mbstring or fallback
     *  ================================================
     */
    private static function initialize_UTF8()
    {
        \Koch\Localization\Utf8::initialize();
    }

    /**
     *  ================================================
     *     Debug Mode & Error Reporting Level
     *  ================================================
     *
     * Some words about the PHP Error Reporting Level.
     * The Error Reporting depends on the Debug Mode Setting.
     * When the Debug Mode is enabled Clansuite runs with error reporting set to E_ALL | E_STRICT.
     * When the Debug is disabled Clansuite will not report any errors (0).
     *
     * For security reasons you are advised to change the Debug Mode Setting to disabled when your site goes live.
     * For more info visit:  http://www.php.net/error_reporting
     * @note: in php6 e_strict will be moved into e_all
     */
    private static function initialize_Debug()
    {
        /**
         * @var Debug-Mode Constant is set via config setting ['error']['debug']
         */
        define('DEBUG', (bool) self::$config['error']['debug']);

        // If Debug is enabled, set FULL error_reporting, else DISABLE it completely
        if (DEBUG == true) {
            ini_set('max_execution_time', '300');
            ini_set('display_startup_errors', true);
            ini_set('display_errors', true);    // display errors in the browser

            error_reporting(E_ALL | E_STRICT);  // all errors and strict standard optimizations

            /**
             * Toggle for Rapid Application Development
             * @var Development-Mode is set via config setting ['error']['development']
             */
            define('DEVELOPMENT', (bool) self::$config['error']['development']);

            /**
             * Setup Debugging Helpers
             *
             * If Clansuite is in DEBUG Mode an additional class is loaded, providing some
             * helper methods for profiling, tracing and enhancing the debug displays.
             * @see clansuite_debug::printR() and clansuite_debug::firebug()
             */
            include KOCH_FRAMEWORK . 'Debug/Debug.php';

            /**
             * @var XDebug and set it's value via the config setting ['error']['xdebug']
             */
            define('XDEBUG', (bool) self::$config['error']['xdebug']);

            // If XDebug is enabled, load xdebug helpers and start the debug/tracing
            if (XDEBUG == true) {
                include KOCH_FRAMEWORK . 'Debug/Xdebug.php';
                \Koch\Debug\XDebug::start_xdebug();
            }
        } else { // application is in live/production mode. errors are not shown, but logged to file!
            // enable error_logging
            ini_set('log_errors', true);
            // do not display errors in the browser
            ini_set('display_errors', false);
            // log only certain errors
            error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
            // write to errorlog
            ini_set('error_log', ROOT_LOGS . 'clansuite_error.log');
        }
    }

    /**
     * Initialize Autoloader(s)
     *
     * Registers our own autoloader on the first position of the spl autoloading stack
     * and throws an exception if autoload fails on class/interface loading.
     */
    public static function initialize_Loader()
    {
        include KOCH_FRAMEWORK . 'Autoload/Loader.php';

        new \Koch\Autoload\Loader();

        /**
         * Composer Autoloading
         *
         * The Autoloading of dependencies from the "vendor" folder is managed by Composer.
         * The "vendors" are described in the "/composer.json" configugration file.
         * When these dependencies are installed with Composer, Composer will create a
         * "/vendor/autoload.php" file.
         *
         * After making sure that "vendors" were installed, the file is included.
         * That initializes the autoloading of Composer managed "vendors".
         */
        /*if (!is_file(ROOT_VENDOR . 'autoload.php')) {
            $msg = _("Vendor dependencies are missing.\nPlease use Composer and 'install' the dependencies:\n");
            $msg .= "\$>cd /path/to/clansuite/bin\n\$>composer-install.bat";
            throw new \Koch\Exception\Exception($msg);
        }

        include ROOT_VENDOR . 'autoload.php';*/
    }

    /**
     * Initialize Logger
     */
    private static function initialize_Logger()
    {
        $logger = new \Koch\Logger\Logger;
        $firebug = $logger->loadLogger('firebug');
        $logger->addLogger($firebug);
    }

    /**
     * Initialize Eventdispatcher
     */
    private static function initialize_Eventdispatcher()
    {
        \Koch\Event\Dispatcher::instantiate();
        \Koch\Event\Loader::loadEvents();
    }

    /**
     * Initialize the custom Exception- and Errorhandlers
     */
    private static function initialize_Errorhandling()
    {
        set_exception_handler(array(new \Koch\Exception\Exception,'exception_handler'));
        set_error_handler('\Koch\Exception\Errorhandler::errorhandler');
        register_shutdown_function('\Koch\Exception\Errorhandler::catchFatalErrorsShutdownHandler');
    }

    /**
     *  ============================================
     *   Initializes the Dependency Injector Phemto
     *  ============================================
     */
    private static function initialize_DependencyInjection()
    {
        self::$injector = new \Koch\DI\DependencyInjector();
    }

    /**
     *  ==========================================
     *          Load Configuration
     *  ==========================================
     *
     * 1. Load clansuite.config.php
     * 2. Load specific staging configuration (overloading clansuite.config.php)
     * 3. Maintenance check
     * 4. Alter php.ini settings
     */
    private static function initialize_Config()
    {
        // 1. load the main clansuite configuration file
        $clansuite_cfg_cached = false;

        if (APC === true and apc_exists('clansuite.config')) {
            self::$config = apc_fetch('clansuite.config');
            $clansuite_cfg_cached = true;
        }

        if ($clansuite_cfg_cached === false) {
            self::$config = \Koch\Config\Adapter\INI::readConfig(ROOT . 'Configuration/clansuite.php');
            if (APC === true) {
                apc_add('clansuite.config', self::$config);
            }
        }
        unset($clansuite_cfg_cached);

        // 2. Maintenance check
        if( isset(self::$config['maintenance']['maintenance']) and
            true === (bool) self::$config['maintenance']['maintenance'] )
        {
            $token = false;

            // incoming maintenance token via GET
            if ($_GET['mnt'] !== null) {
                $tokenstring = $_GET['mnt'];
                $token = Clansuite_Securitytoken::ckeckToken($tokenstring);
            }

            // if token is false (or not valid) show maintenance
            if (false === $token) {
                Clansuite_Maintenance::show(self::$config);
            } else {
                self::$config['maintenance']['maintenance'] = 0;
                \Koch\Config\Ini::writeConfig(ROOT . 'Configuration/clansuite.php', self::$config);
                // redirect to remove the token from url
                header('Location: ' . SERVER_URL);
            }
        }

        // 3. load staging configuration (overloading clansuite.php)
        if ( true === (bool) self::$config['config']['staging'] ) {
            self::$config = \Koch\Config\Staging::overloadWithStagingConfig(self::$config);
        }

        /**
         * Deny service, if the system load is too high.
         */
        if (defined('DEBUG') and DEBUG == false) {
            $max_load = isset(self::$config['load']['max']) ? (float) self::$config['load']['max'] : 80;

            if (\Koch\Functions::get_server_load() > $max_load) {
                $retry = (int) mt_rand(45, 90);
                header ('Retry-After: '.$retry);
                header('HTTP/1.1 503 Too busy, try again later');
                die('HTTP/1.1 503 Server too busy. Please try again later.');
            }
        }
        /**
         *  ================================================
         *          4. Alter php.ini settings
         *  ================================================
         */
        set_time_limit(0);
        ini_set('short_open_tag', 'off');
        ini_set('arg_separator.input', '&amp;');
        ini_set('arg_separator.output', '&amp;');
        ini_set('default_charset', 'utf-8');
        self::setMemoryLimit('32');
        if (false === gc_enabled()) {
            gc_enable();
        }
    }

    /**
     * Register the Clansuite Core Classes at the Dependency Injector
     */
    private static function register_DI_Core()
    {
        // define the core classes to load
        static $core_classes = array(
            'Koch\Config\Config',
            #'Koch\Http\HttpRequest',
            #'Koch\Http\HttpResponse',
            #'Koch\Filter\FilterManager',
            'Koch\Localization\Localization',
            'Koch\Security',
            'Koch\Validation\Inputfilter',
            'Koch\User',
            'Koch\Session\Session',
            'Koch\Router\Router',
        );

        // register them to the DI as singletons
        foreach ($core_classes as $class) {
            self::$injector->register($class);
        }
    }

    /**
     * Register the Pre- and Postfilters Classes at the Dependency Injector
     */
    private static function register_DI_Filters()
    {
        // define prefilters to load
        self::$prefilter_classes = array(
            'Koch\Filter\Filters\GetUser',
            #'Koch\Filter\Filters\SessionSecurity',
            'Koch\Filter\Filters\LanguageViaGet',
            'Koch\Filter\Filters\ThemeViaGet',
            'Koch\Filter\Filters\SetModuleLanguage',
            'Koch\Filter\Filters\StartupChecks',
            #'Koch\Filter\Filters\Statistics'
        );

        // define postfilters to load
        self::$postfilter_classes = array(
            #'Koch\Filter\HtmlTidy',
            'Koch\Filter\Filters\SmartyMoves'
        );

        // register the debug console only in DEBUG mode and before all other filters
        if (DEBUG == true) {
            self::$injector->register('Koch\Filter\Filters\PhpDebugConsole');
        }

        // combine pre- and postfilters
        $filter_classes = self::$prefilter_classes + self::$postfilter_classes;

        // register all filters at the dependency injector
        foreach ($filter_classes as $class) {
            self::$injector->register($class);
        }
    }

    /**
     *  ===================================================================
     *     Request & Response + Frontcontroller + Filters + processRequest
     *  ===================================================================
     */
    private static function executeFrontController()
    {
        // Get request and response objects for Filter and Request processing
        $request  = self::$injector->instantiate('Koch\Http\HttpRequest');
        $response = self::$injector->instantiate('Koch\Http\HttpResponse');

        /**
         * Setup Frontcontroller and pass Request and Response
         */
        $clansuite = new \Koch\Mvc\FrontController($request, $response);

        /**
         * Add the Prefilters and Postfilters to the Frontcontroller
         *
         * Prefilters are executed before the requested Module Action is executed.
         * Examples: caching checks, theme selection.
         */
        foreach (self::$prefilter_classes as $class) {
            $clansuite->addPrefilter(self::$injector->instantiate($class));
        }

        /**
         * Add the Postfilters to the Frontcontroller
         *
         * Postfilters are executed after the action, but before view rendering.
         * Examples: output compression, character set modifications, html tidy, breadcrumbs.
         */
        foreach (self::$postfilter_classes as $class) {
            $clansuite->addPostfilter(self::$injector->instantiate($class));
        }

        // Take off.
        $clansuite->processRequest();
    }

    /**
     * Checks initializes Doctrine 2
     *
     * Note: Doctrine must be initialize before "session start",
     * because the session depends on writting to the database.
     */
    private static function initialize_Database()
    {
        self::$doctrine_em = \Koch\Doctrine\Doctrine::init(self::$config);
    }

    /**
     * Starts a new Session and User
     */
    private static function start_Session()
    {
        // Initialize Session
        self::$injector->create('\Koch\Session\Session');

        // register the session-depending user object manually
        self::$injector->instantiate('\Koch\User\User');
    }

    /**
     *  ================================================
     *     Set Timezone Settings
     *  ================================================
     * Set date_default_timezone_set() in php.ini !
     *
     * For a lot more timezones look in the Appendix H of the PHP Manual
     * @link http://php.net/manual/en/timezones.php
     * @todo make $timezone configurable by user (small dropdown) or autodetected from user
     */
    private static function initialize_Timezone()
    {
        // apply timezone defensivly
        if (isset(self::$config['locale']['timezone']) === true) {
            // set always if incomming via config
            date_default_timezone_set(self::$config['locale']['timezone']);
        }

        // set date formating via config
        if (isset(self::$config['locale']['dateformat']) === true) {
            define('DATE_FORMAT', self::$config['locale']['dateformat']);
        } else { // set default value
            define('DATE_FORMAT', 'd.m.Y H:i');
        }
    }

    /**
     * @return Returns the Dependency Injector
     */
    public static function getInjector()
    {
        return self::$injector;
    }

    /**
     * @return Returns the Clansuite Main Config
     */
    public static function getClansuiteConfig()
    {
        return self::$config;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEntityManager()
    {
        return self::$doctrine_em;
    }

    /**
     * triggerEvent
     * Is an convenience/proxy method for easier registration of Events.
     * The method parameters are passed to / forwarded to
     * Clansuite_Eventdispatcher::instantiate()->triggerEvent().
     *
     * @param string|object $event   Name of Event or Event object to trigger.
     * @param string        $context The context of the event triggering, the object from where we are calling. Defaults to null.
     * @param string        $info    Some pieces of information. Defaults to null.
     */
    public static function triggerEvent($event, $context = null, $info = null)
    {
        if (class_exists('Koch\Event\Dispatcher', false) === true) {
            \Koch\Event\Dispatcher::instantiate()->triggerEvent($event, $context, $info);
        }
    }

    /**
     * ==================================================
     *    Perform a proper Shutdown of the Application
     * ==================================================
     */
    public static function shutdown()
    {
        self::triggerEvent('onApplicationShutdown');

        if (DEBUG == true) {
            echo \Koch\Doctrine\Doctrine::getStats();

            // Display the General Application Runtime
            echo ' Application Runtime: '.round(microtime(1) - constant('STARTTIME'), 3).' Seconds';
        }
    }
}
