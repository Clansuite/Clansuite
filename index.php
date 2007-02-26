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
    *
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  2006 Clansuite Group
    * @license    see COPYING.txt
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

define('IN_CS', true);  # Security Handler

// Alter php.ini settings
ini_set('display_errors'                , true); // todo: give no information to possible attackers, set false if DEBUG false
ini_set('zend.ze1_compatibility_mode'   , false);
ini_set('zlib.output_compression'       , true);
ini_set('zlib.output_compression_level' , '6');
ini_set('arg_separator.input'           , '&amp;');
ini_set('arg_separator.output'          , '&amp;');

// Reverse the effect of register_globals
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

// GET PATH => Set Base_URL -> BASE_URL_SEED2
define('BASE_URL'    , 'http://'.$_SERVER['SERVER_NAME']);
if (dirname($_SERVER['PHP_SELF']) == "\\" )
{
    define('BASE_URL_SEED', BASE_URL );
}
else
{
    define('BASE_URL_SEED', BASE_URL.dirname($_SERVER['PHP_SELF']) );
}

/**
*  ===========================================
*  Config Classes is loaded and initalized.
*  ===========================================
*/
require('config.class.php');
$cfg = new config;

// Defines: Path Assignments, *_ROOT, *_NAME, DEBUG, DB_PREFIX
define('ROOT'       , str_replace('\\', '/', dirname(__FILE__) ) . '/'); # ROOT is Basedir
define('ROOT_MOD'   , ROOT . $cfg->mod_folder);
define('ROOT_TPL'   , ROOT . $cfg->tpl_folder);
define('ROOT_LANG'  , ROOT . $cfg->lang_folder);
define('ROOT_CORE'  , ROOT . $cfg->core_folder);
define('ROOT_UPLOAD', ROOT . $cfg->upload_folder);
define('TPL_NAME'   , $cfg->tpl_name);
define('DEBUG'      , $cfg->debug);
define('DB_PREFIX'  , $cfg->db_prefix);
define('WWW_ROOT'   , BASE_URL_SEED); # WWW_ROOT complete www-path with server
define('WWW_ROOT_TPL_CORE', WWW_ROOT . '/' . $cfg->tpl_folder .  '/core');

// Error Reporting Depth
DEBUG ? error_reporting(E_ALL|E_NOTICE) : error_reporting(E_ALL ^ E_NOTICE);

/**
*  ===========================================
*  Required Classes are loaded and initalized.
*  ===========================================
*/

// Require Core Classes
require(ROOT_CORE . '/smarty/Smarty.class.php');
require(ROOT_CORE . '/smarty/Render_SmartyDoc.class.php');
require(ROOT_CORE . '/session.class.php');
require(ROOT_CORE . '/input.class.php');
require(ROOT_CORE . '/debug.class.php');
require(ROOT_CORE . '/errorhandling.class.php');
require(ROOT_CORE . '/modules.class.php');
require(ROOT_CORE . '/functions.class.php');
require(ROOT_CORE . '/language.class.php');
require(ROOT_CORE . '/security.class.php');
require(ROOT_CORE . '/users.class.php');
require(ROOT_CORE . '/db.class.php');
require(ROOT_CORE . '/stats.class.php');
require(ROOT_CORE . '/permissions.class.php');
require(ROOT_CORE . '/trail.class.php');

// Create objects out of classes
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
*  ===========================================
*     Object: Settings and  Function Calls
*  ===========================================
*/

// Smarty Settings
$tpl->template_dir      = array(ROOT_TPL . '/' . TPL_NAME . '/', ROOT_TPL . '/core/' ) ;
$tpl->compile_dir       = ROOT_CORE .'/smarty/templates_c/';
$tpl->config_dir        = ROOT_CORE .'/smarty/configs/';
$tpl->cache_dir         = ROOT_CORE .'/smarty/cache/';
$tpl->debugging         = DEBUG ? true : false;
$tpl->force_compile     = true;
$tpl->caching           = $cfg->caching;
$tpl->compile_check     = true;
$tpl->cache_lifetime    = $cfg->cache_lifetime;
$tpl->debug_tpl         = ROOT_TPL . '/core/debug.tpl';
$tpl->autoload_filters  = array(    'pre' => array('inserttplnames')
                                   #,'output' => array('tidyrepairhtml')
                                    );
DEBUG ? $tpl->clear_compiled_tpl() : ''; # clear compiled tpls in case of debug

// PHP 5.1 strftime fix by setting the timezone
// more timezones in Appendix H of PHP Manual -> http://us2.php.net/manual/en/timezones.php
date_default_timezone_set('Europe/Berlin');

// Create DB Object by setting DSN and connecting to DB
$dsn = "$cfg->db_type:dbname=$cfg->db_name;host=$cfg->db_host";
$user = $cfg->db_username;
$password = $cfg->db_password;
$db = new db($dsn, $user, $password, array() );

$input->fix_magic_quotes();         # Revert magic_quotes() if still enabled
$input->cleanup_request();          # Clean $_REQUEST input from violent code
$error->set_callbacks();            # Set the callback function for errors
$modules->load_whitelist();         # Load whitelist of modules
$session->create_session($db);      # Create a session
$users->create_user();              # Create a user
$users->check_login_cookie();       # Check for login cookie - Guest/Member
$stats->assign_statistic_vars();    # Assign Statistic Variables

/**
 *  ==================================
 *          Prepare the Output !
 *  ==================================
 */

// Set our Content-Type to UTF-8 encoding
header('Content-Type: text/html; charset=UTF-8');

// Set Language for requested module
# todo -> right place would be core language class?
$_REQUEST['mod']!='' ? $lang->load_lang($_REQUEST['mod'] ) : '';

