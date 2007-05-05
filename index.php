<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         index.php
    * Requires:     PHP5+
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
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$Date$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

   /** =====================================================================
    *  WARNING: DO NOT MODIFY THIS FILE, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *           READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

/**
 * Defines the Security Handler
 */
define('IN_CS', true);

/**
 * Check if config.class.php is found, if true redirect to installation page
 */
if ( !file_exists( 'config.class.php' ) ) { header( 'Location: installation/index.php' ); exit(); }

/**
 * Alter php.ini settings
 * @todo give no information to possible attackers, set (display_errors = false) if (DEBUG = false)
 */
ini_set('display_errors'                , true);
ini_set('zend.ze1_compatibility_mode'   , false);
ini_set('zlib.output_compression'       , true);
ini_set('zlib.output_compression_level' , '6');
ini_set('arg_separator.input'           , '&amp;');
ini_set('arg_separator.output'          , '&amp;');

/**
 * Reverse the effect of register_globals
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
 * DEFINE PATHS
 * BASE_URL = Serverurl
 * BASE_URL_SEED = BASE_URL added by application directory
 */
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
 *  ================================================
 *      Config Classes is loaded and initalized.
 *  ================================================
 */
require('config.class.php');
$cfg = new config;

/**
 * Defines: Path Assignments, *_ROOT, *_NAME, DEBUG, DB_PREFIX
 */
define('ROOT'       , str_replace('\\', '/', dirname(__FILE__) ) . '/'); # ROOT is Basedir
define('ROOT_MOD'   , ROOT . $cfg->mod_folder);
define('ROOT_TPL'   , ROOT . $cfg->tpl_folder);
define('ROOT_LANG'  , ROOT . $cfg->lang_folder);
define('ROOT_CORE'  , ROOT . $cfg->core_folder);
define('ROOT_UPLOAD', ROOT . $cfg->upload_folder);
define('DEBUG'      , $cfg->debug);
define('DB_PREFIX'  , $cfg->db_prefix);
define('WWW_ROOT'   , BASE_URL_SEED); # WWW_ROOT complete www-path with server
define('WWW_ROOT_TPL_CORE', WWW_ROOT . '/' . $cfg->tpl_folder .  '/core');

/**
 *  DATABASE
 *  - require db.class.php
 *  - Create DB Object 
 *  - Connect to DB
 *  
 *  Why is this located here? 
 *  Because we do not want functions to be executed between get of $cfg array and the unset of $cfg-db_password
 */
require(ROOT_CORE . '/db.class.php');
$db = new db("$cfg->db_type:dbname=$cfg->db_name;host=$cfg->db_host", $cfg->db_username, $cfg->db_password, array() );
unset($cfg->db_password); # unset db_password, because we are really really paranoid

// Little late but setup the benchmarking and a execution-timemarker
include_once(ROOT_CORE . '/benchmark.class.php'); 
benchmark::timemarker('begin', 'Exectime:');

// Error Reporting Depth
DEBUG ? error_reporting(E_ALL|E_NOTICE) : error_reporting(E_ALL ^ E_NOTICE);

/**
 *  ========================================================
 *      Required CORE Classes are loaded and initalized.
 *  ========================================================
 */
require(ROOT_CORE . '/smarty/Smarty.class.php');
require(ROOT_CORE . '/smarty/Render_SmartyDoc.class.php');
//require(ROOT_CORE . '/smarty/SmartyDoc2.class.php5'); // note by vain: leave here, needed for smarty plugin dev purposes
require(ROOT_CORE . '/session.class.php');
require(ROOT_CORE . '/input.class.php');
require(ROOT_CORE . '/language.class.php');
require(ROOT_CORE . '/debug.class.php');
require(ROOT_CORE . '/errorhandling.class.php');
require(ROOT_CORE . '/modules.class.php');
require(ROOT_CORE . '/functions.class.php');
require(ROOT_CORE . '/security.class.php');
require(ROOT_CORE . '/users.class.php');
require(ROOT_CORE . '/stats.class.php');
require(ROOT_CORE . '/permissions.class.php');
require(ROOT_CORE . '/trail.class.php');
//require(ROOT_CORE . '/openid.class.php'); // note by vain: leave here, needed for openid dev purposes

// Create objects out of classes
$tpl        = new Render_SmartyDoc; # Template
$session    = new session;          # Session
$input      = new input;            # Input
$lang       = new language;         # Language
$debug      = new debug;            # Debugging
$error      = new error;            # Error
$modules    = new modules;          # Modules
$functions  = new functions;        # Functions
$security   = new security;         # Security
$users      = new users;            # Users
$stats      = new statistics;       # Statistics
$perms      = new permissions;      # Permissions
$trail      = new trail;            # Trail/Breadcrumb
#$openid     = new openid;           # OpenID Authentication

/**
 *  ============================================
 *      Object: Settings and  Function Calls
 *  ============================================
 */

