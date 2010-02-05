<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @author     Florian Wolf <xsign.dll@clansuite.com> 2005-2006
    * @copyright  Florian Wolf (2005-2006)
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */
session_start();

@set_time_limit(0);

# Security Handler
define('IN_CS', true);

# Debugging Handler
define('DEBUG', true);

/**
 * Suppress Errors and use E_STRICT when Debugging
 * E_STRICT forbids the shortage of "<?php print $language->XY ?>" to "<?=$language->XY ?>"
 * so we use E_ALL when DEBUGING. This is just an installer btw :)
 */
ini_set('display_startup_errors', true);
ini_set('display_errors', true);
error_reporting(E_ALL | E_STRICT);

#var_dump($_SESSION);
#var_dump($_POST);

/**
 *  ================================================
 *     Startup Checks
 *  ================================================
 */
# PHP Version Check
define('REQUIRED_PHP_VERSION', '5.2');
if (version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<') == true)
{
    $e = new Clansuite_Installation_Startup_Exception('Your PHP Version: <b>' . PHP_VERSION . '</b>! |
                                                        Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b> .', 1);
    exit($e);
}

try
{
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

// Define: DS; INSTALLATION_ROOT; ROOT
define ('DS', DIRECTORY_SEPARATOR);
define ('INSTALLATION_ROOT', getcwd() . DS);
define ('ROOT', dirname(getcwd()) . DS);

// The Clansuite version this script installs
require ROOT . 'core/bootstrap/clansuite.version.php';

// Define $error
$error = '';

// in case clansuite.config.php exists, exit -> can be configured from backend then
/*if (is_file( ROOT . 'configuration/clansuite.config.php' ))
{
    exit('The file <strong>/configuration/clansuite.config.php</strong> already exists! This indicates that
          <strong>Clansuite '. $cs_version . ' '. $clansuite_version_state .' ('.$clansuite_version_name .')</strong> is already installed.
          <br /> You should visit your Websites <a href="../index.php">Frontend</a>
          or it\'s  <a href="../index.php?mod=controlcenter">Controlcenter (CC)</a> instead.');
}*/

#========================
#      SELF DELETION
#========================

if(isset($_GET['delete_installation'])) { removeDirectory(getcwd()); }


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
    if(isset($_POST['step_forward']))  { $step++; }
    if(isset($_POST['step_backward'])) { $step--; }
    if($step >= $total_steps) { $step = $total_steps; }
}
else { $step = 1; }

# Calculate Progress
$_SESSION['progress'] = (float) calc_progress($step, $total_steps);

/**
 * ===========================
 *    Language Handling
 * ===========================
 */

# Get language from GET
if (isset($_GET['lang']) && !empty($_GET['lang']))
{
   $lang =  (string) htmlspecialchars($_GET['lang']);
}
else
{
    # Get language from SESSION
    if(isset($_SESSION['lang']))
    {
        $lang = $_SESSION['lang'];
    }

    # SET DEFAULT LANGUAGE VAR
    if ($step == 1 OR empty( $_SESSION['lang']))
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
    $file = INSTALLATION_ROOT . 'languages'. DS . $lang .'.install.php';
    if (is_file ($file))
    {
        require_once $file;
        $language = new language;
        $_SESSION['lang'] = $lang;
    }
    else
    {
        throw new Clansuite_Installation_Startup_Exception('<span style="color:red">Language file missing: <strong>' . $file . '</strong></span>');
    }
}
catch (Exception $e)
{
    exit($e);
}

#=======================
#      START OUTPUT
#=======================

# INCLUDE THE HEADER!
include 'install_header.php';

# INCLUDE THE MENU!
include 'install_menu.php';

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
 *      Handling of Installation STEP 4 - Database
 * ===================================================
 */
if( isset($_POST['step_forward']) && $step == 5 )
{
    # check if input-fields are filled
    if (!empty($_POST['config']['database']['name']) &&
        !ctype_digit($_POST['config']['database']['name']) &&
        preg_match('#^[a-zA-Z0-9]{1,}[a-zA-Z0-9_\-@]+[a-zA-Z0-9_\-@]*$#', $_POST['config']['database']['name'] ) &&
        !empty($_POST['config']['database']['host']) &&
        !empty($_POST['config']['database']['type']) &&
        !empty($_POST['config']['database']['username']) &&
         isset($_POST['config']['database']['password'])
       )
    {

        /**
         * 1. Check if Connection Data is valid (establish db connection)
         */

        $db_connection = '';
        $db_connection = @mysql_pconnect($_POST['config']['database']['host'],
                                         $_POST['config']['database']['username'],
                                         $_POST['config']['database']['password']);
        if ($db_connection == false)
        {
            $step = 4;
            $error = 'Konnte keine Verbindung zur Datenbank herstellen. Host, User+PW pr�fen.' . '<br />' . mysql_error();
        }

        /**
         * 2. create the database?
         *
         * http://dev.mysql.com/doc/refman/5.0/en/charset-unicode-sets.html
         * so for german language there are "utf8_general_ci" or "utf8_unicode_ci"
         */

        if (isset($_POST['config']['database']['create_database']) &&
            $_POST['config']['database']['create_database'] == 'on')
        {
            if (!@mysql_query('CREATE DATABASE ' . $_POST['config']['database']['name'] .' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci', $db_connection))
            {
                $step = 4;
                $error = $language['ERROR_WHILE_CREATING_DATABASE'] . '<br />' . mysql_error();
            }

            # remove create_database from $_POST Config array
            # this shouldn't be written in the config file
            unset($_POST['config']['database']['create_database']);
        }

        /**
         * 3. Check if database is selectable
         */

        if (!@mysql_select_db($_POST['config']['database']['name']))
        {
            $step = 4;
            $error = 'Datenbanktabelle konnte nicht selektiert werden. Entweder falsch benannt oder momentan nicht erreichbar.' . '<br />' . mysql_error();
        }

        /**
         * 4. Insert SQL Data
         */

        $sqlfile = INSTALLATION_ROOT . 'sql/clansuite.sql';
        if( !loadSQL( $sqlfile , $_POST['config']['database']['host'],
                                 $_POST['config']['database']['name'],
                                 $_POST['config']['database']['username'],
                                 $_POST['config']['database']['password']) )
        {
            $step = 4;
            $error = $language['ERROR_NO_DB_CONNECT'] . '<br />' . mysql_error();
        }
        else # sql inserted correctly into database
        {
            # 5. Write Settings to clansuite.config.php
            if( !write_config_settings($_POST['config']))
            {
                $step = 4;
                $error = 'Config not written <br />';

            }
            else # config written
            {
            } # end if: 5. insert SQL Data

        } # end if: 4. insert SQL Data
    }
    else # input fields empty
    {
        $step = 4;
        $error = $language['ERROR_FILL_OUT_ALL_FIELDS'];
        # Adjust Error-Message in case validity of database name FAILED
        if( isset($_POST['config']['database']['name']) && preg_match('#^[a-zA-Z0-9]{1,}[a-zA-Z0-9_\-@]+[a-zA-Z0-9_\-@]*$#', $_POST['config']['database']['name'] ))
        {
            $error .= '<p>The database name you have entered, "'. $_POST['config']['database']['name'] . '", is invalid.</p>';
            $error .= '<p> It can only contain alphanumeric characters, periods, or underscores. Usable Chars: A-Z;a-z;0-9;-;_ </p>';
            $error .= '<p> Forbidden are database names containing only numbers and names like mysql-database commands.</p>';
        }
    }
}

/**
 * ========================================================
 *      Handling of Installation STEP 5 - Configuration
 * ========================================================
 */
if( isset($_POST['step_forward']) && $step == 6 )
{
    #var_dump($_SESSION);
    # check if input-fields are filled
    if( isset($_POST['config']['template']['pagetitle']) &&
        isset($_POST['config']['email']['from']) &&
        isset($_POST['config']['language']['timezone']) )
    {
        $array_to_write = array();
        $array_to_write = $_POST['config'];
        $array_to_write['language']['gmtoffset'] = (int) $_POST['config']['language']['timezone'];
        $array_to_write['language']['timezone']  = (string) timezone_name_from_abbr('', $_POST['config']['language']['timezone'], 0);

        # write Settings to clansuite.config.php
        if( !write_config_settings($array_to_write))
        {
            $step = 5;
            $error = 'Config not written <br />';
        }
        else
        {
            // Config written
        }
    }
    else # input fields empty
    {
        $step = 5;
        $error = $language['ERROR_FILL_OUT_ALL_FIELDS'];
    }
}

/**
 * ========================================================
 *      Handling of Installation STEP 6 - Create Administrator
 * ========================================================
 */
if( isset($_POST['step_forward']) && $step == 7 )
{
    # checken, ob admin name und password vorhanden
    # wenn nicht, fehler : zur�ck zu STEP6
    if( !isset($_POST['admin_name']) && !isset($_POST['admin_password']) )
    {
        $step = 6;
        $error = $language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'];
    }
    else
    {
        # @todo Check mysql connect
        $db = @mysql_pconnect($_SESSION['config']['database']['host'], $_SESSION['config']['database']['username'], $_SESSION['config']['database']['password']);
        // Generate activation code & salted hash
        $hashArr = build_salted_hash($_POST['admin_password'], $_SESSION['encryption']);
        $hash = $hashArr['hash'];
        $salt = $hashArr['salt'];

        // Insert User to DB
        # @todo check mysql insert
        $result = @mysql_query('INSERT INTO '.$_SESSION['config']['database']['prefix'].'users SET
                                email= \'' . $_POST['admin_email'] . '\',
                                nick= \'' .$_POST['admin_name']. '\',
                                passwordhash = \'' .$hash. '\',
                                salt = \'' . $salt . '\',
                                joined = \'' . time() . '\',
                                language = \'' . $_SESSION['admin_language'] . '\',
                                activated = 1');
    }
}

/**
 * =========================================================
 *      SWITCH to Intallation-functions based on "STEPS"
 * =========================================================
 */

# add step to function name
$installfunction  = "installstep_$step";

# check if this functionname exists
if(function_exists($installfunction))
{
    # set this step to the session
    $_SESSION['step'] = $step;
    # lets rock! :P
    $installfunction($language,$error);
}

#=======================
#      END OUTPUT
#=======================

# INCLUDE THE FOOTER !
require INSTALLATION_ROOT.'install_footer.php';

#===========================
#      PAGE IS DISPLAYED
#===========================

# EOF

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
    foreach($arr2 as $k=>$v)
    {
        if (!array_key_exists($k, $arr1))
        {
            $arr1[$k]=$v;
        }
        else
        {
            if (is_array($v))
            {
                $arr1[$k]=array_merge_rec($arr1[$k], $arr2[$k]);
            }
        }
    }
    return $arr1;
}

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
 * This functions takes a clear (password) string and prefixes a random string called
 * "salt" to it. The new combined "salt+password" string is then passed to the hashing
 * method to get an hash return value.
 * So what�s stored in the database is Hash(password, users_salt).
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
function build_salted_hash( $string = '', $hash_algo = '')
{
    # set up the array
    $salted_hash_array = array();
    # generate the salt with fixed length 6 and place it into the array
    $salted_hash_array['salt'] = generate_salt(6);
    # combine salt and string
    $salted_string =  $salted_hash_array['salt'] . $string;
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
    mt_srand((double)microtime()*1000000);

    # set up the random chars to choose from
    $chars = "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    # count the number of random_chars
    $number_of_random_chars = strlen($chars);

    # add a char from the random_chars to the salt, until we got the wanted $length
    for ($i=0; $i<$length; ++$i)
    {
        # get a random char of $chars
        $char_to_add = $chars[mt_rand(0,$number_of_random_chars)];

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
    if (function_exists('hash'))
    {
        return hash($hash_algo,$string);
    }
    else
    {   # when hash() not available, do hashing the old way
        switch($hash_algo)
        {
            case 'MD5':     return md5($string);
                            break;
            default:
            case 'SHA1':    return sha1($string);
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
 * @return float progress-value
 */
function calc_progress($this_is_step,$of_total_steps)
{
   $this_is_step--;
   return round(100/($of_total_steps-1)*$this_is_step, 0);
}

// STEP 1 - Language Selection
function installstep_1($language){    require INSTALLATION_ROOT.'install-step1.php' ;}
// STEP 2 - System Check
function installstep_2($language){    require INSTALLATION_ROOT.'install-step2.php' ;}
// STEP 3 - System Check
function installstep_3($language){    require INSTALLATION_ROOT.'install-step3.php' ;}
// STEP 4 - System Check
function installstep_4($language, $error)
{
    $values['host']      = isset($_SESSION['host']) ? $_SESSION['host'] : 'localhost';
    $values['type']      = isset($_SESSION['type']) ? $_SESSION['type'] : 'mysql';
    $values['name']      = isset($_SESSION['name']) ? $_SESSION['name'] : 'clansuite';
    $values['create_database']    = isset($_SESSION['create_database']) ? $_SESSION['create_database'] : '0';
    $values['username']  = isset($_SESSION['user']) ? $_SESSION['user'] : '';
    $values['password']  = isset($_SESSION['pass']) ? $_SESSION['pass'] : '';
    $values['prefix']    = isset($_SESSION['prefix']) ? $_SESSION['prefix'] : 'cs_';

    require INSTALLATION_ROOT.'install-step4.php' ;
}
// STEP 5 - System Check
function installstep_5($language)
{
    $values['pagetitle']      = isset($_SESSION['pagetitle']) ? $_SESSION['pagetitle'] : 'Team Clansuite';
    $values['from']                = isset($_SESSION['from']) ? $_SESSION['from'] : 'system@website.com';
    $values['timezone']            = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : '0';
    $values['encryption']          = isset($_SESSION['encryption']) ? $_SESSION['encryption'] : 'SHA1';

    require INSTALLATION_ROOT.'install-step5.php';
}
// STEP 6 - System Check
function installstep_6($language)
{
    $values['admin_name']       = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'admin';
    $values['admin_password']   = isset($_SESSION['admin_password']) ? $_SESSION['admin_password'] : 'admin';
    $values['admin_email']      = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'admin@email.com';
    $values['admin_language']   = isset($_SESSION['admin_language']) ? $_SESSION['admin_language'] : 'en_EN';

    require 'install-step6.php';
}
// STEP 7 - System Check
function installstep_7($language){    require INSTALLATION_ROOT.'install-step7.php' ;}


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
    #print "Loading SQL";
    if ($connection = @mysql_pconnect($hostname, $username, $password))
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
        #print "$ix queries imported";
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
    $table_prefix = $_POST['config']['database']['prefix'];
    $splitter = preg_replace("/`cs_/", "`$table_prefix", $splitter);

    # remove empty lines
    return array_filter($splitter, create_function('$line', 'return !empty($line);'));
}

/**
 * Writes the Database-Settings into the clansuite.config.php
 *
 * @param $data_array
 * @return BOOLEAN true, if clansuite.config.php could be written to the INSTALLATION_ROOT
 *
 */
function write_config_settings($data_array)
{
    # Read/Write Handler for Configfiles
    require ROOT . 'core/config/ini.config.php';

    # throw not needed / non-setting vars out
    unset($data_array['step_forward']);
    unset($data_array['lang']);

    #var_dump($data_array);

    # read skeleton settings = minimum settings for initial startup
    # (not asked from user during installation, but required paths/defaultactions etc)
    $installer_config = Clansuite_Config_INIHandler::readConfig( INSTALLATION_ROOT . 'clansuite.config.installer');

    #var_dump($installer_config);

    # array merge: overwrite the array to the left, with the array to the right, when keys identical
    $data_array = array_merge_recursive($data_array, $installer_config);
    #var_dump($data_array);

    # Write Config File to ROOT Directory
    #print ROOT . 'clansuite.config.php';
    if ( !Clansuite_Config_INIHandler::writeConfig( ROOT . '/configuration/clansuite.config.php', $data_array) )
    {
        return false;
    }
    return true;
}

/**
 * removeDirectory
 *
 * @description Remove a directory and all it files recursively.
 * @param file {String} The file or folder to be deleted.
 **/
function removeDirectory($dir)
{
    echo "[Deleting Installation Directory] Starting at $dir<br/>";

    # get files
    $files = glob( $dir . '*', GLOB_MARK );
    foreach( $files as $file )
    {
        # skip the index.php
        #if( strpos( 'installation'.DIRECTORY_SEPARATOR.'index.php', $file ) !== FALSE )
        #{
        #    continue;
        #}

        # skip dirs
        if( is_dir( $file ) )
        {
            removeDirectory( $file );
        }
        else
        {
            @chmod($file, 0777);
            @unlink( $file );
            echo '[Deleting File] '.$file.'.</br>';
        }
    }

    # try to apply delete permissiosn
    if(@chmod($dir, 0777) === FALSE)
    {
        echo "[Deleting Directory] Setting the permission to delete the directory on directory $dir failed!<br/>";
    }
    else
    {
        echo "[Deleting Directory] Successfully applied permission to delete the directory on directory $dir!<br/>";
    }

    # try to remove directory
    if(@rmdir($dir) === FALSE)
    {
        echo "[Deleting Directory] Removing of directory $dir failed! Please remove it manually.<br/>";
    }
    else
    {
        # rmdir sucessfull
        echo "[Deleting Directory] Removing of directory $dir<br/>";
    }
}

// Save+Close the Session
session_write_close();

/**
 * Clansuit Exception - Installation Startup Exception
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Exception
 */
class Clansuite_Installation_Startup_Exception extends Exception
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
        $errormessage    = '<p><html><head>';
        $errormessage   .= '<title>Clansuite Installation - Error</title>';
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
        $errormessage   .= "<br />If you can't solve the error yourself, feel free to contact us at our website's <a href=\"http://forum.clansuite.com/index.php?board=25.0\">Installation - Support Forum</a>.<br/>";
        $errormessage   .= '</fieldset>';
        # FOOTER
        $errormessage   .= '</body></html>';

        #return __CLASS__ . " {$errormessage}";
        return $errormessage;
    }
}
?>
