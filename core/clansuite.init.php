<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
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
define('REQUIRED_PHP_VERSION', '5.2');
if (version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<') == true) { die('Your PHP Version: <b>' . PHP_VERSION . '</b>! Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>'); }
# PDO Check
if (!class_exists('pdo')) { die('<i>php_pdo</i> not enabled!'); }
# PDO mysql driver Check
if (!in_array('mysql', PDO::getAvailableDrivers() )) { die('<i>php_pdo_mysql</i> driver not enabled.'); }


/**
 *  ================================================
 *     Debug & Error Reporting
 *  ================================================
 *
 *  @note: in php6 e_strict will be moved into e_all
 */

# Debug-Mode is set via config
define('DEBUG', $config['error']['debug']);
# If Debug is enabled, set FULL error_reporting, else DISABLE it completely
if ( defined('DEBUG') && DEBUG==1 )
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

/**
 *  ================================================
 *     Define Constants
 *  ================================================
 *   - Path Assignments
 *   - ROOT & *_ROOT
 *   - WWW_ROOT & WWW_ROOT_*
 *   - DB_PREFIX
 *   - NL
 */
# DEFINE_SHORTHAND -> DIRECTORY_SEPARATOR
define('DS', DIRECTORY_SEPARATOR);

# DEFINE -> ROOT
# Purpose of ROOT is to provide the absolute path to the current working dir of clansuite
define('ROOT',  getcwd() . DS);
#define('ROOT'       , str_replace('\\', '/', dirname(__FILE__) ) . '/');

# DEFINE -> Directories related to ROOT
define('ROOT_MOD'           , ROOT . $config['paths']['mod_folder'].DS);
define('ROOT_THEMES'        , ROOT . $config['paths']['themes_folder'].DS);
define('ROOT_LANGUAGES'     , ROOT . $config['paths']['language_folder'].DS);
define('ROOT_CORE'          , ROOT . $config['paths']['core_folder'].DS);
define('ROOT_LIBRARIES'     , ROOT . $config['paths']['libraries_folder'].DS);
define('ROOT_UPLOAD'        , ROOT . $config['paths']['upload_folder'].DS);

# DEFINE -> Webpaths for Templates

# 1. SERVER_URL
define('SERVER_URL'    , 'http://'.$_SERVER['SERVER_NAME']);
# 2. Build WWW_ROOT = complete www-path with server from SERVER_URL, depending on os-system
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

# DEFINE -> Directories related to WWW_ROOT
define('WWW_ROOT_THEMES'       , WWW_ROOT . '/' . $config['paths']['themes_folder']);
define('WWW_ROOT_THEMES_CORE'  , WWW_ROOT . '/' . $config['paths']['themes_folder'] .  '/core');

# DEFINE -> Database Prefix
define('DB_PREFIX'          , $config['database']['db_prefix']);
# DEFINE -> HTML Break + Carriage Return
define('NL', "<br />\r\n");

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
?>