// Smarty Settings
$tpl->template_dir      = array( ROOT_TPL . '/core/' ) ;        # template_dir set to CORE / fallback for errors
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
$tpl->register_modifier('timemarker',  array('benchmark', 'timemarker'));

DEBUG ? $tpl->clear_compiled_tpl() : ''; # clear compiled tpls in case of debug

/**
 * Assign Paths (for general use in tpl)
 */
$tpl->assign('www_root'         , WWW_ROOT );
$tpl->assign('www_root_upload'  , WWW_ROOT . '/' . $cfg->upload_folder );
$tpl->assign('www_root_tpl_core', WWW_ROOT_TPL_CORE );

// PHP 5.1 strftime fix by setting the timezone
// more timezones in Appendix H of PHP Manual -> http://us2.php.net/manual/en/timezones.php
date_default_timezone_set('Europe/Berlin');

// Do all needed build functions to generate valid PHP output, input, throughput and ... put put put
$input->fix_magic_quotes();         # Revert magic_quotes() if still enabled
$input->cleanup_request();          # Clean $_REQUEST input from violent code
$error->set_callbacks();            # Set the callback function for errors
$modules->load_whitelist();         # Load whitelist of modules
$session->create_session($db);      # Create a session
$users->create_user();              # Create a user
$users->check_login_cookie();       # Check for login cookie - Guest/Member
$stats->assign_statistic_vars();    # Assign Statistic Variables

/**
 * Set Theme via URL by appendix $_GET['theme'] (?theme=standard)
 */
if($cfg->themeswitch_via_url == 1)
{
    if(isset($_GET['theme']) && !empty($_GET['theme']))
    {
    	// Security Handler for $_GET['theme']
        if( !$input->check( $_GET['theme'], 'is_abc|is_custom', '_' ) )
        {
            $security->intruder_alert();
        }
        // If $_GET['theme'] dir exists, set it as session-user-theme
        if(is_dir(ROOT_TPL . '/' . $_GET['theme'] . '/'))
        {
            $_SESSION['user']['theme']          = $_GET['theme'];
            $_SESSION['user']['theme_via_url']  = 1;
        }
        else
        {
            $_SESSION['user']['theme_via_url']  = 0;
        }
    }
}

// Set template dir again, this time to the choosen session theme and assign the path accordingly
$tpl->template_dir = array( ROOT_TPL . '/' . $_SESSION['user']['theme'] . '/', ROOT_TPL . '/core/' ) ;
$tpl->assign('www_root_tpl', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . $_SESSION['user']['theme'] );


/**
 * I18N
 *
 * 1) default language is set by config
 *    $cfg->language
 *    - up to this point this language is used for any output, like error-messages
 * NOW:
 * 2) override this by user-selection via URL by $_GET['lang']
 *    and set $_SESSION parameter accordingly
 *
 * notice by vain: to check if language exists is not important,
 *                 because there are 1) english and 2) the default language as fallback
 *
 */
if(isset($_GET['lang']) && !empty($_GET['lang']) )
{
	// Security Handler
    if( !$input->check( $_GET['lang'], 'is_abc|is_custom', '_' ) )
    {
        $security->intruder_alert();
    }
    // Update Session
    else
    {
	   $_SESSION['user']['language']           = $_GET['lang'];
	   $_SESSION['user']['language_via_url']   = 1;
	}
}

/**
 *  ==================================
 *          Prepare the Output !
 *  ==================================
 */

/**
 * Set X-Powered-By Header to Identify Clansuite
 * Set our Content-Type to UTF-8 encoding
 */
header('X-Powered-By: [ Clansuite - just an eSport CMS ] [ Version : '.$cfg->version.' ] [ www.clansuite.com ]' , false);
header('Content-Type: text/html; charset=UTF-8');

/*
 * Set Language for requested module, if no mod is set do nothing
 * @todo: right place would be core language class?
 */
$_REQUEST['mod']!='' ? $lang->load_lang($_REQUEST['mod'], $_SESSION['user']['language'] ) : '';

/**
 *  =====================================================
 *      Assignments to the Template (tpl) in general
 *  =====================================================
 */

/**
 * Assign Config Values (for use in header of tpl)
 */
$tpl->assign('meta'             , $cfg->meta );             # Meta Inforamtions about the website
$tpl->assign('cs_version'       , $cfg->version );          # ClanSuite Version from config.class.php
$tpl->assign('query_counter'    , $db->query_counter );     # Query counters (DB)
$tpl->assign('redirect'         , $functions->redirect );   # Redirects, if necessary
$tpl->assign('css'              , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . $_SESSION['user']['theme'] . '/' . $cfg->std_css ); # Normal CSS (global)
$tpl->assign('javascript'       , WWW_ROOT . '/' . $cfg->tpl_folder . '/' . $_SESSION['user']['theme'] . '/' . $cfg->std_javascript ); # Normal Javascript (global)
$tpl->assign('std_page_title'   , $cfg->std_page_title );   # Page Title
$tpl->assign_by_ref('trail'     , $trail->path);            # Breadcrumb

