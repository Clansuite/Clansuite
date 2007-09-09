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

#echo 'get:';        var_dump($_GET);
echo 'post:';       var_dump($_POST);
echo 'session:';    var_dump($_SESSION);

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
include ('install-header.php');

/**
 * ==============================
 *    STEP HANDLING + PROGRESS
 * ==============================
 */
 # Get Total Steps and if we are at max_steps, set step to max
$total_steps = get_total_steps();

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
	$db = @mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_pass']);
	if( !$db )
	{
		$step = 4;
		$error = $language['ERROR_NO_DB_CONNECT'];	
	}
	else
	{
		#splitten und einfügen
		
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
require ('install-footer.php');

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
	$values['db_host'] 		= isset($_POST['db_host']) ? $_POST['db_host'] : 'localhost';
	$values['db_name'] 		= isset($_POST['db_name']) ? $_POST['db_name'] : 'clansuite';
	$values['db_username'] 	= isset($_POST['db_username']) ? $_POST['db_username'] : '';
	$values['db_password'] 	= isset($_POST['db_password']) ? $_POST['db_password'] : '';
	$values['db_prefix'] 	= isset($_POST['db_prefix']) ? $_POST['db_prefix'] : 'cs_';

	require 'install-step4.php' ;
}
// STEP 5 - System Check
function installstep_5($language)
{
	$values['site_name']  	= isset($_POST['site_name']) ? $_POST['site_name'] : 'Team Clansuite';
	$values['system_email'] = isset($_POST['system_email']) ? $_POST['system_email'] : 'system@website.com';
	$values['user_account_enc']  	= isset($_POST['user_account_enc']) ? $_POST['user_account_enc'] : 'SHA1';
	$values['salting']  	= isset($_POST['salting']) ? $_POST['salting'] : 'SALT';
	$values['time_zone']  	= isset($_POST['time_zone']) ? $_POST['time_zone'] : 'Berlin +1';

	require 'install-step5.php';
}
// STEP 6 - System Check
function installstep_6($language)
{    
	$values['admin_name'] 		= isset($_POST['admin_name']) ? $_POST['admin_name'] : 'admin';
	$values['admin_password'] 	= isset($_POST['admin_password']) ? $_POST['admin_password'] : 'admin';
	$values['admin_email'] 		= isset($_POST['admin_email']) ? $_POST['admin_email'] : 'admin@email.com';
	$values['admin_language'] 	= isset($_POST['admin_language']) ? $_POST['admin_language'] : 'en_EN';

	require 'install-step6.php';
}
// STEP 7 - System Check
function installstep_7($language){    require 'install-step7.php' ;}

#echo 'Debug Language Values';
#var_dump($language);
session_write_close()
?>