/**
 *  ====================================================
 *     Assignments to the Template (tpl) in General
 *  ====================================================
 */

// Assign Paths (for general use in tpl)
$tpl->assign('www_root'         , WWW_ROOT );
$tpl->assign('www_tpl_root'     , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME );
$tpl->assign('www_core_tpl_root', WWW_ROOT_TPL_CORE );

// Assign Config Values (for use in header of tpl)
$tpl->assign('meta'             , $cfg->meta );
$tpl->assign('query_counter'    , $db->query_counter );
$tpl->assign('redirect'         , $functions->redirect );
$tpl->assign('css'              , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_css );
$tpl->assign('javascript'       , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_javascript );
$tpl->assign('std_page_title'   , $cfg->std_page_title );

// Assign Breadcrumb-Trail Home
$tpl->assign_by_ref('trail'     , $trail->path);

/**
 *   Check for our Copyright-Sign {$copyright} and assign it
 *
 *   Keep in mind ! that we spend a lot of time and ideas on this project.
 *   If you rip, rip real good, knowing that you are forced to give something back to the community.
 */
$security->check_copyright( ROOT_TPL . '/' . TPL_NAME . '/' . $cfg->tpl_wrapper_file );
$tpl->assign('copyright', $tpl->fetch(ROOT_TPL . '/core/copyright.tpl'));

/**
 *  ====================================================
 *     Assignments to the Template (tpl) from Module
 *  ====================================================
 */

// $content is an array and contains ['ADDITIONAL_HEAD'], ['SUPPRESS_WRAPPER'], ['OUTPUT']
$content = $modules->get_content($_REQUEST['mod'], $_REQUEST['sub']);

// handle additional_head
if (isset($content['ADDITIONAL_HEAD']) && !empty($content['ADDITIONAL_HEAD']))
{
    $tpl->assign('additional_head'  , $content['ADDITIONAL_HEAD'] );
}

/**
 * Pre-Conditions for Template Output
 * checks $_REQUEST, $content['SUPPRESS_WRAPPER'], $cfg->maintenance
 * and sets Condition
 *
 * Step 1:     Check if : Maintenance_mode is set
 *             - show only the content of the maintenance tpl
 *             - if right for access_controlcenter
 *             - turn maintenance off, show normal wrapped template
 *
 * Step 2:     Check if : Admin module <- Switch -> Normal module
 *             - if admin modules requested:
 *               - check permissions
 *               - if right for access_controlcenter, turn maintenance off, then display
 *               - else redirect to index or login
 *             - if normal modules reqiested:
 *               - display tpl_wrapper_file (content of module and stuff around that)
 *
 * Step 3:     Check if : Suppress Wrapper is set
 *             - only the content of the suppressing module is echoed
 */

// Set default_condition : normal template
$condition = 'display_normal_wrapped_template';

// Step 1: set condition for maintenance
if ( $cfg->maintenance == 1 )
{
    $condition = 'display_maintenance_template';

    // override maintenance_mode for admins to keep system maintainable
    if ( $perms->check('access_controlcenter', 'no_redirect') == true )
    {
        $cfg->maintenance == 0;
        $condition = 'display_normal_wrapped_template';
    }
}

// Step 2: Set condition for admininterface
if ( ($_REQUEST['mod'] == 'admin') OR ($_REQUEST['sub'] == 'admin') )
{
    // Check if sufficent right to access "admin control center" center
    if ( $perms->check('access_controlcenter', 'no_redirect') == true )
    {
        // Overwrite maintenance_mode for admins to keep system maintainable
        $cfg->maintenance_mode = 0;
        // Set condition to display_admincontrolcenter
        $condition = 'display_admincontrolcenter';
    }
    else
    {
         // Not enough rights to access "acc"
         // If not even logged in, so redirect to login
         if ( $_SESSION['user']['user_id'] == 0 )
         {
            $functions->redirect('index.php?mod=account&action=login&referer='.base64_encode($_SERVER['REQUEST_URI']));
         }
         // else user is logged in, but doesn't have sufficent rights
         else
         {
            $functions->redirect('index.php', 'metatag|newsite', 5, $lang->t('You do not have sufficient rights!') );
         }
    }
}

// Step 3: Set condition for suppressed wrapper
if ( isset($content['SUPPRESS_WRAPPER'] ) && ( $content['SUPPRESS_WRAPPER'] == true ) )
{
    $condition = 'display_template_with_suppressed_wrapper';
}

/**
 *   Finally: The Switch on Conditions
 *
 *   A. display_normal_wrapped_template
 *   B. display_template_with_suppressed_wrapper
 *   C. display_admincontrolcenter
 *   D. display_maintenance_template
 */
switch ($condition)
{

            // (A) displays content of modul with portal frame
            default:
            case 'display_normal_wrapped_template':
                    $tpl->assign('content', $content['OUTPUT'] );
                    DEBUG ? $debug->show_console() : '';
                    $tpl->displayDoc($cfg->tpl_wrapper_file);
                    break;

            // (B) means just the content of the modul, without the portal frame
            case 'display_template_with_suppressed_wrapper':
                    echo $content['OUTPUT'];
                    break;

            // (C) display AdminControlCenter
            case 'display_admincontrolcenter':
                    $tpl->assign('content', $content['OUTPUT'] );
                    $tpl->displayDoc('admin/index.tpl');
                    break;

            // (D) display the maintenance template
            case 'display_maintenance_template':
                    $tpl->assign('maintenance_reason', $cfg->maintenance_reason);
                    $tpl->displayDoc('maintenance.tpl');
                    break;
}

?>