<?php
   /**
    *   Clansuite - just an E-Sport CMS
    *   Jens-Andre Koch, Florian Wolf © 2005,2006
    *   http://www.clansuite.com/
    *
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
    */

   /** =====================================================================
    *  WARNING: DO NOT MODIFY THIS FILE, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *           READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

   /**
    *
    * index.php
    * Initialize objects, create DB link, load templates, clean input
    *
    *
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  2006 Clansuite Group
    * @license    see COPYING.txt
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id: index.php 156 2006-06-14 08:45:48Z xsign $
    */

/**
* @desc Security Handler
*/
define('IN_CS', true);

/**
* @desc Alter php.ini settings
*/
ini_set('display_errors'                , true); // todo: give no information to possible attackers, set false if DEBUG false
ini_set('zend.ze1_compatibility_mode'   , false);
ini_set('zlib.output_compression'       , true);
ini_set('zlib.output_compression_level' , '6');
ini_set('arg_separator.input'           , '&amp;');
ini_set('arg_separator.output'          , '&amp;');

/**
* @desc Reverse the effect of register_globals
*/
if (ini_get('register_globals'))
{
    foreach ($GLOBALS as $s_variable_name => $m_variable_value)
    {
        if (!in_array($s_variable_name, array('argv', 'argc', '_FILES', '_COOKIE', '_GET', '_POST', '_SERVER', '_ENV', '_SESSION', 's_variable_name', 'm_variable_value')))
        {
            unset($GLOBALS[$s_variable_name]);
        }
    }
    unset($GLOBALS['s_variable_name']);
    unset($GLOBLAS['m_variable_value']);
}

/**
* @desc Path Assignments
*/
define('BASEDIR'    , str_replace('\\', '/', dirname(__FILE__) ) . '/');
define('BASE_URL_SEED'    , 'http://'.$_SERVER['SERVER_NAME']);
if (dirname($_SERVER['PHP_SELF']) == "\\" )
{
    define('BASE_URL_SEED2', BASE_URL_SEED );
}
else
{
    define('BASE_URL_SEED2', BASE_URL_SEED.dirname($_SERVER['PHP_SELF']) );
}

/**
* @desc Load config
* @desc Create Object $cfg
*/

require('config.class.php');
$cfg = new config;

/**
* @desc Defines: *_ROOT, *_NAME, DEBUG, DB_PREFIX
*/
define('ROOT'       , $cfg->root);
define('MOD_ROOT'   , ROOT . $cfg->mod_folder);
define('TPL_ROOT'   , ROOT . $cfg->tpl_folder);
define('LANG_ROOT'  , ROOT . $cfg->lang_folder);
define('CORE_ROOT'  , ROOT . $cfg->core_folder);
define('UPLOAD_ROOT', ROOT . $cfg->upload_folder);
define('TPL_NAME'   , $cfg->tpl_name);
define('WWW_ROOT'   , $cfg->www_root);
define('DEBUG'      , $cfg->debug);
define('DB_PREFIX'  , $cfg->db_prefix);

// Some 'other' shortcuts :)
define('WWW_CORE_TPL_ROOT', $cfg->www_root . '/' . $cfg->tpl_folder .  '/core');

/**
* @desc Error Reporting Depth
*/
DEBUG ? error_reporting(E_ALL|E_NOTICE) : error_reporting(E_ALL ^ E_NOTICE);

/**
 *  ===========================================
 *  Required Classes are loaded and initalized.
 *  ===========================================
 */

/**
* @desc Require Core Classes
*/
require(CORE_ROOT . '/smarty/Smarty.class.php');
require(CORE_ROOT . '/smarty/Render_SmartyDoc.class.php');
require(CORE_ROOT . '/session.class.php');
require(CORE_ROOT . '/input.class.php');
require(CORE_ROOT . '/debug.class.php');
require(CORE_ROOT . '/errorhandling.class.php');
require(CORE_ROOT . '/modules.class.php');
require(CORE_ROOT . '/functions.class.php');
require(CORE_ROOT . '/language.class.php');
require(CORE_ROOT . '/security.class.php');
require(CORE_ROOT . '/users.class.php');
require(CORE_ROOT . '/db.class.php');
require(CORE_ROOT . '/stats.class.php');
require(CORE_ROOT . '/permissions.class.php');
require(CORE_ROOT . '/trail.class.php');


/**
* @desc Create objects out of classes
*/
$tpl        = new Render_SmartyDoc;
$session    = new session;
$input      = new input;
$debug      = new debug;
$error      = new error;
$modules    = new modules;
$functions  = new functions;
$lang       = new language;
$security   = new security;
$users      = new users;
$stats      = new statistics;
$perms      = new permissions;
$trail      = new trail;

/**
 *  =====================================
 *  The settings for the objects are set.
 *  =====================================
 */

/**
* @desc Smarty Settings
*/
$tpl->template_dir      = array(TPL_ROOT . '/' . TPL_NAME . '/', TPL_ROOT . '/core/' ) ;
$tpl->compile_dir       = CORE_ROOT .'/smarty/templates_c/';
$tpl->config_dir        = CORE_ROOT .'/smarty/configs/';
$tpl->cache_dir         = CORE_ROOT .'/smarty/cache/';
$tpl->debugging         = DEBUG ? true : false;
$tpl->force_compile     = true;
$tpl->caching           = $cfg->caching;
$tpl->compile_check     = true;
$tpl->cache_lifetime    = $cfg->cache_lifetime;
$tpl->debug_tpl         = TPL_ROOT . '/core/debug.tpl';
$tpl->autoload_filters  = array(    'pre' => array('inserttplnames')
                                     );
