<?php
    /**
     * Clansuite Installer
     * v0.2dev 10-august-2007 by vain
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
     * @author     Jens-Andre Koch <vain@clansuite.com>
     * @author     Florian Wolf <xsign.dll@clansuite.com>
     * @copyright  2006 Clansuite Group
     * @license    see COPYING.txt
     *
     * @link       http://gna.org/projects/clansuite
     * @since      File available since Release 0.1
     * @version    SVN: $Id$
     */
session_start();
set_time_limit(0);

// Security Handler
define('IN_CS', true);

// Get site path
define ('CS_ROOT', getcwd() . DIRECTORY_SEPARATOR);

// The Clansuite version this script installs
$cs_version = '0.1';

// Define $error
$error = '';

// In Case config.class.php exists, exit
/* @todo: other solution pls - 'cause the webinstaller currently leeches from the svn snapshot!!!
if (file_exists(CS_ROOT.'config.class.php'))
{
	exit('The file \'config.class.php\' already exists which would mean that <strong>Clansuite</strong> '. $cs_version . ' is already installed. You should go <a href="../index.php">here</a> instead.');
}*/


/**
 * @desc Suppress Errors
 * E_STRICT forbids the shortage of "<?php echo $language->XY ?>" to "<?=$language->XY ?>"
 * so we use e_all ... this is just an installer btw :)
 */
error_reporting(E_ALL);

#================
#     OUTPUT
#================

# INCLUDE THE HEADER!
include ('install_header.php');

/**
 * ==============================
 *    STEP HANDLING + PROGRESS
 * ==============================
 */
 # Get Total Steps and if we are at max_steps, set step to max
$total_steps = get_total_steps();

/**
* Update the session with the given variables!
*/
$_SESSION = array_merge($_SESSION, $_POST);

# STEP HANDLING
if(isset($_SESSION['step']))
{
	$step = $_SESSION['step'];
	if(isset($_POST['step_forward']))  { $step++; }
	if(isset($_POST['step_backward'])) { $step--; }
	if($step >= $total_steps) { $step = $total_steps; }
}
else { $step = 1; }

# Calculate Progress
$_SESSION['progress'] = calc_progress($step, $total_steps);

/**
 * ==============================
 *    SET DEFAULT LANGUAGE VAR
 * ==============================
 */
# Language Handling
if (isset($_GET['lang']) and !empty($_GET['lang']))
{
   $lang =  (string) htmlspecialchars($_GET['lang']);
}
else
{
    if(isset($_SESSION['lang']))
    {
        $lang = $_SESSION['lang'];
    }
    if ($step == 1 OR empty( $_SESSION['lang']))
    {
        $lang = 'german';
    }
}

# Language Include
try
{
	if (is_file (CS_ROOT . '/languages/'. $lang .'.install.php'))
	{
		require_once (CS_ROOT . '/languages/'. $lang .'.install.php');
		$language = new language;
		$_SESSION['lang'] = $lang;
	}
	else
	{
	    throw new Exception('<span style="color:red">Language file missing: <strong>' . CS_ROOT . $lang . '.install.php</strong>.</span>');
	}
}
catch (Exception $e)
{
	echo $e->getMessage().' in '.$e->getFile().', line: '. $e->getLine().'.';
}

/**
 * Handling of STEP 4 - Database
 * if STEP 4 successful, proceed to 5 - else return STEP 4, display error
 */
if( isset($_POST['step_forward']) AND $step == 5 )
{
	$sqlfile = 'clansuite.sql';
	$db = @mysql_connect();
	if( !loadSQL( $sqlfile ,$_POST['db_host'], $_POST['db_user'], $_POST['db_pass']) )
	{
		$step = 4;
		$error = $language['ERROR_NO_DB_CONNECT'] . '<br />' . mysql_error();
	}
	else
	{
	    // AlertBox?
		//echo "SQL Data correctly inserted into Database!";
	}
}


/**
 * Handling of STEP 6 - Create Administrator
 * if STEP 6 successful, proceed to 7 - else return STEP 6, display error
 */
if( isset($_POST['step_forward']) AND $step == 7 )
{
	# checken, ob admin name und password vorhanden
	# wenn nicht, fehler : zurück zu STEP6
	if( !isset($_POST['admin_name']) and !isset($_POST['admin_password']) )
	{
		$step = 6;
		$error = $language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'];
	}
	else
	{
		#create admin user
	}
}

/**
 * SWITCH to Intallation-functions based on "STEPS"
 */
$installfunction  = "installstep_$step"; # add step to function name
if(function_exists($installfunction))  # check if exists
{
	# Set Step to Session
	$_SESSION['step'] = $step;
    $installfunction($language,$error); # lets rock! :P
}

# INCLUDE THE FOOTER !!
require ('install_footer.php');

