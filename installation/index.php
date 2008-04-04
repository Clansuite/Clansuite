<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005-2008
    * http://www.clansuite.com/
    *
    * Clansuite Installer
    * v0.3dev 22-oktober-2007 by vain
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
    * @author     Florian Wolf <xsign.dll@clansuite.com> 2005-2006
    * @copyright  Jens-Andre Koch (2005 - $Date$)
    * @license    see COPYING.txt
    *
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */
session_start();
set_time_limit(0);

// Security Handler
define('IN_CS', true);

// Debugging Handler
define('DEBUG', false);

/**
 *  ================================================
 *     Startup Checks
 *  ================================================
 */
try
{
    # PHP Version Check
    define('REQUIRED_PHP_VERSION', '5.2');
    if (version_compare(PHP_VERSION, '5.2', '<') == true)
    {
        throw new Clansuite_Installation_Startup_Exception('Your PHP Version: <b>' . PHP_VERSION . '</b>! |
                                                            Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b> .', 1);
    }

    # PDO Check
    if(!class_exists('PDO'))
    {
        throw new Clansuite_Installation_Startup_Exception('<i>PHP_PDO</i> extension not enabled! | The Extension is needed for Database Access!', 2);
    }

    # PDO mysql driver Check
    if (!in_array('mysql', PDO::getAvailableDrivers() ))
    {
        throw new Clansuite_Installation_Startup_Exception('<i>php_pdo_mysql</i> driver not enabled. | The Extension is needed for Database Access!', 3);
    }
}
catch (Exception $e)
{
    exit($e);
}

// Get site paths
define ('CS_ROOT', getcwd() . DIRECTORY_SEPARATOR);
define ('WWW_ROOT', realpath(dirname(__FILE__)."/../")."/");

// The Clansuite version this script installs
$cs_version = '0.1';

// Define $error
$error = '';

#var_dump($_SESSION);
#var_dump($_POST);

// in case clansuite.config.php exists, exit -> can be configured from backend then
/*if (is_file( WWW_ROOT . 'clansuite.config.php'))
{
	exit('The file \'clansuite.config.php\' already exists which would mean that <strong>Clansuite</strong> '. $cs_version . ' is already installed.
	      <br /> You should visit your <a href="../index.php">site (FRONTEND)</a> or it\'s <a href="../index.php?mod=admin">admin-control-panel (ACP)</a> instead.');
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
include 'install_header.php';

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
	$step = (int) $_SESSION['step'];
	if(isset($_POST['step_forward']))  { $step++; }
	if(isset($_POST['step_backward'])) { $step--; }
	if($step >= $total_steps) { $step = $total_steps; }
}
else { $step = 1; }

# Calculate Progress
$_SESSION['progress'] = (float) calc_progress($step, $total_steps);

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
		require_once CS_ROOT . '/languages/'. $lang .'.install.php';
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
    # check if input-fields are filled
	if (isset($_POST['db_hostname']) AND isset($_POST['db_username']) AND isset($_POST['db_password']))
	{
	    # B) Write SQL-Data into Database

	    # Should we create the database?
        if (isset($_POST['db_create_database']) && $_POST['db_create_database'] == 'on')
        {
            # establish connection to database
            $db = mysql_pconnect($_POST['db_hostname'], $_POST['db_username'], $_POST['db_password']);
            #or die ("Konnte keine Verbindung zur Datenbank herstellen");

            # http://dev.mysql.com/doc/refman/5.0/en/charset-unicode-sets.html
            # so for german language there are "utf8_general_ci" or "utf8_unicode_ci"
            if (!mysql_query('CREATE DATABASE ' . $_POST['db_name'] .' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci', $db))
            {
                $step = 4;
                $error = $language['ERROR_WHILE_CREATING_DATABASE'] . '<br />' . mysql_error();
            }
        }

	    $sqlfile = CS_ROOT . '/sql/clansuite.sql';
    	if( !loadSQL( $sqlfile ,$_POST['db_hostname'], $_POST['db_name'], $_POST['db_username'], $_POST['db_password']) )
    	{
    		$step = 4;
    		$error = $language['ERROR_NO_DB_CONNECT'] . '<br />' . mysql_error();
    	}
    	else
    	{
    	    // AlertBox?
    		//echo "SQL Data correctly inserted into Database!";
    	}

    	# A)  Write Settings to clansuite.config.php
	    if( !write_config_settings($_POST))
	    {
	        $step = 4;
	        $error = 'Config not written <br />';

	    }
	    else
	    {
	        // Config written
	    }
    }
    else # input fields empty
    {
        $step = 4;
        $error = $language['ERROR_FILL_OUT_ALL_FIELDS'];
    }
}

/**
 * Handling of STEP 5 - Configuration
 * if STEP 5 successful, proceed to 6 - else return STEP 5, display error
 */
if( isset($_POST['step_forward']) AND $step == 6 )
{
	# check if input-fields are filled
	if( isset($_POST['site_name']) AND isset($_POST['system_email'])
	                               AND isset($_POST['encryption'])
	                               AND isset($_POST['salt'])
	                               AND isset($_POST['time_zone']) )
	# A)  Write Settings to clansuite.config.php
    if( !write_config_settings($_POST, true))
    {
        $step = 6;
        $error = 'Config not written <br />';
    }
    else
    {
        // Config written
    }
}

/**
 * Handling of STEP 6 - Create Administrator
 * if STEP 6 successful, proceed to 7 - else return STEP 6, display error
 */
if( isset($_POST['step_forward']) AND $step == 7 )
{
	# checken, ob admin name und password vorhanden
	# wenn nicht, fehler : zur�ck zu STEP6
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
require 'install_footer.php';

##### FUNCTIONS #####

/**
 * Gets the total number of installations steps available
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
* @params $length Length of Random String, Default = 6
* @params $with_divider default = true
* @return string
* @todo $with_divider functionality is not implemented yet (true => a-b-c  / false => abc)
*/
function generate_random_string($length = 6, $with_divider = true)
{
	$chars = "ABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
	$code = "";
	$clen = strlen($chars) - 1;  # variable with the fixed length of chars correct for the fence post issue
	while (strlen($code) < $length)
	{
	    $code .= $chars[mt_rand(0,$clen)] . '-';  # mt_rand's range is inclusive - this is why we need 0 to n-1
	}
	$code = substr_replace($code, '', -1);
	return $code;
}

/**
 * Calculate Progress
 * is used to display install-progress in percentages
 *
 * @params $this_is_step Current Step
 * @params $of_total_steps Total Number of Steps
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
	$values['encryption']  	        = isset($_SESSION['encryption']) ? $_SESSION['encryption'] : 'SHA1';
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
 * @params $sqlfile The file containing the mysql-dump data
 * @params $hostname Database Hostname
 * @params $database Database Name
 * @params $username Database Username
 * @params $password Database Password
 * @return BOOLEAN Returns true, if SQL was injected successfully
 */
function loadSQL($sqlfile, $hostname, $database, $username, $password)
{
    #echo "Loading SQL";
    if ($connection = @ mysql_pconnect($hostname, $username, $password))
    {
        # select database
        mysql_select_db($database,$connection);
        # ensure database entries are written as UTF8
        mysql_query("SET NAMES 'utf8'");

        if (!is_readable($sqlfile)) {
          die("$sqlfile does not exist or is not readable");
        }
        $queries = getQueriesFromSQLFile($sqlfile);
        for ($i = 0, $ix = count($queries); $i < $ix; ++$i) {
          $sql = $queries[$i];

          if (!mysql_query($sql, $connection)) {
            die(sprintf("error while executing mysql query #%u: %s<br />\nerror: %s", $i + 1, $sql, mysql_error()));
          }
        }
        #echo "$ix queries imported";
        return true; //"SQL file loaded correctly";
    }
    else
    {
        return false; //"ERROR: Could not log on to database to load SQL file: ".mysql_error() ;
    }
}

/**
 * getQueriesFromSQLFile
 * - strips off all comments, sql notes, empty lines from an sql file
 * - trims white-spaces
 * - filters the sql-string for sql-keywords
 * - replaces the db_prefix
 *
 * @param $file sqlfile
 * @return trimmed array of sql queries
 */
function getQueriesFromSQLFile($file)
{
    # import file line by line
    # and filter (remove) those lines, beginning with an sql comment token
    $file = array_filter(file($file),
                         create_function('$line',
                                         'return strpos(ltrim($line), "--") !== 0;'));

    # and filter (remove) those lines, beginning with an sql notes token
    $file = array_filter($file,
                         create_function('$line',
                                         'return strpos(ltrim($line), "/*") !== 0;'));

    # this is a list of SQL commands, which are allowed to follow a semicolon
    $keywords = array('ALTER', 'CREATE', 'DELETE', 'DROP', 'INSERT', 'REPLACE', 'SELECT', 'SET',
                      'TRUNCATE', 'UPDATE', 'USE');

    # create the regular expression
    $regexp = sprintf('/\s*;\s*(?=(%s)\b)/s', implode('|', $keywords));

    # split there
    $splitter = preg_split($regexp, implode("\r\n", $file));

    # remove trailing semicolon or whitespaces
    $splitter = array_map(create_function('$line',
                                          'return preg_replace("/[\s;]*$/", "", $line);'),
                          $splitter);

    # replace the database prefix
    $table_prefix = $_SESSION['db_prefix'];
    $splitter = preg_replace("/`cs_/", "`$table_prefix", $splitter);

    # remove empty lines
    return array_filter($splitter, create_function('$line', 'return !empty($line);'));
}

/**
 * Writes the Database-Settings into the clansuite.config.php
 *
 * @param $data_array
 * @return BOOLEAN true, if clansuite.config.php could be written to the ROOT
 */
function write_config_settings($data_array, $update = false)
{
    # throw non-setting vars out
    # not needed
    unset($data_array['step_forward']);
    unset($data_array['lang']);
    # handled in step 4 - section b
    unset($data_array['db_create_database']);

    # Read Config File
    if ( $update == true )
    {
        # the original one, from the root dir
        $config_file = file_get_contents( WWW_ROOT . 'clansuite.config.php');
    }
    else
    {
        # the template one, from the installtion dir
        $config_file = file_get_contents( CS_ROOT . '/clansuite.config.installer');
    }

    # Loop over Config File Data
    foreach($data_array as $key => $value)
    {
       if ($key == 'system_email') { $key = 'from'; }
       if ($key == 'site_name')    { $key = 'std_page_title'; }
       $config_file = preg_replace( '#\$this->config\[\''. $key . '\'\][\s]*\=.*\;#', '$this->config[\''. $key . '\'] = \''. $value . '\'; # inserted by installation', $config_file );
    }

    # Write Config File to ROOT Directory
    if (!file_put_contents( WWW_ROOT . 'clansuite.config.php', $config_file ))
    {
        return false;
    }
    return true;
}

// Save+Close the Session
session_write_close();

/**
 * Clansuit Exception - Installation Startup Exception
 *
 * @package clansuite
 * @category installer
 * @subpackage exceptions
 */
class Clansuite_Installation_Startup_Exception extends Exception
{
    /**
            *    Define Exceptionmessage and Code via constructor
            */
    public function __construct($message, $code = 0)
    {
        // hand it over to the Exception Class, which is parent
        parent::__construct($message, $code);
    }

    /**
        * Transform the Object to String
        */
    public function __toString()
    {
        # Header
        $errormessage    = '<p><html><head>';
        $errormessage   .= '<title>Clansuite Installation Error</title>';
        $errormessage   .= '<body>';
        $errormessage   .= '<link rel="stylesheet" href="../themes/core/css/error.css" type="text/css" />';
        $errormessage   .= '</head>';
        # Body
        $errormessage   .= '<body>';
        # Fieldset with colours (error_red, error_orange, error_beige)
        $errormessage   .= '<fieldset class="error_red">';
        $errormessage   .= '<div style="float: left; margin: 5px; margin-right: 25px; border:1px inset #bf0000; padding: 20px;">';
        $errormessage   .= '<img src="images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;"/></div>';
        # Fieldset Legend for ERRORBOX
        $errormessage   .= '<legend>Clansuite Installation Error</legend>';
        # Error String (passed Error Description)
        $errormessage   .= '<p><strong>'.$this->message.'</strong>';
        # Error Messages from the ErrorObject
        $errormessage   .= '<hr><table>';
        $errormessage   .= '<tr><td><strong>Errorcode:</strong></td><td>'.$this->getCode().'</td></tr>';
        # More Error Messages from the ErrorObj only on Debug
        if(DEBUG != false)
        {
            $errormessage   .= '<tr><td><strong>Message:</strong></td><td>'.$this->getMessage().'</td></tr>';
            $errormessage   .= '<tr><td><strong>Pfad :</strong></td><td>'. dirname($this->getFile()).'</td></tr>';
            $errormessage   .= '<tr><td><strong>Datei :</strong></td><td>'. basename($this->getFile()).'</td></tr>';
            $errormessage   .= '<tr><td><strong>Zeile :</strong></td><td>'.$this->getLine().'</td></tr>';
        }
        $errormessage   .= '</table>';
        $errormessage   .= '</fieldset><br />';
        # Fieldset Legend for HELPBOX
        $errormessage   .= '<fieldset class="error_beige">';
        $errormessage   .= '<legend>Help</legend>';
        $errormessage   .= "<br />1) Please use <a href=\"phpinfo.php\">phpinfo()</a> to check your serversettings! ";
        $errormessage   .= "<br />2) Check your php.ini and ensure all needed extensions/libraries are loaded!";
        $errormessage   .= "<br />3) Check the webservers errorlog.<br/>";
        $errormessage   .= "<br />If you can't solve the error yourself, feel free to contact us at our website's <a href=\"http://www.clansuite.com/smf/index.php?board=22.0\">Installation - Support Forum</a>.<br/>";
        $errormessage   .= '</fieldset>';
        # FOOTER
        $errormessage   .= '</body></html>';

        #return __CLASS__ . " {$errormessage}";
        return $errormessage;
    }
}
?>