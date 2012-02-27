<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Florian Wolf (2005-2006)
    *
    * @version    SVN: $Id$
    */

session_start();

@set_time_limit(0);

# Security Handler
define('IN_CS', true);

# Debugging Handler
define('DEBUG', false);

# Define: DS; INSTALLATION_ROOT; ROOT
define('DS', DIRECTORY_SEPARATOR);
define('INSTALLATION_ROOT', __DIR__ . DS);
define('ROOT', dirname(INSTALLATION_ROOT) . DS);
define('ROOT_CACHE', ROOT . 'cache' . DS);

/**
 * @var HTML Break + Carriage Return
 */
define('NL', "<br />\r\n", false);
define('CR', "\n");

# Error Reporting Level
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', true);
ini_set('display_errors', true);

// Define $error
$error = '';

/**
 *  =====================
 *     Startup Checks
 *  =====================
 */
try
{
    # PHP Version Check
    define('REQUIRED_PHP_VERSION', '5.3.0');
    if(version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<=') === true)
    {
        throw new Clansuite_Installation_Startup_Exception(
            'Your PHP Version is <b>' . PHP_VERSION . '</b>. Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>.', 1);
    }

    # PDO extension must be available
    if(false === class_exists('PDO'))
    {
        throw new Clansuite_Installation_Startup_Exception(
            '"<i>PHP_PDO</i>" extension not enabled. The extension is needed for accessing the database.', 2);
    }

    # php_pdo_mysql driver must be available
    if(false === in_array('mysql', PDO::getAvailableDrivers()))
    {
        throw new Clansuite_Installation_Startup_Exception(
            '"<i>php_pdo_mysql</i>" driver not enabled. The extension is needed for accessing the database.', 3);
    }
}
catch(Exception $e)
{
    exit($e);
}

// The Clansuite version this script installs
require ROOT . 'core/bootstrap/clansuite.version.php';

// Initialize Doctrine2 Autoloader
use Doctrine\Common\ClassLoader;
require ROOT . 'libraries/Doctrine/Common/ClassLoader.php';
$classLoader = new ClassLoader('Doctrine', ROOT . 'libraries/');
$classLoader->register();

if(DEBUG)
{
    var_export($_SESSION, false);
    var_export($_POST, false);
}

#===================
#   SELF DELETION
#===================

if(isset($_GET['delete_installation']))
{
    deleteInstallationFolder();
}

#================================
#   STEP HANDLING + PROGRESS
#================================
# Get Total Steps and if we are at max_steps, set step to max
$total_steps = get_total_steps();

# Update the session with the given variables!
$_SESSION = array_merge_rec($_POST, $_SESSION);

# STEP HANDLING
if(isset($_SESSION['step']))
{
    $step = (int) intval($_SESSION['step']);
    if(isset($_POST['step_forward']))  { $step = $step + 1; }
    if(isset($_POST['step_backward'])) { $step = $step - 1; }
    if($step >= $total_steps)          { $step = $total_steps; }
    if($step == 0) { $step = 1; }
}
else
{ $step = 1;}

# Calculate Progress
$_SESSION['progress'] = calc_progress($step, $total_steps);

/**
 * ===========================
 *    Language Handling
 * ===========================
 */
date_default_timezone_set('Europe/Berlin');
# Get language from GET
if(isset($_GET['lang']) and empty($_GET['lang']) === false)
{
    $lang = (string) htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8');
}
else
{
    # Get language from SESSION
    if(isset($_SESSION['lang']))
    {
        $lang = $_SESSION['lang'];
    }

    # SET DEFAULT LANGUAGE VAR
    if($step == 1 or empty($_SESSION['lang']))
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
    $file = INSTALLATION_ROOT . 'languages' . DS . $lang . '.install.php';
    if(is_file($file) === true)
    {
        include_once $file;
        $language = new language;
        $_SESSION['lang'] = $lang;
    }
    else
    {
        throw new Clansuite_Installation_Startup_Exception('<span style="color:red">Language file missing: <strong>' . $file . '</strong></span>');
    }
}
catch(Exception $e)
{
    exit($e);
}

#=======================
#      START OUTPUT
#=======================
# load header and menu
require 'install_header.php';
require 'install_menu.php';

/***
 * ===============================================
 *      Handling of Installations STEPS
 * ===============================================
 *
 * Procedure Notice:
 * if a STEP is successful, proceed to the next, else return to the same STEP and display error
 */

/**
 * ===================================================
 *      Handling of Installation STEP 4 - Database
 * ===================================================
 *
 * Procedure Notice:
 * 1) check if database settings are valid
 * 2) create table
 * 3) connect to database
 * 4) validate database schema
 * 5) insert database schema
 * 6) write database settings to config file
 */
if(isset($_POST['step_forward']) && $step == 5)
{

    /**
     * 1) Valid Database Settings.
     *
     * Ensure the database settings incomming from input-fields are valid.
     */
    if(!empty($_POST['config']['database']['dbname']) &&
        ! ctype_digit($_POST['config']['database']['dbname']) &&
        preg_match('#^[a-zA-Z0-9]{1,}[a-zA-Z0-9_\-@]+[a-zA-Z0-9_\-@]*$#', $_POST['config']['database']['dbname']) &&
        ! empty($_POST['config']['database']['host']) &&
        ! empty($_POST['config']['database']['driver']) &&
        ! empty($_POST['config']['database']['user']) &&
        isset($_POST['config']['database']['password']))
    {
        /**
         * 2) Create database.
         *
         * Has the user requested to create the database?
         */
        if(isset($_POST['config']['database']['create_database']) and $_POST['config']['database']['create_database'] == 'on')
        {
            try
            {
                # connection without dbname (must be blank for create table)
                $connectionParams = array(
                    'user'      => $_POST['config']['database']['user'],
                    'password'  => $_POST['config']['database']['password'],
                    'host'      => $_POST['config']['database']['host'],
                    'driver'    => $_POST['config']['database']['driver'],
                );

                $config = new \Doctrine\DBAL\Configuration();
                $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
                $connection->setCharset('UTF8');

                /**
                 * fetch doctrine schema manager
                 * and create database
                 */
                $schema_manager = $connection->getSchemaManager();
                $schema_manager->createDatabase($_POST['config']['database']['dbname']);

                /**
                 * Another way of doing this is via the specific database platform command.
                 * Then for creating the database the platform is asked, which SQL CMD to use.
                 * For "pdo_mysql" it would result in a string like 'CREATE DATABASE name'.
                 */
                #$db = $connection->getDatabasePlatform();
                #$sql = $db->getCreateDatabaseSQL('databasename');
                #$connection->exec($sql);
            }
            catch(Exception $e)
            {
                $step = 4;
                $error = $language['ERROR_WHILE_CREATING_DATABASE'] . NL . NL;
                $error .= $e->getMessage() . '.';
            }
        }

        /**
         * 3) Connect to Database
         */

        # Drop Connection.
        unset($connection);

        # Setup Connection Parameters. This time with "dbname".
        $connectionParams = array(
            'dbname'    => $_POST['config']['database']['dbname'],
            'user'      => $_POST['config']['database']['user'],
            'password'  => $_POST['config']['database']['password'],
            'host'      => $_POST['config']['database']['host'],
            'driver'    => $_POST['config']['database']['driver'],
        );

        $entityManager = getEntityManager($connectionParams);

        /**
         * 4) Validate Database Schemas
         */
        try
        {
            # instantiate validator
            $validator = new \Doctrine\ORM\Tools\SchemaValidator($entityManager);

            # validate
            $validation_error = $validator->validateMapping();

            # handle validation errors
            if($validation_error)
            {
                var_dump($validation_error);
            }
        }
        catch(Exception $e)
        {
            $step = 4;
            $error = $language['ERROR_NO_DB_CONNECT'] . NL . $e->getMessage();
        }

        /**
         * 5) Insert/Update Schemas
         *
         * "recreate" will do a database drop, before schemas are updated.
         */
        try
        {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
            $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
            if(isset($_GET['recreate']))
            {
                $schemaTool->dropSchema($metadata);
            }
            $schemaTool->updateSchema($metadata);

            $entityManager->flush();
        }
        catch(Exception $e)
        {
            $html = '';
            $html .= 'The update failed!' . NL;
            $html .= 'Do you want to force a database drop ('.$connectionParams['dbname'].')?' . NL;
            $html .= 'This will result in a total loss of all data and database tables.' . NL;
            $html .= 'It will allow for an clean installation of the database.' . NL;
            $html .= 'WARNING: Act carefully!' . NL;
            $html .= '<form action="index.php?step=4&recreate=true" method="post">';
            $html .= '<input type="submit" value="Recreate Database" class="retry"></form>';

            $step = 4;
            $error = $language['ERROR_NO_DB_CONNECT'] . NL . $e->getMessage();
            $error .= NL . NL . $html;
        }

        /**
         * 6. Write Settings to clansuite.config.php
         */
        if(false === write_config_settings($_POST['config']))
        {
            $step = 4;
            $error = 'Config not written.'. NL;
        }
    }
    else # input fields empty
    {
        $step = 4;
        $error = 'The provided database settings are not valid! ';
        $error .= $language['ERROR_FILL_OUT_ALL_FIELDS'];

        /**
         * This adjusts the error message,
         * in case the validity of the "database name" FAILED.
         *
         * The database name has serious restrictions:
         * Forbidden are database names containing
         * only numbers and names like mysql-database commands.
         */
        if(isset($_POST['config']['database']['name']) &&
           preg_match('#^[a-zA-Z0-9]{1,}[a-zA-Z0-9_\-@]+[a-zA-Z0-9_\-@]*$#', $_POST['config']['database']['name']))
        {
            $error .= '<p>The database name you have entered ("' . $_POST['config']['database']['name'] . '") is invalid.</p>';
            $error .= '<p> It can only contain alphanumeric characters, periods or underscores.';
            $error .= ' You might use chars printed within brackets: [A-Z], [a-z], [0-9], [-_].</p>';
            $error .= '<p> Forbidden are database names containing only numbers and names like mysql-database commands.</p>';
        }
    }
}

/**
 * ========================================================
 *      Handling of Installation STEP 5 - Configuration
 * ========================================================
 */
if(isset($_POST['step_forward']) && $step == 6)
{

    # check if input-fields are filled
    if(isset($_POST['config']['template']['pagetitle']) &&
       isset($_POST['config']['email']['from']) &&
       isset($_POST['config']['language']['timezone']))
    {
        $config_array = array();
        $config_array = $_POST['config'];
        $config_array['language']['gmtoffset'] = (int) $_POST['config']['language']['timezone'];
        $config_array['language']['timezone'] = (string) timezone_name_from_abbr('', $_POST['config']['language']['timezone'], 0);

        # write Settings to clansuite.config.php
        if(!write_config_settings($config_array))
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
 * ===============================================================
 *      Handling of Installation STEP 6 - Create Administrator
 * ===============================================================
 */
if(isset($_POST['step_forward']) && $step == 7)
{
    // Security Class is required for Password Hashes
    require ROOT . 'core/security.core.php';

    # checken, ob admin name und password vorhanden
    # wenn nicht, fehler : zurï¿?ck zu STEP6
    if(!isset($_POST['admin_name']) && ! isset($_POST['admin_password']))
    {
        $step = 6;
        $error = $language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'];
    }
    else
    {
        // Generate activation code & salted hash
        $hashArr = Clansuite_Security::build_salted_hash(
            $_POST['admin_password'], $_SESSION['encryption']
        );
        $hash = $hashArr['hash'];
        $salt = $hashArr['salt'];

        /**
         * Insert admin user into the database.
         *
         * We are using a raw sql statement with bound variables passing it to Doctrine2.
         */
        $db = getEntityManager()->getConnection();

        $raw_sql_query = 'INSERT INTO ' . $_SESSION['config']['database']['prefix'] . 'users
                          SET  email = :email,
                               nick = :nick,
                               passwordhash = :hash,
                               salt = :salt,
                               joined = :joined,
                               language = :language,
                               activated = :activated';

        $stmt = $db->prepare($raw_sql_query);

        $params = array(
            'email' => $_POST['admin_email'],
            'nick' => $_POST['admin_name'],
            'hash' => $hash,
            'salt' => $salt,
            'joined' => time(),
            'language' => $_SESSION['admin_language'],
            'activated' => '1'
        );

        $stmt->execute($params);

    }
}

/**
 * =========================================================
 *      SWITCH to Intallation-functions based on "STEPS"
 * =========================================================
 */
# add step to function name
$installfunction = "installstep_$step";

# check if this functionname exists
if(function_exists($installfunction))
{
    # set this step to the session
    $_SESSION['step'] = $step;
    # lets rock! :P
    $installfunction($language, $error);
}

#=======================
#      END OUTPUT
#=======================
# INCLUDE THE FOOTER !
include INSTALLATION_ROOT . 'install_footer.php';

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
 * by checking how many functions with name "installstep_x" exist
 *
 * @return $_SESSION['total_steps']
 */
function get_total_steps()
{
    if(isset($_SESSION['total_steps']))
    {
        return $_SESSION['total_steps'];
    }

    for($i = 1; function_exists('installstep_' . $i) === true; $i++)
    {
        $_SESSION['total_steps'] = $i;
    }
    return $_SESSION['total_steps'];
}

/**
 * Calculate Progress
 * is used to display install-progress in percentages
 *
 * @params $this_is_step Current Step
 * @params $of_total_steps Total Number of Steps
 * @return float progress-value
 */
function calc_progress($current_step, $total_steps)
{
    if($current_step <= 1) { return 0;}
    return round((100 / $total_steps) * $current_step, 0);
}

// STEP 1 - Language Selection
function installstep_1($language)
{
    include INSTALLATION_ROOT . 'install-step1.php';
}

// STEP 2 - System Check
function installstep_2($language)
{
    include INSTALLATION_ROOT . 'install-step2.php';
}

// STEP 3 - System Check
function installstep_3($language)
{
    include INSTALLATION_ROOT . 'install-step3.php';
}

// STEP 4 - System Check
function installstep_4($language, $error)
{
    $values['host']             = isset($_SESSION['host'])             ? $_SESSION['host'] : 'localhost';
    $values['driver']           = isset($_SESSION['driver'])           ? $_SESSION['driver'] : 'pdo_mysql';
    $values['dbname']           = isset($_SESSION['dbname'])           ? $_SESSION['dbname'] : 'clansuite';
    $values['create_database']  = isset($_SESSION['create_database'])  ? $_SESSION['create_database'] : '0';
    $values['user']             = isset($_SESSION['user'])             ? $_SESSION['user'] : '';
    $values['password']         = isset($_SESSION['password'])         ? $_SESSION['password'] : '';
    $values['prefix']           = isset($_SESSION['prefix'])           ? $_SESSION['prefix'] : 'cs_';

    include INSTALLATION_ROOT . 'install-step4.php';
}

// STEP 5 - System Check
function installstep_5($language)
{
    $values['pagetitle']    = isset($_SESSION['pagetitle'])   ? $_SESSION['pagetitle'] : 'Team Clansuite';
    $values['from']         = isset($_SESSION['from'])        ? $_SESSION['from'] : 'webmaster@website.com';
    $values['timezone']     = isset($_SESSION['timezone'])    ? $_SESSION['timezone'] : '3600';
    $values['encryption']   = isset($_SESSION['encryption'])  ? $_SESSION['encryption'] : 'SHA1';

    include INSTALLATION_ROOT . 'install-step5.php';
}

// STEP 6 - System Check
function installstep_6($language)
{
    $values['admin_name']       = isset($_SESSION['admin_name'])     ? $_SESSION['admin_name'] : 'admin';
    $values['admin_password']   = isset($_SESSION['admin_password']) ? $_SESSION['admin_password'] : 'admin';
    $values['admin_email']      = isset($_SESSION['admin_email'])    ? $_SESSION['admin_email'] : 'admin@email.com';
    $values['admin_language']   = isset($_SESSION['admin_language']) ? $_SESSION['admin_language'] : 'en_EN';

    include 'install-step6.php';
}

// STEP 7 - System Check
function installstep_7($language)
{
    include INSTALLATION_ROOT . 'install-step7.php';
}

/**
 * Writes the Database-Settings into the clansuite.config.php
 *
 * @param $data_array
 * @return BOOLEAN true, if clansuite.config.php could be written to the INSTALLATION_ROOT
 */
function write_config_settings($data_array)
{
    # Read/Write Handler for config files
    include ROOT . 'core/config/ini.config.php';

    # throw not needed / non-setting vars out
    unset($data_array['step_forward']);
    unset($data_array['lang']);
    unset($data_array['create_database']);

    # base class is needed for Clansuite_Config_INI
    if(false === class_exists('Clansuite_Config_Base'))
    {
        require ROOT . 'core/config/config.base.php';
    }

    # read skeleton settings = minimum settings for initial startup
    # (not asked from user during installation, but required paths, default actions, etc.)
    $installer_config = Clansuite_Config_INI::readConfig(INSTALLATION_ROOT . 'clansuite.config.installer');

    # array merge: overwrite the array to the left, with the array to the right, when keys identical
    $data_array = array_merge_recursive($data_array, $installer_config);

    # Write Config File to ROOT Directory
    if(!Clansuite_Config_INI::writeConfig(ROOT . 'configuration/clansuite.config.php', $data_array))
    {
        return false;
    }
    return true;
}

function getEntityManager($connectionParams = null)
{
    if(is_array($connectionParams) === false)
    {
        # Read/Write Handler for config files
        include ROOT . 'core/config/ini.config.php';

        # fetch the dsn/connection config
        $clansuite_config = Clansuite_Config_INI::readConfig(ROOT . 'configuration/clansuite.config.php');
        $connectionParams = $clansuite_config['database'];
    }

    # connect
    $config = new \Doctrine\DBAL\Configuration();
    $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    $connection->setCharset('UTF8');

    # get Event and Config
    $event = $connection->getEventManager();
    $config = new \Doctrine\ORM\Configuration();

    # setup Cache
    $cache = new \Doctrine\Common\Cache\ArrayCache();
    $config->setMetadataCacheImpl($cache);

    # setup Proxy Dir
    $config->setProxyDir(realpath(ROOT . 'doctrine'));
    $config->setProxyNamespace('Proxies');

    # setup Annotation Driver
    $driverImpl = $config->newDefaultAnnotationDriver(
        getModelPathsForAllModules());
    $config->setMetadataDriverImpl($driverImpl);

    # finally: instantiate EntityManager
    $entityManager = \Doctrine\ORM\EntityManager::create($connection, $config, $event);

    return $entityManager;
}

/**
 * Delete the installation folder
 */
function deleteInstallationFolder()
{
    echo "Deleting Directory - " . __DIR__;

    removeDirectory(__DIR__);

    # display success message
    if(false === file_exists(__DIR__))
    {
        echo '<p><center>
                <h1>Finished!</h1>
                <br />
                <p><a href="../index.php">Click here to proceed!</a></p>
              </center></p>';
    }

    exit();
}

/**
 * removeDirectory
 *
 * Remove a directory and all it files recursively.
 * @param file {String} The file or folder to be deleted.
 * */
function removeDirectory($dir)
{
    # get files
    $files = array_merge(glob($dir . '/*'), glob($dir . '/.*'));
    if(strpos($dir, 'installation') === false)
    {
        die('ERROR!' . var_dump($dir));
    }

    foreach($files as $file)
    {
        # skip the index.php
        if(preg_match('#[\\|/]\.$#', $file) || preg_match('#[\\|/]\.\.$#', $file))
        {
            continue;
        }

        # skip dirs
        if(is_dir($file))
        {
            removeDirectory($file);
        }
        else
        {
            chmod($file, 0777);
            unlink($file);
            echo '.'; #[Deleting File] '.$file.'.</br>';
        }
    }

    # try to apply delete permissiosn
    if(chmod($dir, 0777) === false)
    {
        echo '[Deleting Directory] Setting the permission to delete the directory on directory ' . $dir . ' failed!<br/>';
    }
    else
    {
        # echo '[Deleting Directory] Successfully applied permission to delete the directory on directory '.$dir.'!<br/>';
    }

    # try to remove directory
    if(rmdir($dir) === false)
    {
        echo '[Deleting Directory] Removing of directory ' . $dir . ' failed! Please remove it manually.<br/>';
    }
    else
    {
        # rmdir sucessfull
        # echo '[Deleting Directory] Removing of directory '.$dir.'<br/>';
    }
}

// Save+Close the Session
session_write_close();

/**
 * Fetches Model Paths for all modules
 *
 * @return array Array with all model directories
 */
function getModelPathsForAllModules()
{
    $model_dirs = array();

    $dirs = glob( ROOT . '/modules/' . '[a-zA-Z]*', GLOB_ONLYDIR );

    foreach($dirs as $key => $dir_path)
    {
        # Entity Path
        $entity_path = $dir_path . DS . 'model' . DS . 'entities' . DS;

        if(is_dir($entity_path))
        {
            $model_dirs[] = $entity_path;
        }

        # Repository Path
        $repos_path = $dir_path . DS . 'model' . DS . 'repositories' . DS;

        if(is_dir($repos_path))
        {
            $model_dirs[] = $repos_path;
        }
    }

    #$model_dirs[] = ROOT . 'doctrine';

    $model_dirs = array_unique($model_dirs);

    #Clansuite_Debug::printR($model_dirs);
    return $model_dirs;
}

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
     * Define Exceptionmessage && Code via constructor
     * and hand it over to the parent Exception Class
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * Transform the Object to String
     */
    public function __toString()
    {
        # Header
        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
                           "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">';
        $html .= '<head><title>Clansuite Installation - Error</title>';
        $html .= '<link rel="stylesheet" href="../themes/core/css/error.css" type="text/css" />';
        $html .= '</head><body>';

        /**
         * Fieldset for Exception Message
         *
         * You might set the following colour attributes (error_red, error_orange, error_beige) as defined in core/error.css.
         */
        $html .= '<fieldset class="error_beige">';
        $html .= '<div style="float:left; padding: 15px;">';
        $html .= '<img src="images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;" alt="Clansuite Error Icon" /></div>';
        $html .= '<legend>Clansuite Installation Error</legend>';
        $html .= '<p><strong>' . $this->message . '</strong>';

        /**
         * Display a table with all pieces of information of the exception.
         */
        if(DEBUG === true)
        {
            $html .= '<hr><table>';
            $html .= '<tr><td><strong>Errorcode</strong></td><td>' . $this->getCode() . '</td></tr>';
            $html .= '<tr><td><strong>Message</strong></td><td>' . $this->getMessage() . '</td></tr>';
            $html .= '<tr><td><strong>Pfad</strong></td><td>' . dirname($this->getFile()) . '</td></tr>';
            $html .= '<tr><td><strong>Datei</strong></td><td>' . basename($this->getFile()) . '</td></tr>';
            $html .= '<tr><td><strong>Zeile</strong></td><td>' . $this->getLine() . '</td></tr>';
            $html .= '</table>';
        }

        $html .= '</p></fieldset><br />';

        /**
         * Fieldset for Help Message
         */
        $html .= '<fieldset class="error_beige">';
        $html .= '<legend>Help</legend>';
        $html .= '<ol>';
        $html .= '<li>You might use <a href="phpinfo.php">phpinfo()</a> to check your serversettings.</li>';

        if( get_cfg_var('cfg_file_path') )
        {
            $cfg_file_path = get_cfg_var('cfg_file_path');
        }
        $html .= '<li>Check your php.ini ('. $cfg_file_path .') and ensure all needed extensions are loaded. <br />';
        $html .= 'After a modification of your php.ini you must restart your webserver.</li>';

        $html .= '<li>Check the webservers errorlog.</li></ol><p>';
        $html .= "If you can't solve the error yourself, feel free to contact us at our website's <a href=\"http://forum.clansuite.com/index.php?board=25.0\">Installation - Support Forum</a>.<br/>";
        $html .= '</p></fieldset>';
        $html .= '</body></html>';

        return $html;
    }

}
?>