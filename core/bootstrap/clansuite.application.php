<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

   /** =====================================================================
    *    WARNING: DO NOT MODIFY FILES, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *             READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

class Clansuite_CMS
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
        define('STARTTIME', microtime(1), false);

        self::initialize_Loader();
        self::initialize_Config();
        self::initialize_Paths();
        self::perform_startup_checks();
        self::initialize_Debug();
        self::initialize_Version();
        self::initialize_Timezone();
        self::initialize_Eventdispatcher();
        self::initialize_Errorhandling();
        self::initialize_DependecyInjection();
        self::register_DI_Core();
        self::register_DI_Filters();
        self::start_Session();
        self::execute_Frontcontroller();
        self::shutdown();
    }

    /**
     *  ================================================
     *     Startup Checks
     *  ================================================
     */
    private static function perform_startup_checks()
    {
        # Check if install.php is still available, so we are installed but without security steps performed
        #if ( is_file( 'installation/install.php') === true ) { header( 'Location: installation/check_security.php'); exit; }

        # PHP Version Check
        $REQUIRED_PHP_VERSION = '5.2.3';
        if(version_compare(PHP_VERSION, $REQUIRED_PHP_VERSION, '<') === true)
        {
            die('Your PHP Version is <b><font color="#FF0000">' . PHP_VERSION . '</font></b>!
                 Clansuite requires PHP Version <b><font color="#4CC417">' . $REQUIRED_PHP_VERSION . '</font></b>!');
        }
        unset($REQUIRED_PHP_VERSION);

        # PDO Check
        if(class_exists('pdo', false) === false)
        {
            die('<i>php_pdo</i> not enabled!');
        }

        # PDO mysql driver Check
        # @todo the type of db-driver for pdo is set on installtion + available via config
        if(in_array('mysql', PDO::getAvailableDrivers()) === false)
        {
            die('<i>php_pdo_mysql</i> driver not enabled.');
        }

        # Data Source Name Check
        # check if database settings are available in configuration
        if(empty(self::$config['database']['type']) or
           empty(self::$config['database']['username']) or
           empty(self::$config['database']['host']) or
           empty(self::$config['database']['name'])
           )
        {
            die('<b><font color="#FF0000">[Clansuite Error] Database Connection Settings missing!</font></b> <br />
                 Please use <a href="/installation/index.php">Clansuite Installation</a> to perform a proper installation.');
        }
    }

    /**
     *  ==========================================
     *          Load Configuration
     *  ==========================================
     *
     * 1. Check if clansuite.config.php is found, else redirect
     * 2. Load clansuite.config.php
     * 3. Alter php.ini settings
     */
    private static function initialize_Config()
    {
        # 1. Check if clansuite.config.php is found, else we are not installed at all, so redirect to installation page
        if(is_file('configuration/clansuite.config.php') === false)
        {
            header('Location: installation/index.php');
            exit;
        }

        # in order to read the main config, the configuration handler for ini files is needed
        include getcwd() . '/core/config/ini.config.php';

        # 2. load the main clansuite configuration file
        self::$config = Clansuite_Config_IniHandler::readConfig('configuration/clansuite.config.php');

        /**
         *  ================================================
         *          3. Alter php.ini settings
         *  ================================================
         */
        ini_set('short_open_tag', 'off');
        ini_set('arg_separator.input', '&amp;');
        ini_set('arg_separator.output', '&amp;');

        if(extension_loaded('mbstring') == true)
        {
            if(ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING)
            {
                trigger_error('The string functions are overloaded by mbstring. Please stop that.
                               Check php.ini - setting: mbstring.func_overload.', E_USER_ERROR);
            }

            mb_internal_encoding('UTF-8');
        }

        # in general the memory limit is determined by php.ini, it's only raised if lower 16MB
        if(intval(ini_get('memory_limit')) < 16)
        {
            ini_set('memory_limit', '16M');
        }
    }

    /**
     *  ================================================
     *     Define Constants
     *  ================================================
     *
     *   1. Define Shorthands and Syntax Declarations
     *   - DS, PS, NL, CR
     *   2. Path Assignments
     *   - ROOT_*
     *   - WWW_ROOT & WWW_ROOT_*
     *   3. Setup include path for 3th party libraries
     *  ------------------------------------------------
     */
    private static function initialize_Paths()
    {
        /**
         * 1) Shorthands and Syntax Declarations
         */

        /**
         * @var DS is a shorthand for DIRECTORY_SEPARATOR
         */
        define('DS', DIRECTORY_SEPARATOR, false);

        /**
         * @var PS is a shorthand for PATH_SEPARATOR
         */
        define('PS', PATH_SEPARATOR, false);

        /**
         * @var HTML Break + Carriage Return "<br />\r\n"
         */
        define('NL', "<br />\r\n", false);

        /**
         * @var Carriage Return "\n"
         */
        define('CR', "\n", false);

        /**
         * @var Carriage Return and Tabulator "\n\t"
         */
        define('CRT', "\n\t", false);

        /**
         * 2) Path Assignments
         *    ROOT and directories related to ROOT as absolute path shortcuts.
         */

        /**
         * ROOT is the APPLICATION PATH
         * @var Purpose of ROOT is to provide the absolute path to the current working dir of clansuite
         */
        define('ROOT', getcwd() . DS, false);
        #define('ROOT',  realpath('../'));

        /**
         * @var ROOT_MOD Root path of the modules directory (with trailing slash)
         */
        define('ROOT_MOD', ROOT . self::$config['paths']['mod_folder'] . DS, false);

        /**
         * @var Root path of the themes directory (with trailing slash)
         */
        define('ROOT_THEMES', ROOT . self::$config['paths']['themes_folder'] . DS, false);

        /**
         * @var Root path of the languages directory (with trailing slash)
         */
        define('ROOT_LANGUAGES', ROOT . self::$config['paths']['language_folder'] . DS, false);

        /**
         * @var Root path of the core directory (with trailing slash)
         */
        define('ROOT_CORE', ROOT . self::$config['paths']['core_folder'] . DS, false);

        /**
         * @var Root path of the libraries directory (with trailing slash)
         */
        define('ROOT_LIBRARIES', ROOT . self::$config['paths']['libraries_folder'] . DS, false);

        /**
         * @var Root path of the upload directory (with trailing slash)
         */
        define('ROOT_UPLOAD', ROOT . self::$config['paths']['upload_folder'] . DS, false);

        /**
         * @var Root path of the logs directory (with trailing slash)
         */
        define('ROOT_LOGS', ROOT . self::$config['paths']['logfiles_folder'] . DS, false);

        /**
         * @var Root path of the cache directory (with trailing slash)
         */
        define('ROOT_CACHE', ROOT . 'cache' . DS, false);

        /**
         * @var Root path of the config directory (with trailing slash)
         */
        define('ROOT_CONFIG', ROOT . 'configuration' . DS, false);

        /**
         * @var Determine Type of Protocol for Webpaths (http/https)
         */
        if(isset($_SERVER['HTTPS']) and mb_strtolower($_SERVER['HTTPS']) == 'on')
        {
            define('PROTOCOL', 'https://', false);
        }
        else
        {

            define('PROTOCOL', 'http://', false);
        }

        /**
         * @var SERVER_URL
         */
        define('SERVER_URL', PROTOCOL . $_SERVER['SERVER_NAME'], false);

        /**
         * @var WWW_ROOT is a complete www-path with servername from SERVER_URL, depending on os-system
         */
        if(dirname($_SERVER['PHP_SELF']) == '\\')
        {
            define('WWW_ROOT', SERVER_URL . DS , false);
        }
        else
        {
            define('WWW_ROOT', SERVER_URL . dirname($_SERVER['PHP_SELF']) . DS, false);
        }

        /**
         * @var WWW_ROOT_THEMES defines the themes folder
         */
        define('WWW_ROOT_THEMES', WWW_ROOT . self::$config['paths']['themes_folder'] . DS, false);

        /**
         * @var WWW_ROOT_THEMES defines the themes/core folder
         */
        define('WWW_ROOT_THEMES_CORE', WWW_ROOT_THEMES . 'core' . DS, false);

        /**
         * SET INCLUDE PATHS
         *
         * We set INCLUDE PATHS for PEAR and other 3th party Libraries by defining an paths array first.
         * We are not setting the clansuite core path here, because files located there are handled via autoloading.
         * The $paths array is set to the php environment with set_include_path.
         * Note, that for set_include_path the path order is important   <first path to look>:<second path>:<etc>:
         */
        $paths = array(
            ROOT,
            ROOT_LIBRARIES,
            ROOT_LIBRARIES . 'PEAR' . DS
        );

        # attach original include paths
        set_include_path(implode($paths, PS) . PS . get_include_path());
        unset($paths);
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
        define('DEBUG', (bool) self::$config['error']['debug'], false);

        # If Debug is enabled, set FULL error_reporting, else DISABLE it completely
        if(DEBUG == true)
        {
            ini_set('display_startup_errors', true);
            ini_set('display_errors', true);    # display errors in the browser
            error_reporting(E_ALL | E_STRICT);  # all errors and strict standard optimizations

            /**
             * Toggle for Rapid Application Development
             * @var Development-Mode is set via config setting ['error']['development']
             */
            define('DEVELOPMENT', (bool) self::$config['error']['development'], false);

            /**
             * Setup Debugging Helpers
             *
             * If Clansuite is in DEBUG Mode an additional class is loaded, providing some
             * helper methods for profiling, tracing and enhancing the debug displays.
             * @see clansuite_debug::printR() and clansuite_debug::firebug()
             */
            include ROOT_CORE . 'debug/debug.core.php';

            /**
             * @var XDebug and set it's value via the config setting ['error']['xdebug']
             */
            define('XDEBUG', (bool) self::$config['error']['xdebug'], false);

            # If XDebug is enabled, load xdebug helpers and start the debug/tracing
            if(XDEBUG == true)
            {
                include ROOT_CORE . 'debug/xdebug.core.php';
                Clansuite_XDebug::start_xdebug();
            }
        }
        else # application is in live/production mode. errors are not shown, but logged to file!
        {
            # enable error_logging
            ini_set('log_errors', true);
            # do not display errors in the browser
            ini_set('display_errors', false);
            # log only certain errors
            error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
            # do not report any errors
            #error_reporting(0);
            # write to errorlog
            ini_set('error_log', ROOT_LOGS . 'clansuite_errorlog.txt');
            # @todo use logger instead of error_log()
        }
    }

    /**
     * Initialize Autoloader
     */
    private static function initialize_Loader()
    {
        include getcwd() . '/core/bootstrap/clansuite.loader.php';
        # instantiate the autoloading handlers by overwriting the spl_autoload handling
        Clansuite_Loader::register_autoloaders();
    }

    /**
     * Initialize Eventdispatcher
     */
    private static function initialize_Eventdispatcher()
    {
        if( isset(self::$config['eventsystem']['enabled'])
              and self::$config['eventsystem']['enabled'] === true)
        {
            include ROOT_CORE . 'eventhandler.core.php';
            Clansuite_Eventdispatcher::instantiate();
            Clansuite_Eventloader::autoloadEvents();
        }
    }

    /**
     * Initialize the custom Exception- and Errorhandlers
     */
    private static function initialize_Errorhandling()
    {
        set_exception_handler(array(new Clansuite_Exception, 'clansuite_exception_handler'));
        set_error_handler(array(new Clansuite_Errorhandler, 'clansuite_error_handler'));
    }

    /**
     *  ============================================
     *   Initializes the Dependency Injector Phemto
     *  ============================================
     */
    private static function initialize_DependecyInjection()
    {
        include ROOT_LIBRARIES . 'phemto/phemto.php';
        self::$injector = new Phemto();
    }

    /**
     * Register the Clansuite Core Classes at the Dependency Injector
     */
    private static function register_DI_Core()
    {
        # define the core classes to load
        $core_classes = array(
                              'Clansuite_Config',
                              'Clansuite_HttpRequest',
                              'Clansuite_HttpResponse',
                              'Clansuite_FilterManager',
                              'Clansuite_Localization',
                              'Clansuite_Security',
                              'Clansuite_Inputfilter',
                              'Clansuite_Localization',
                              'Clansuite_User',
                              'Clansuite_Session',
                              'Clansuite_Router',
                             );

        # register them to the DI as singletons
        foreach($core_classes as $class)
        {
            self::$injector->register($class);
        }
    }

    /**
     * Register the Pre- and Postfilters Classes at the Dependency Injector
     */
    private static function register_DI_Filters()
    {
        # define prefilters to load
        self::$prefilter_classes = array(
                                         # let the debug console always be first
                                         'Clansuite_Filter_PhpDebugConsole',
                                         'Clansuite_Filter_Maintenance',
                                         'Clansuite_Filter_GetUser',
                                         #'Clansuite_Filter_Session_Security',
                                         'Clansuite_Filter_Routing',
                                         'Clansuite_Filter_LanguageViaGet',
                                         'Clansuite_Filter_ThemeViaGet',
                                         'Clansuite_Filter_SetModuleLanguage',
                                         'Clansuite_Filter_StartupChecks',
                                         'Clansuite_Filter_Statistics'
                                        );

        # register the prefilters at the DI
        foreach(self::$prefilter_classes as $class)
        {
            self::$injector->register($class);
        }

        # define postfilters to load
        self::$postfilter_classes = array(
                                          #'Clansuite_Filter_HtmlTidy',
                                          'Clansuite_Filter_SmartyMoves'
                                          );

        # register the postfilters at the DI
        foreach(self::$postfilter_classes as $class)
        {
            self::$injector->register($class);
        }
    }

    /**
     *  ===================================================================
     *     Request & Response + Frontcontroller + Filters + processRequest
     *  ===================================================================
     */
    private static function execute_Frontcontroller()
    {
        # Get request and response objects for Filters and RequestProcessing
        $request  = self::$injector->instantiate('Clansuite_HttpRequest');
        $response = self::$injector->instantiate('Clansuite_HttpResponse');

        /**
         * Setup Frontcontroller and pass Request and Response
         */
        $clansuite = new Clansuite_Front_Controller($request, $response);

        /**
         * Add the Prefilters and Postfilters to the Frontcontroller
         *
         * - PRE-Filters are executed before ModuleAction is triggered
         *   Examples: caching, theme
         *
         * - POST-Filters are executed afterwards, but before view rendering
         *   Examples: output compression, character set modifications, breadcrumbs
         */
        foreach(self::$prefilter_classes as $class)
        {
            $clansuite->addPrefilter(self::$injector->instantiate($class));
        }
        foreach(self::$postfilter_classes as $class)
        {
            $clansuite->addPostfilter(self::$injector->instantiate($class));
        }

        # Take off.
        $clansuite->processRequest();
    }

    /**
     * Starts a new Session and Userobject
     */
    private static function start_Session()
    {
        # Initialize Doctrine before session start, because session is written to database
        new Clansuite_Doctrine(self::$config['database']);

        # Initialize Session
        self::$injector->create('Clansuite_Session');

        # register the session-depending User-Object manually
        self::$injector->instantiate('Clansuite_User');
    }

    /**
     *  ================================================
     *     Clansuite Version Information
     *  ================================================
     */
    private static function initialize_Version()
    {
        include ROOT_CORE . 'bootstrap/clansuite.version.php';
        Clansuite_Version::setVersionInformation();
    }

    /**
     *  ================================================
     *     Set Timezone Settings
     *  ================================================
     * with (1) ini_set()
     *      (2) date_default_timezone_set()
     *      (3) putenv(TZ=)
     *
     * For a lot more timezones look in the Appendix H of the PHP Manual
     * @link http://php.net/manual/en/timezones.php
     * @todo make $timezone configurable by user (small dropdown) or autodetected from user
     */
    private static function initialize_Timezone()
    {
        # apply timezone defensivly
        if(isset(self::$config['language']['timezone']))
        {
            ini_set('date.timezone', self::$config['language']['timezone']);

            if(function_exists('date_default_timezone_set'))
            {
                date_default_timezone_set(self::$config['language']['timezone']);
            }
            else
            {
                putenv('TZ=' . self::$config['language']['timezone']);
            }
        }

        # set date formating via config
        if(isset(self::$config['locale']['dateformat']))
        {
            define('DATE_FORMAT', self::$config['locale']['dateformat'], false);
        }
        else # set default value
        {
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
        if(class_exists('Clansuite_Eventdispatcher', false) == true)
        {
            Clansuite_Eventdispatcher::instantiate()->triggerEvent($event, $context, $info);
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

        if(DEBUG == true)
        {
            # Display the General Application Runtime
            echo 'Application Runtime: '.round(microtime(1) - constant('STARTTIME'), 3).' Seconds';
        }
    }
}
?>