##### FUNCTIONS #####

/**
 * Gets the total number of installations steps avaiable
 * by checking how many functions with name "installstep_x" exist
 *
 * @return $_SESSION['total_steps']
 */
function get_total_steps()
{
    if(isset($_SESSION['total_steps'])){return $_SESSION['total_steps'];}
    for($i=1;function_exists('installstep_'.$i)==true;$i++){$_SESSION['total_steps']=$i;}
    return $_SESSION['total_steps'];
}

/**
* Generates a random String (with divider default because needed for SALT)
*
* @return string
*/
function generate_random_string($length = 6, $with_divider = true)
{
	$chars = "ABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;  //a variable with the fixed length of chars correct for the fence post issue
	while (strlen($code) < $length)
	{
	    $code .= $chars[mt_rand(0,$clen)] . '-';  //mt_rand's range is inclusive - this is why we need 0 to n-1
	}
	$code = substr_replace($code, '', -1);
	return $code;
}

/**
 * Calculate Progress
 * is used to display install-progress in percentages
 *
 * @return float progress-value
 */
function calc_progress($this_is_step,$of_total_steps)
{
   $this_is_step--;
   return round(100/($of_total_steps-1)*$this_is_step, 0);
}

// STEP 1 - Language Selection
function installstep_1($language){    require 'install-step1.php' ;}
// STEP 2 - System Check
function installstep_2($language){    require 'install-step2.php' ;}
// STEP 3 - System Check
function installstep_3($language){    require 'install-step3.php' ;}
// STEP 4 - System Check
function installstep_4($language, $error)
{
	$values['db_host'] 		= isset($_SESSION['db_host']) ? $_SESSION['db_host'] : 'localhost';
	$values['db_name'] 		= isset($_SESSION['db_name']) ? $_SESSION['db_name'] : 'clansuite';
	$values['db_create_database']    = isset($_SESSION['db_create_database']) ? $_SESSION['db_create_database'] : '0';
	$values['db_username'] 	= isset($_SESSION['db_user']) ? $_SESSION['db_user'] : '';
	$values['db_password'] 	= isset($_SESSION['db_pass']) ? $_SESSION['db_pass'] : '';
	$values['db_prefix'] 	= isset($_SESSION['db_prefix']) ? $_SESSION['db_prefix'] : 'cs_';

	require 'install-step4.php' ;
}
// STEP 5 - System Check
function installstep_5($language)
{
	$values['site_name']  			= isset($_SESSION['site_name']) ? $_SESSION['site_name'] : 'Team Clansuite';
	$values['system_email'] 		= isset($_SESSION['system_email']) ? $_SESSION['system_email'] : 'system@website.com';
	$values['user_account_enc']  	= isset($_SESSION['user_account_enc']) ? $_SESSION['user_account_enc'] : 'SHA1';
	$values['salt']  				= isset($_SESSION['salt']) ? $_SESSION['salt'] : generate_random_string(12);
	$values['time_zone']  			= isset($_SESSION['time_zone']) ? $_SESSION['time_zone'] : '0';

	require 'install-step5.php';
}
// STEP 6 - System Check
function installstep_6($language)
{
	$values['admin_name'] 		= isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'admin';
	$values['admin_password'] 	= isset($_SESSION['admin_password']) ? $_SESSION['admin_password'] : 'admin';
	$values['admin_email'] 		= isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'admin@email.com';
	$values['admin_language'] 	= isset($_SESSION['admin_language']) ? $_SESSION['admin_language'] : 'en_EN';

	require 'install-step6.php';
}
// STEP 7 - System Check
function installstep_7($language){    require 'install-step7.php' ;}


/**
* Load an SQL stream into the database one command at a time
*
* @author     Stuart Prescott
* @copyright  Copyright Stuart Prescott
* @license    http://opensource.org/licenses/gpl-license.php GNU Public License
* @version    sqlload.php,v 1.1 2006/06/02 14:51:09 stuart
*/
function loadSQL($sql, $host, $username, $passwd) {
  #echo "Loading SQL";
  if ($connection = @ mysql_pconnect($host, $username, $passwd)) {
    // then we successfully logged on to the database
    $sqllist = preg_split('/;/', $sql);
    foreach ($sqllist as $q) {
      #echo "$q\n";
      if (preg_match('/^\s*$/', $q)) continue;
      $handle = mysql_query($q);
      if (! $handle) {
        return "ERROR: I had trouble executing SQL statement:"
              ."<blockquote>$q</blockquote>"
              ."MySQL said:<blockquote>"
              .mysql_error()
              ."</blockquote>";
      }
    }
    return true; //"SQL file loaded correctly";
  } else {
    return false; //"ERROR: Could not log on to database to load SQL file: ".mysql_error() ;
  }
}

session_write_close();
?>