/**
 *   Check for our Copyright-Sign {$copyright} and assign it
 *
 *   Keep in mind ! that we spend a lot of time and ideas on this project.
 *   If you rip, so rip really good, knowing that you are forced to give something back to the community.
 */
$security->check_copyright( ROOT_TPL . '/' . $_SESSION['user']['theme'] . '/' . $cfg->tpl_wrapper_file );
$tpl->assign('copyright', $tpl->fetch(ROOT_TPL . '/core/copyright.tpl'));

/**
 *  =====================================================
 *      Assignments to the Template (tpl) from Module
 *  =====================================================
 */

/**
 * Content from Modules
 *
 * $content is an array and contains ['ADDITIONAL_HEAD'], ['SUPPRESS_WRAPPER'], ['OUTPUT']
 * as return values of the requested module
 * Also checks for status of module
 */
if( $cfg->modules[$_REQUEST['mod']]['enabled'] == 1 )
{
    $content = $modules->get_content($_REQUEST['mod'], $_REQUEST['sub']);
}
else
{
    $functions->redirect('index.php', 'metatag|newsite', 5, $lang->t('Module is not avaiable.') );
}

/**
 * Handle $content['ADDITIONAL_HEAD'] if var contains data
 */
if (isset($content['ADDITIONAL_HEAD']) && !empty($content['ADDITIONAL_HEAD']))
{
    $tpl->assign('additional_head'  , $content['ADDITIONAL_HEAD'] );
}

/**
 * This sets up the $condition for the template output
 * as controlled by the switch command underneath.
 *
 * It checks $_REQUEST, $content['SUPPRESS_WRAPPER'], $cfg->maintenance
 * and sets $condition accordingly.
 *
 * 1. Check
 * - if Maintenance_mode is set
 *  - show only the content of the maintenance tpl
 * - but if user_right for cc_access is set
 *  - turn maintenance off, show normal wrapped template
 *
 * 2. Check
 * - if Admin module <- Switch -> Normal module
 *  - if admin modules requested:
 *   - check permissions
 *   - if user_right for cc_access is set
 *   - turn maintenance off, then display
 *  - else redirect to index or login
 *  - if non admin module is requested:
 *   - display tpl_wrapper_file
 *
 * 3. Check
 * - if Suppress Wrapper is set
 *  - only the content of the suppressing module is echoed
 */

// Set default_condition : normal template
$condition = 'display_normal_wrapped_template';

// Step 1: set condition for maintenance
if ( $cfg->maintenance == 1 )
{
    $condition = 'display_maintenance_template';

    // override maintenance_mode for admins to keep system maintainable
    if ( $perms->check('cc_access', 'no_redirect') == true )
    {
        $cfg->maintenance == 0;
        $condition = 'display_normal_wrapped_template';
    }
}

// Step 2: Set condition for admininterface
if ( ($_REQUEST['mod'] == 'admin') OR ($_REQUEST['sub'] == 'admin') )
{
    // Check if sufficent right to access "admin control center" center
    if ( $perms->check('cc_access', 'no_redirect') == true )
    {
        // Overwrite maintenance_mode for admins to keep system maintainable
        $cfg->maintenance_mode = 0;
        // Set condition to display_admincontrolcenter
        $condition = 'display_admincontrolcenter';
    }
    else
    {
         // Not enough rights to access "the control center
         // If not even logged in, redirect to login form
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
 * The switch on $condition
 *
 * 4 Cases are handled here:
 *
 * 1. display_normal_wrapped_template
 * 2. display_template_with_suppressed_wrapper
 * 3. display_admincontrolcenter
 * 4. display_maintenance_template
 */
switch ($condition)
{
    // (1) displays content of modul with portal frame
    default:
    case 'display_normal_wrapped_template':
            $tpl->assign('content', $content['OUTPUT'] );
            DEBUG ? $debug->show_console() : '';
            $tpl->displayDoc($cfg->tpl_wrapper_file);
            break;

    // (2) means just the content of the modul, without the portal frame
    case 'display_template_with_suppressed_wrapper':
            echo $content['OUTPUT'];
            break;

    // (3) display AdminControlCenter
    case 'display_admincontrolcenter':
            $tpl->assign('content', $content['OUTPUT'] );
            DEBUG ? $debug->show_console() : '';
            $tpl->displayDoc('admin/index.tpl');
            break;

    // (4) display the maintenance template
    case 'display_maintenance_template':
            $tpl->assign('maintenance_reason', $cfg->maintenance_reason);
            $tpl->displayDoc('maintenance.tpl');
            break;
}

DEBUG ? benchmark::timemarker('list') : ''; # returns a history of timemarkers in case of debug

?>