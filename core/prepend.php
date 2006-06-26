<?php
/**
* prepend.php
* 1. Initialize common objects
* 2. create DB link
* 3. load templates
* 4. clean input
*
* PHP versions 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
define('IN_CS', true);

//------------------------------------------------
// Create Object $cfg
//------------------------------------------------
$cfg = new config;

//----------------------------------------------------------------
// Alter php.ini settings
//----------------------------------------------------------------
ini_set('display_errors', 								true);
ini_set('zend.ze1_compatibility_mode', 		false);
ini_set('zlib.output_compression',		true);
ini_set('zlib.output_compression_level',	'6');
ini_set('arg_separator.input', 						'&amp;'); 
ini_set('arg_separator.output',						'&amp;'); 

//----------------------------------------------------------------
// Reverse the effect of register_globals
//----------------------------------------------------------------
if (ini_get('register_globals'))
{
	foreach($GLOBALS as $s_variable_name => $m_variable_value)
	{
		if (!in_array($s_variable_name, array('argv', 'argc', '_FILES', '_COOKIE', '_GET', '_POST', '_SERVER', '_ENV', '_SESSION', 's_variable_name', 'm_variable_value')))
		{
			unset($GLOBALS[$s_variable_name]);
		}
	}
	unset($GLOBALS['s_variable_name']);
	unset($GLOBLAS['m_variable_value']);
}

//----------------------------------------------------------------
// Path Assignments
//----------------------------------------------------------------
define('ROOT', str_replace( '\\', '/', dirname(dirname(__FILE__).'../') ) . '/'); 
#define('ROOT', str_replace( '\\', '/', dirname(__FILE__)) . '/'); 

define('BASE_URL_SEED'	, 'http://'.$_SERVER['SERVER_NAME']);
if ( dirname($_SERVER['PHP_SELF']) == "\\" )
{ define( 'BASE_URL_SEED2', BASE_URL_SEED ); }
else
{ define( 'BASE_URL_SEED2', BASE_URL_SEED.dirname($_SERVER['PHP_SELF']) ); }

//----------------------------------------------------------------
// Define *_ROOT, *_NAME, DEBUG, DB_PREFIX 
//----------------------------------------------------------------
define('WWW_ROOT'	, BASE_URL_SEED2);

define('MOD_ROOT'	, ROOT . $cfg->mod_folder);
define('TPL_ROOT'	, ROOT . $cfg->tpl_folder);
define('LANG_ROOT'	, ROOT . $cfg->lang_folder);
define('CORE'		, ROOT . $cfg->core_folder);
define('TPL_NAME'	, $cfg->tpl_name);

define('DEBUG'		, $cfg->debug);
define('DB_PREFIX'	, $cfg->db_prefix);
/*
echo ROOT."<br>";
echo CORE."<br>";;
echo LANG_ROOT."<br>";
echo TPL_ROOT."<br>";
echo TPL_NAME."<br>";
*/
//----------------------------------------------------------------
// Error Reporting Depth
//----------------------------------------------------------------
DEBUG ? error_reporting(E_ALL|E_NOTICE) : error_reporting(E_ALL ^ E_NOTICE);

//----------------------------------------------------------------
// Require Core Classes
//----------------------------------------------------------------
require (CORE . '/phpOpenTracker.php');
require (CORE . '/smarty/Smarty.class.php');
require (CORE . '/session.class.php');
require (CORE . '/input.class.php');
require (CORE . '/debug.class.php');
require (CORE . '/error.class.php');
require (CORE . '/modules.class.php');
require (CORE . '/functions.class.php');
require (CORE . '/language.class.php');
require (CORE . '/security.class.php');
require (CORE . '/users.class.php');
require (CORE . '/db.class.php');

//----------------------------------------------------------------
// Create objects out of classes
//----------------------------------------------------------------
$tpl 			= new Smarty;
$session		= new session;
$input			= new input;
$debug 			= new debug;
$error 			= new error;
$modules 		= new modules;
$functions 		= new functions;
$lang 			= new language;
$security		= new security;
$users			= new users;

//----------------------------------------------------------------
// Smarty Paths
//----------------------------------------------------------------
$tpl->template_dir 	= array( TPL_ROOT . '/' . TPL_NAME . '/', TPL_ROOT . '/core/' ) ;
$tpl->compile_dir	= CORE .'/smarty/templates_c/';
$tpl->config_dir	= CORE .'/smarty/configs/';
$tpl->cache_dir		= CORE .'/smarty/cache/';

//----------------------------------------------------------------
// Smarty Settings
//----------------------------------------------------------------
$tpl->debugging		= DEBUG ? true : false;
$tpl->debug_tpl		= TPL_ROOT . '/core/debug.tpl';
$tpl->autoload_filters  = array('pre' 	=> array('inserttplnames'),
                               'output' => array('gzip') ); 

//----------------------------------------------------------------
// Load up DSN & Connect DB
//----------------------------------------------------------------
$dsn = "$cfg->db_type:dbname=$cfg->db_name;host=$cfg->db_host";
$user = $cfg->db_username;
$password = $cfg->db_password;
$db = new db($dsn, $user, $password, array('PDO_ATTR_PERSISTENT' => true));

//----------------------------------------------------------------
// Revert magic_quotes() if still enabled
// Clean $_REQUEST input from violent code
//----------------------------------------------------------------
$input->fix_magic_quotes();
$input->essential_cleanup();

//----------------------------------------------------------------
// Set the callback function for errors
//----------------------------------------------------------------
$error->set_callback();

//----------------------------------------------------------------
// Create a user session
//----------------------------------------------------------------
$session->create_session();

//----------------------------------------------------------------
// Logging
//----------------------------------------------------------------
phpOpenTracker::log();

//----------------------------------------------------------------
// Output all
//----------------------------------------------------------------
$_REQUEST['mod']!='' ? $lang->load_lang( $_REQUEST['mod'] ) : '';
$content = $modules->get_content($_REQUEST['mod']);
$security->check_copyright( TPL_ROOT . '/' . TPL_NAME . '/' . $cfg->tpl_wrapper_file );
 
//----------------------------------------------------------------
// Assign Paths to Template (tpl) & Smarty Var Assignments
//----------------------------------------------------------------
$tpl->assign('root', ROOT );
$tpl->assign('www_root', WWW_ROOT );
$tpl->assign('www_tpl_root', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME );
$tpl->assign('www_core_tpl_root', WWW_ROOT . '/' . $cfg->tpl_folder . '/core' );

$tpl->assign('exec_counter', $db->exec_counter );
$tpl->assign('redirect', $functions->redirect );
$tpl->assign('css', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_css );
$tpl->assign('javascript', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_javascript );
$tpl->assign('additional_head', $content['ADDITIONAL_HEAD'] );
$tpl->assign('std_page_title', $cfg->std_page_title );
$tpl->assign('mod_page_title', $content['MOD_PAGE_TITLE'] );
$tpl->assign('copyright', $cfg->copyright );

$tpl->assign('content', $content['OUTPUT'] );

?>