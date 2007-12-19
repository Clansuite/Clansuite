<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
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
    * @copyright  Jens-Andre Koch (2005-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 *  ================================================
 *     Startup Checks
 *  ================================================
 */
# PHP Version Check
define('REQUIRED_PHP_VERSION', '5.2');
if (version_compare(PHP_VERSION, '5.2', '<') == true) { die('Your PHP Version: <b>' . PHP_VERSION . '</b>! Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>'); }
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
define('DEBUG', $config['debug']);
# If Debug is enabled, set FULL error_reporting, else DISABLE it completely
if ( defined('DEBUG') && DEBUG===1 )
{
    ini_set('display_errors',   true);  # display errors in the browser
    error_reporting(E_ALL | E_STRICT);  # all errors and strict standard optimizations
}
else
{
    ini_set('display_errors',   false); # do not display errors in the browser
    error_reporting(0);                 # do not report errors
};

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
# DEFINE -> ROOT
# Purpose of ROOT is to provide the absolute path to the current working dir of clansuite
define('ROOT',  getcwd() . '/');
#define('ROOT'       , str_replace('\\', '/', dirname(__FILE__) ) . '/');

# DEFINE -> Directories related to ROOT
define('ROOT_MOD'           , ROOT . $config['mod_folder']);
define('ROOT_TPL'           , ROOT . $config['tpl_folder']);
define('ROOT_LANGUAGES'     , ROOT . $config['language_folder']);
define('ROOT_CORE'          , ROOT . $config['core_folder']);
define('ROOT_LIBRARIES'     , ROOT . $config['libraries_folder']);
define('ROOT_UPLOAD'        , ROOT . $config['upload_folder']);

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
define('WWW_ROOT_TPL'       , WWW_ROOT . '/' . $config['tpl_folder']);
define('WWW_ROOT_TPL_CORE'  , WWW_ROOT . '/' . $config['tpl_folder'] .  '/core');

# DEFINE -> Database Prefix
define('DB_PREFIX'          , $config['db_prefix']);
# DEFINE -> HTML Break + Carriage Return
define('NL', "<br />\r\n");

/**
 *  ================================================
 *     Alter php.ini settings
 *  ================================================
 * @note: in php6 zend.ze1 compatbility will be removed
 */
#ini_set('zend.ze1_compatibility_mode'  , false);
ini_set('short_open_tag'                , 'off');
ini_set('arg_separator.input'           , '&amp;');
ini_set('arg_separator.output'          , '&amp;');
ini_set('memory_limit'                  , '20M' );

/**
 *  ================================================
 *     Unicode & Charset Settings
 *  ================================================
 * @link    http://www.php.net/manual/en/ref.unicode.php
 */
#declare(encoding=$config['outputcharset']);
/**
unicode.fallback_encoding       =
unicode.from_error_mode	        = U_INVALID_SUBSTITUTE;         # replace invalid characters
unicode.from_error_subst_char	=
unicode.http_input_encoding	    = $config['outputcharset'];
unicode.output_encoding	        = $config['outputcharset'];
unicode.runtime_encoding        =
unicode.script_encoding         = $config['outputcharset'];
# this is PHP_INI_PERDIR and can only set via php.ini or .htaccess "php_flag unicode.semantics 1"
#unicode.semantics               = 1
*/

// Set Charset and Character Encoding
if(function_exists('mb_http_output'))
{
	mb_http_output($config['outputcharset']);
	mb_internal_encoding($config['outputcharset']);
	# replace mail(), str*(), ereg*() by mbstring functions
    ini_set('mbstring.func_overload','7');
}
/*
if (function_exists('iconv')
{
    iconv_set_encoding('input_encoding',   'UTF-8');
    iconv_set_encoding('internal_encoding','UTF-8');
    iconv_set_encoding('output_encoding',  $config['outputcharset']);
}*/

/**
 *  ================================================
 *     Compress output if the browser supports it
 *  ================================================
 * @note by vain: problems reported with cached smarty templates... we'll see how that works out
 */
# Method 1 zlib
ini_set('zlib.output_compression'       , true);
ini_set('zlib.output_compression_level' , '7');
# Method 2 Fallback to ob_start('gz_handler') = output buffering with gzip handling
if(!ini_get('zlib.output_compression') === true)
{
  ob_start('ob_gzhandler');
  require ROOT_LIBRARIES.'/gzip_encode/class.gzip_encode.php';
  define('OB_GZIP', true);
}

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
$timezone = 'Europe/Berlin';
ini_set('date.timezone', $timezone);
if(function_exists('date_default_timezone_set'))
{
    date_default_timezone_set($timezone);
}
else
{
    putenv('TZ=' . $timezone);
}
?>