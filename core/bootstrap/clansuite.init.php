<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 *  ================================================
 *     Startup Checks
 *  ================================================
 */
# PHP Version Check
if (version_compare(PHP_VERSION, '5.2', '<') == true) { die('Your PHP Version: <b>' . PHP_VERSION . '</b>! Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>'); }
# PDO Check
if (!class_exists('pdo')) { die('<i>php_pdo</i> not enabled!'); }
# PDO mysql driver Check
if (!in_array('mysql', PDO::getAvailableDrivers() )) { die('<i>php_pdo_mysql</i> driver not enabled.'); }

/**
 *  ================================================
 *     Alter php.ini settings
 *  ================================================
 */
ini_set('short_open_tag'                , 'off');
ini_set('arg_separator.input'           , '&amp;');
ini_set('arg_separator.output'          , '&amp;');
ini_set('memory_limit'                  , '20M' );

/**
 *  ================================================
 *     Debug Mode & Error Reporting Level
 *  ================================================
 *
 * Some words about the PHP Error Reporting Level.
 * The Error Reporting depends on the Debug Mode Setting.
 * When the Debug Mode is enabled Clansuite runs with error reporting set to E_ALL | E_STRICT.
 * When the Debug is disabled Clansuite will not report any errors (0).
 * For security reasons you are advised to change the Debug Mode Setting to disabled when your site goes live.
|* For more info visit:  http://www.php.net/error_reporting
 * @note: in php6 e_strict will be moved into e_all
 */

# Debug-Mode is set via config
define('DEBUG', $config['error']['debug']);
# If Debug is enabled, set FULL error_reporting, else DISABLE it completely
if ( defined('DEBUG') && DEBUG == true ) # == true or false
{
    ini_set('display_startup_errors', true);
    ini_set('display_errors', true);    # display errors in the browser
    error_reporting(E_ALL | E_STRICT);  # all errors and strict standard optimizations
}
else
{
    ini_set('display_errors',   false); # do not display errors in the browser
    error_reporting(0);                 # do not report errors
}

# Setup XDebug
define('XDEBUG', $config['error']['xdebug']);
if((bool)XDEBUG === true)
{
    require 'core/bootstrap/clansuite.xdebug.php';
    clansuite_xdebug::start_xdebug();
}

/**
 *  ================================================
 *     Define Constants
 *  ================================================
 *   - Syntax Declarations
 *   - Path Assignments
 *   - ROOT & *_ROOT
 *   - WWW_ROOT & WWW_ROOT_*
 *   - DB_PREFIX
 *   - NL, CR
 *  ------------------------------------------------
 */
# DEFINE Shorthands and Syntax Declarations for DIRECTORY_SEPARATOR & PATH_SEPARATOR
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);

# DEFINE -> ROOT
# Purpose of ROOT is to provide the absolute path to the current working dir of clansuite
define('ROOT',  getcwd() . DS);
#define('ROOT'       , str_replace('\\', '/', dirname(__FILE__) ) . '/'); # Replace the DSs to Unix Style

# DEFINE -> Directories related to ROOT
# Purpose: absolute path shortcuts
define('ROOT_MOD'           , ROOT . $config['paths']['mod_folder'].DS);
define('ROOT_THEMES'        , ROOT . $config['paths']['themes_folder'].DS);
define('ROOT_LANGUAGES'     , ROOT . $config['paths']['language_folder'].DS);
define('ROOT_CORE'          , ROOT . $config['paths']['core_folder'].DS);
define('ROOT_LIBRARIES'     , ROOT . $config['paths']['libraries_folder'].DS);
define('ROOT_UPLOAD'        , ROOT . $config['paths']['upload_folder'].DS);
define('ROOT_LOGS'          , ROOT . $config['paths']['logfiles_folder'].DS);
define('ROOT_CONFIG'        , ROOT . 'configuration'.DS);

# DEFINE -> Webpaths for Templates

# @toto get rid of using $_SERVER

# 1. Determine Type of Protocol for Webpaths (http/https)
if(isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on')
{
    define('PROTOCOL','https://');
}
else
{
    define('PROTOCOL','http://');  
}
# 2. SERVER_URL
define('SERVER_URL'    , PROTOCOL.$_SERVER['SERVER_NAME']);
# 3. Build WWW_ROOT = complete www-path with server from SERVER_URL, depending on os-system
# Purpose of WWW_ROOT is to provide the complete www-path for later use in templates
# Example: WWW_ROOT = 'http://www.yourdomain.com/clansuite_root_directory/';
if (dirname($_SERVER['PHP_SELF']) == "\\" )
{
    define('WWW_ROOT', SERVER_URL );
}
else
{
    define('WWW_ROOT', SERVER_URL.dirname($_SERVER['PHP_SELF']) );
}



# Purpose: webpath shortcuts for direct usage in templates
#define('WWW_ROOT_THEMES'       , WWW_ROOT . '/' . $config['paths']['themes_folder']);
#define('WWW_ROOT_THEMES_CORE'  , WWW_ROOT . '/' . $config['paths']['themes_folder'] .  '/core');

# DEFINE -> Database Prefix
define('DB_PREFIX'          , $config['database']['db_prefix']);
# DEFINE -> HTML Break + Carriage Return
define('NL', "<br />\r\n");
define('CR', "\n");

# Set Include Path for PEAR Libraries
# Note: Path order is important <first path to look>:<second path>:<etc>:
set_include_path( ROOT_LIBRARIES . 'PEAR' . DS . PS .                   # /libraries/PEAR
                  #$_SERVER['DOCUMENT_ROOT'].'/libraries/PEAR/' . PS .  # /libraries/PEAR
                  ROOT_LIBRARIES . PS  .                                # /libraries/
                  get_include_path()                                    # attach rest
                 );

/**
 *  ================================================
 *     Set Timezone Settings
 *  ================================================
 * with (1) ini_set()
 *      (2) date_default_timezone_set()
 *      (3) putenv(TZ=)
 *
 * PHP 5.1 strftime() and date-calculation bugfix by setting the timezone
 * For a lot more timezones look in the Appendix H of the PHP Manual
 * @link http://php.net/manual/en/timezones.php
 * @todo make $timezone configurable by user (small dropdown) or autodetected from user
 */
ini_set('date.timezone', $config['language']['timezone']);
if(function_exists('date_default_timezone_set'))
{
    date_default_timezone_set($config['language']['timezone']);
}
else
{
    putenv('TZ=' . $config['language']['timezone']);
}

/**
 *  ================================================
 *     Clansuite Version Information
 *  ================================================
 */
require ROOT_CORE . 'bootstrap/clansuite.version.php';
?>