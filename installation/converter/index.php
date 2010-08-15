<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * Clansuite Converter - v0.1dev - 21-september-2008 by vain
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @category    Clansuite
    * @package     Installation
    * @subpackage  Converter
    *
    * @version    SVN: $Id$
    */

session_start('clansuite_converter');

# Security Handler
define('IN_CS', true);

// Debugging Handler
define('DEBUG', true);

set_time_limit(0);

/**
 * Suppress Errors and use E_STRICT when Debugging
 * E_STRICT forbids the shortage of "<?php echo$language->XY ?>" to "<?php echo $language->XY ?>"
 * so we use E_ALL when DEBUGING. This is just an installer btw :)
 */
$boolean = true;

if(DEBUG == false)
{
    $boolean = false;
}
else
{
    $boolean = true;
}

ini_set('display_startup_errors', $boolean);
ini_set('display_errors', $boolean);
error_reporting($boolean);

// Define: DS; ROOT; BASE_ROOT
define('DS', DIRECTORY_SEPARATOR);
define('CONVERTER_ROOT', getcwd() . DS);
define('ROOT', dirname(dirname(getcwd())) . DS);

echo 'P,G,R,S';
#var_dump($_POST);
#var_dump($_GET);
#var_dump($_REQUEST);
#var_dump($_SESSION);

/**
 *  ================================================
 *     Startup Checks
 *  ================================================
 */