DEBUG ? $tpl->clear_compiled_tpl() : '';

// PHP 5.1 strftime fix by setting the timezone
// more timezones in Appendix H of PHP Manual -> http://us2.php.net/manual/en/timezones.php
date_default_timezone_set('Europe/Berlin');

/**
* @desc Breadcrumb-Trail Home
*/
$tpl->assign_by_ref('trail', $trail->path);

/**
* @desc Create DB Object by setting DSN and connecting to DB
*/
$dsn = "$cfg->db_type:dbname=$cfg->db_name;host=$cfg->db_host";
$user = $cfg->db_username;
$password = $cfg->db_password;
$db = new db($dsn, $user, $password, array() );

/**
* @desc Assign Paths to Template (tpl)
*/
$tpl->assign('www_root'         , WWW_ROOT );
$tpl->assign('www_tpl_root'     , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME );
$tpl->assign('www_core_tpl_root', WWW_ROOT . '/' . $cfg->tpl_folder . '/core' );

/**
* @desc Revert magic_quotes() if still enabled
* @desc Clean $_REQUEST input from violent code
*/
$input->fix_magic_quotes();
$input->cleanup_request();

/**
* @desc Set the callback function for errors
*/
$error->set_callbacks();

/**
* @desc Load whitelist of modules
*/
$modules->load_whitelist();

/**
* @desc Create a session
*/
$session->create_session($db);

/**
* @desc Create a user and check for login cookie - Guest/Member
*/
$users->create_user();
$users->check_login_cookie();

/**
* @desc Assign Statistic Variables
*/
$stats->assign_statistic_vars();

/**
 *  ==================================
 *  Prepare Output !
 *  ==================================
 */

/**
* @desc Set our Content-Type to UTF-8 encoding
*/
header('Content-Type: text/html; charset=UTF-8');

/**
* @desc $_REQUEST Switch - This fetches the content for the module / sub
* @output $content
*/
$_REQUEST['mod']!='' ? $lang->load_lang($_REQUEST['mod'] ) : '';
if ( $_REQUEST['mod'] == 'admin' OR $_REQUEST['sub'] == 'admin' )
{
    if ( $perms->check('access_controlcenter', 'no_redirect') )
    {
        $content = $modules->get_content($_REQUEST['mod'], $_REQUEST['sub']);
    }
    else
    {   // todo: redirect the guy back where he came from, means referer use or something
        $content = $modules->get_content('index', '');
    }
}
else
{
    $content = $modules->get_content($_REQUEST['mod'], $_REQUEST['sub']);
}


/**
* Check for our Copyright-Sign.
* Keep in mind ! that we spend a lot of time and ideas on this project.
* If you rip, rip real good, knowing that you are forced to give something back to the community.
*/
$security->check_copyright(TPL_ROOT . '/' . TPL_NAME . '/' . $cfg->tpl_wrapper_file );

/**
* @desc Assign the results
*/
$tpl->assign('meta'             , $cfg->meta );
$tpl->assign('query_counter'    , $db->query_counter );
$tpl->assign('redirect'         , $functions->redirect );
$tpl->assign('css'              , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_css );
$tpl->assign('javascript'       , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_javascript );
$tpl->assign('std_page_title'   , $cfg->std_page_title );
$tpl->assign('copyright'        , $cfg->copyright );
if (!empty($content['ADDITIONAL_HEAD'])) { $tpl->assign('additional_head'  , $content['ADDITIONAL_HEAD'] ); }
$tpl->assign('content'          , $content['OUTPUT'] );

/**
* @desc Step 1:     Check if : Suppress Wrapper is set
*                   - only the content of the suppressing module is echoed
*
* @desc Step 2:     Check if : Admin module <-> Normal module
*                   - if admin modules requested:
*                     - check permissions, if right for access_controlcenter then display
*                     - else redirect to index or login
*                   - if normal modules reqiested:
*                     - display tpl_wrapper_file (content of module and stuff around that)
*/
if ( $content['SUPPRESS_WRAPPER'] == true )
{
    echo $content['OUTPUT'];
}
else
{
    /**
    * @desc Admin module <-> Normal module
    */
    if ( $_REQUEST['mod'] == 'admin'  OR $_REQUEST['sub'] == 'admin' )
    {
        if ( $perms->check('access_controlcenter', 'no_redirect') )
        {
           $tpl->displayDoc('admin/index.tpl');
        }
        else
        {
             if ( $_SESSION['user']['user_id'] == 0 )
             {
                $functions->redirect('index.php?mod=account&action=login&referer='.base64_encode($_SERVER['REQUEST_URI']));
             }
             else
             {
                $functions->redirect( 'index.php', 'metatag|newsite', 5, $lang->t('You do not have sufficient rights to access the ACP') );
             }
        }
    }
    else
    {
        $tpl->displayDoc($cfg->tpl_wrapper_file);
    }

    /**
    * @desc Show Debug Console
    */
    DEBUG ? $debug->show_console() : '';
}

?>