# PHP Version Check
define('REQUIRED_PHP_VERSION', '5.2');
if(version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<') == true)
{
    $e = new Clansuite_Converter_Exception('Your PHP Version: <b>' . PHP_VERSION . '</b>! |
                                                    Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b> .', 1);
    exit($e);
}

# Check for Configuration File
if(false == is_file(ROOT . '/configuration/clansuite.config.php'))
{
    $e = new Clansuite_Converter_Exception("<i>Configuration file not found!</i> | Ensure that Clansuite is installed properly and clansuite.config.php resides in maindirectory! The file is needed for Database Access!", 2);
    exit($e);
}


// The Clansuite version this script installs
require ROOT . '/core/clansuite.version.php';
define('CONVERTER_VERSION', '0.1');

// Define $error
$error = '';


#========================
#      SELF DELETION
#========================

if(isset($_GET['delete_converter']))
{
    rm_recursive(getcwd());
}

#================================
#    STEP HANDLING + PROGRESS
#================================
# Get Total Steps and if we are at max_steps, set step to max
$total_steps = get_total_steps();

# Update the session with the given variables!
$_SESSION = array_merge_rec($_SESSION, $_POST);

# STEP HANDLING
if(isset($_SESSION['step']))
{
    $step = (int) intval($_SESSION['step']);
    if(isset($_POST['step_forward']))
    {
        $step++;
    }
    if(isset($_POST['step_backward']))
    {
        $step--;
    }
    if($step >= $total_steps)
    {
        $step = $total_steps;
    }
}
else
{
    $step = 1;
}

# Calculate Progress
$_SESSION['progress'] = (float) calc_progress($step, $total_steps);

/**
 * ===========================
 *    Language Handling
 * ===========================
 */
# Get language from GET
if(isset($_GET['lang']) && ! empty($_GET['lang']))
{
    $lang = (string) htmlspecialchars($_GET['lang']);
}
else
{
    # Get language from SESSION
    if(isset($_SESSION['lang']))
    {
        $lang = $_SESSION['lang'];
    }

    # SET DEFAULT LANGUAGE VAR
    if($step == 1 OR empty($_SESSION['lang']))
    {
        $lang = 'german';
    }
}

/**
 * ===========================
 *    Language File Include
 * ===========================
 */
try
{
    $file = ROOT . 'languages' . DS . $lang . '.converter.php';
    if(is_file($file) === true)
    {
        require_once $file;
        $language = new language;
        $_SESSION['lang'] = $lang;
    }
    else
    {
        throw new Clansuite_Converter_Exception('<span style="color:red">Language file missing: <strong>' . $file . '</strong></span>');
    }
}
catch(Exception $e)
{
    exit($e);
}

#=======================
#      START OUTPUT
#=======================
# INCLUDE THE HEADER!
include 'converter_header.php';

# INCLUDE THE MENU!
include 'converter_menu.php';

/***
 * ===============================================
 *      Handling of Installations STEPS
 * ===============================================
 *
 * Procedure Notice:
 * if a STEP is successful, procedd to next else return to same STEP and display error
 */

/**
 * ===================================================
 *      Handling of Converter STEPS
 * ===================================================
 */
# Handling of Step 1 = None Language is set to Session
# Handling of Step 2
# Handling of Step 3
# Handling of Step 4
# Handling of Step 5
# Handling of Step 6
# Handling of Step 7


/**
 * =========================================================
 *      SWITCH to Intallation-functions based on "STEPS"
 * =========================================================
 */
# add step to function name
$converterfunction = "converterstep_$step";

# check if this functionname exists
if(function_exists($converterfunction))
{
    # set this step to the session
    $_SESSION['step'] = $step;
    # lets rock! :P
    $converterfunction($language, $error);
}

#=======================
#      END OUTPUT
#=======================
# INCLUDE THE FOOTER !
require 'converter_footer.php';

#=======================================
#           PAGE IS DISPLAYED
#=======================================
# --------------  EOF  -----------------

/**
 * ===================
 * Installer Functions
 * ===================
 */

/**
 * Array Merge Recursive
 *
 * @param $arr1 array
 * @param $arr2 array
 * @return recusrive merged array
 */
function array_merge_rec($arr1, $arr2)
{
    foreach($arr2 as $k => $v)
    {
        if(!array_key_exists($k, $arr1))
        {
            $arr1[$k] = $v;
        }
        else
        {
            if(is_array($v))
            {
                $arr1[$k] = array_merge_rec($arr1[$k], $arr2[$k]);
            }
        }
    }
    return $arr1;
}

/**
 * Gets the total number of installations steps available
 * by checking how many functions with name "converterstep_x" exist
 *
 * @return $_SESSION['total_steps']
 */
function get_total_steps()
{
    if(isset($_SESSION['total_steps']))
    {return $_SESSION['total_steps'];    }
    for($i = 1; function_exists('converterstep_' . $i)==true; $i++)
    {
        $_SESSION['total_steps'] = $i;
    }
    return $_SESSION['total_steps'];
}

/**
 * This functions takes a clear (password) string and prefixes a random string called
 * "salt" to it. The new combined "salt+password" string is then passed to the hashing
 * method to get an hash return value.
 * So what’s stored in the database is Hash(password, users_salt).
 *
 * Why salting? 2 Reasons:
 * 1) Make Dictionary Attacks (pre-generated lists of hashes) useless
 *    The dictionary has to be recalculated for every account.
 * 2) Using a salt fixes the issue of multiple user-accounts having the same password
 *    revealing themselves by identical hashes. So in case two passwords would be the
 *    same, the random salt makes the difference while creating the hash.
 *
 * @param string A clear-text string, like a password "JohnDoe$123"
 *
 * @return $hash is an array, containing ['salt'] and ['hash']
 */
function build_salted_hash($string = '', $hash_algo = '')
{
    # set up the array
    $salted_hash_array = array();
    # generate the salt with fixed length 6 and place it into the array
    $salted_hash_array['salt'] = generate_salt(6);
    # combine salt and string
    $salted_string = $salted_hash_array['salt'] . $string;
    # generate hash from "salt+string" and place it into the array
    $salted_hash_array['hash'] = generate_hash($hash_algo, $salted_string);
    # return array with elements ['salt'], ['hash']
    return $salted_hash_array;
}

/**
 * This is security.class.php -> method generate_salt().
 *
 * Get random string/salt of size $length
 * mt_srand() && mt_rand() are used to generate even better
 * randoms, because of mersenne-twisting.
 *
 * @param integer $length Length of random string to return
 *
 * @return string Returns a string with random generated characters and numbers
 */
function generate_salt($length)
{
    # set salt to empty
    $salt = '';

    # seed the randoms generator with microseconds since last "whole" second
    mt_srand((double) microtime() * 1000000);

    # set up the random chars to choose from
    $chars = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    # count the number of random_chars
    $number_of_random_chars = mb_strlen($chars);

    # add a char from the random_chars to the salt, until we got the wanted $length
    for($i = 0; $i < $length; ++$i)
    {
        # get a random char of $chars
        $char_to_add = $chars[mt_rand(0, $number_of_random_chars)];

        # ensure that a random_char is not used twice in the salt
        if(!strstr($salt, $char_to_add))
        {
            # finally => add char to salt
            $salt .= $char_to_add;
        }
    }
    return $salt;
}

/**
 * This function generates a HASH of a given string using the requested hash_algorithm.
 * When using hash() we have several hashing algorithms like: md5, sha1, sha256 etc.
 * To get a complete list of available hash encodings use: print_r(hash_algos());
 * When it's not possible to use hash() for any reason, we use "md5" and "sha1".
 *
 * @link http://www.php.net/manual/en/ref.hash.php
 *
 * @param $string String to build a HASH from
 * @param $hash_type Encoding to use for the HASH (sha1, md5) default = sha1
 *
 * @return hashed string
 */
function generate_hash($hash_algo = null, $string = '')
{
    # Get Config Value for Hash-Algo/Encryption
    if($hash_algo == null)
    {
        $hash_algo = $_SESSION['encryption'];
    }

    # check, if we can use hash()
    if(function_exists('hash'))
    {
        return hash($hash_algo, $string);
    }
    else
    {   # when hash() not available, do hashing the old way
        switch($hash_algo)
        {
            case 'MD5': return md5($string);
                break;
            default:
            case 'SHA1': return sha1($string);
                break;
        }
    }
}

/**
 * Calculate Progress
 * is used to display install-progress in percentages
 *
 * @params $this_is_step Current Step
 * @params $of_total_steps Total Number of Steps
 *
 * @return float progress-value
 */
function calc_progress($this_is_step, $of_total_steps)
{
    $this_is_step--;
    return round(100 / ($of_total_steps - 1) * $this_is_step, 0);
}

// STEP 1 - Language Selection
function converterstep_1($language)
{
    include 'converter-step1.php';
}

// STEP 2 - System Check
function converterstep_2($language)
{
    include 'converter-step2.php';
}

// STEP 3 - System Check
function converterstep_3($language)
{
    include 'converter-step3.php';
}

// STEP 4 - System Check
function converterstep_4($language, $error)
{
    $values['db_host'] = isset($_SESSION['db_host']) ? $_SESSION['db_host'] : 'localhost';
    $values['db_type'] = isset($_SESSION['db_type']) ? $_SESSION['db_type'] : 'mysql';
    $values['db_name'] = isset($_SESSION['db_name']) ? $_SESSION['db_name'] : 'clansuite';
    $values['db_create_database'] = isset($_SESSION['db_create_database']) ? $_SESSION['db_create_database'] : '0';
    $values['db_username'] = isset($_SESSION['db_user']) ? $_SESSION['db_user'] : '';
    $values['db_password'] = isset($_SESSION['db_pass']) ? $_SESSION['db_pass'] : '';
    $values['db_prefix'] = isset($_SESSION['db_prefix']) ? $_SESSION['db_prefix'] : 'cs_';

    include 'converter-step4.php';
}

function converterstep_5($language)
{
    include 'converter-step5.php';
}

function converterstep_6($language)
{
    include 'converter-step6.php';
}

/**
 * Load an SQL stream into the database one command at a time
 *
 * @params $sqlfile The file containing the mysql-dump data
 * @params $hostname Database Hostname
 * @params $database Database Name
 * @params $username Database Username
 * @params $password Database Password
 *
 * @return BOOLEAN Returns true, if SQL was injected successfully
 */
function loadSQL($sqlfile, $hostname, $database, $username, $password)
{
    #echo"Loading SQL";
    if($connection = @ mysql_pconnect($hostname, $username, $password))
    {
        # select database
        mysql_select_db($database, $connection);
        # ensure database entries are written as UTF8
        mysql_query("SET NAMES 'utf8'");

        if(!is_readable($sqlfile))
        {
            die($sqlfile . 'does not exist or is not readable');
        }
        $queries = getQueriesFromSQLFile($sqlfile);
        for($i = 0, $ix = count($queries); $i < $ix; ++$i)
        {
            $sql = $queries[$i];

            if(!mysql_query($sql, $connection))
            {
                die(sprintf("error while executing mysql query #%u: %s<br />\nerror: %s", $i + 1, $sql, mysql_error()));
            }
        }
        #echo"$ix queries imported";
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
 *
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

    # this is a whitelist of SQL commands, which are allowed to follow a semicolon
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
    $table_prefix = $_POST['config']['database']['db_prefix'];
    $splitter = preg_replace("/`cs_/", "`$table_prefix", $splitter);

    # remove empty lines
    return array_filter($splitter, create_function('$line', 'return !empty($line);'));
}

/**
 * Writes the Database-Settings into the clansuite.config.php
 *
 * @param $data_array
 *
 * @return BOOLEAN true, if clansuite.config.php could be written to the ROOT
 *
 */
function write_config_settings($data_array)
{
    include ROOT . 'core/clansuite_config.class.php';

    # throw not needed / non-setting vars out
    unset($data_array['step_forward']);
    unset($data_array['lang']);

    #var_dump($data_array);
    # read skeleton settings = minimum settings for initial startup
    # (not asked from user during installation, but required paths/defaultactions etc)
    $installer_config = Clansuite_Config::readConfig(CONVERTER_ROOT . 'clansuite.config.converter');

    #var_dump($installer_config);
    # array merge: overwrite the array to the left, with the array to the right, when keys identical
    $data_array = array_merge_recursive($data_array, $installer_config);
    #var_dump($data_array);
    # Write Config File to ROOT Directory
    #echoROOT . 'clansuite.config.php';
    if(!Clansuite_Config::writeConfig(ROOT . 'clansuite.config.php', $data_array))
    {
        return false;
    }
    return true;
}

/**
 * rm_recursive
 *
 * @description Remove recursively. (Like `rm -r`) AND uses opendir() instead of glob()
 * @see Comment by davedx at gmail dot com on { http://us2.php.net/manual/en/function.rmdir.php }
 * @param file {String} The file or folder to be deleted.
 */
function rm_recursive($filepath)
{
    echo '<p>[Deleting Installation Directory] Starting at ' . $filepath . '</p>';

    if(is_dir($filepath) && ! is_link($filepath))
    {
        if($dh = opendir($filepath))
        {
            while(($sf = readdir($dh)) !== false)
            {
                # handle . and ..
                if($sf == '.' || $sf == '..')
                {
                    continue;
                }
                else
                {
                    # handle files
                    if(unlink($filepath . DS . $sf))
                    {
                        echo 'File ' . $filepath . DS . $sf . ' deleted successfully.<br />';
                    }
                    else
                    {
                        echo 'Unable to delete file ' . $filepath . DS . $sf . '.<br />';
                    }
                }

                # handle dirs recursivly
                if(!rm_recursive($filepath . DS . $sf))
                {
                    throw new Exception($filepath . DS . $sf . ' could not be deleted.');
                }
            }
            closedir($dh);
        }
        return rmdir($filepath);
    }
    return unlink($filepath);
}
// Save+Close the Session
session_write_close();

/**
 * Clansuit Exception - Installation Startup Exception
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Converter
 */
class Clansuite_Converter_Exception extends Exception
{

    /**
     *    Define Exceptionmessage && Code via constructor
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
        $errormessage = '<p><html><head>';
        $errormessage .= '<title>Clansuite Converter - Error</title>';
        $errormessage .= '<body>';
        $errormessage .= '<link rel="stylesheet" href="../themes/core/css/error.css" type="text/css" />';
        $errormessage .= '</head>';
        # Body
        $errormessage .= '<body>';
        # Fieldset with colours (error_red, error_orange, error_beige)
        $errormessage .= '<fieldset class="error_red">';
        $errormessage .= '<div style="float: left; margin: 5px; margin-right: 25px; border:1px inset #bf0000; padding: 20px;">';
        $errormessage .= '<img src="../images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;"/></div>';
        # Fieldset Legend for ERRORBOX
        $errormessage .= '<legend>Clansuite Converter - Error</legend>';
        # Error String (passed Error Description)
        $errormessage .= '<p><strong>' . $this->message . '</strong>';
        # Error Messages from the ErrorObject
        $errormessage .= '<hr><table>';
        $errormessage .= '<tr><td><strong>Errorcode:</strong></td><td>' . $this->getCode() . '</td></tr>';
        # More Error Messages from the ErrorObj only on Debug
        if(DEBUG != false)
        {
            $errormessage .= '<tr><td><strong>Message:</strong></td><td>' . $this->getMessage() . '</td></tr>';
            $errormessage .= '<tr><td><strong>Pfad :</strong></td><td>' . dirname($this->getFile()) . '</td></tr>';
            $errormessage .= '<tr><td><strong>Datei :</strong></td><td>' . basename($this->getFile()) . '</td></tr>';
            $errormessage .= '<tr><td><strong>Zeile :</strong></td><td>' . $this->getLine() . '</td></tr>';
        }
        $errormessage .= '</table>';
        $errormessage .= '</fieldset><br />';
        # Fieldset Legend for HELPBOX
        $errormessage .= '<fieldset class="error_beige">';
        $errormessage .= '<legend>Help</legend>';
        $errormessage .= "If you can't solve the error yourself, feel free to contact us at our website's <a href=\"http://forum.clansuite.com/index.php?board=26.0\">Converter - Support Forum</a>.<br/>";
        $errormessage .= '</fieldset>';
        # FOOTER
        $errormessage .= '</body></html>';

        #return __CLASS__ . " {$errormessage}";
        return $errormessage;
    }
}
?>