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

/**
 * Bootstrap
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

# load  Clansuite Version constants
require ROOT . 'core/bootstrap/clansuite.version.php';
Clansuite_Version::setVersionInformation();

# Error Reporting Level
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', true);
ini_set('display_errors', true);

if(DEBUG)
{
    var_export($_SESSION, false);
    var_export($_POST, false);
}

/**
 * Startup Checks
 */

# PHP Version Check
define('REQUIRED_PHP_VERSION', '5.3.0');
if(version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<=') === true)
{
    throw new Clansuite_Installation_Exception(
            'Your PHP Version is <b>' . PHP_VERSION . '</b>. Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>.', 1);
}

# PDO extension must be available
if(false === class_exists('PDO'))
{
    throw new Clansuite_Installation_Exception(
            '"<i>PHP_PDO</i>" extension not enabled. The extension is needed for accessing the database.', 2);
}

# php_pdo_mysql driver must be available
if(false === in_array('mysql', PDO::getAvailableDrivers()))
{
    throw new Clansuite_Installation_Exception(
            '"<i>php_pdo_mysql</i>" driver not enabled. The extension is needed for accessing the database.', 3);
}

/**
 * Start Installation Application
 */
new Clansuite_Installation;

/**
 * Clansuite Installation
 * ----------------------
 *
 * GET Requests
 * ------------
 * "?reset_session"      - Resets the installaton session
 * "?delete_installaton" - Deletes the Installation Folder
 */
class Clansuite_Installation
{
    /**
     * @var string Language Locale (german, english).
     */
    public $locale;

    /**
     * @var object Language.
     */
    public $language;

    /**
     * @var int The current installation step.
     */
    public $step;

    /**
     * @var string Errormessage.
     */
    public $error;

    /**
     * @var array Default Values for the formulars.
     */
    public $values;

    public function __construct()
    {
        $this->handleRequest_deleteInstallationFolder();

        $this->loadDoctrine();

        $this->handleRequest_Language();
        $this->loadLanguage();

        $this->getTotalNumberOfInstallationSteps();

        $this->determineCurrentStep();
        $this->processPreviousStep();
        $this->calculateInstallationProgress();
        $this->renderStep();

        register_shutdown_function('Clansuite_Installation::shutdown');
    }

    public function loadDoctrine()
    {
        // Initialize Doctrine2 Autoloader
        require ROOT . 'libraries/Doctrine/Common/ClassLoader.php';
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', ROOT . 'libraries/');
        $classLoader->register();
    }

    /**
     * Triggers the self deletion of the installation folder.
     *
     * When the installation finishes in step 7, the user is asked,
     * wether he might delete the installation folder for security purposes.
     */
    public function handleRequest_deleteInstallationFolder()
    {
        # allow session reset only in debug mode
        if(DEBUG == true and isset($_GET['reset_session']))
        {
            $_SESSION = array();
            unset($_SESSION);
        }

        if(isset($_GET['delete_installation']))
        {
            /**
             * Delete the installation folder
             */
            echo "Deleting Directory - " . __DIR__;

            Clansuite_Installation_Helper::removeDirectory(__DIR__);

            # display success message
            if(false === file_exists(__DIR__))
            {
                echo '<p>
                        <center><h1>Finished!</h1><br />
                            <p><a href="../index.php">Click here to proceed!</a></p>
                        </center>
                      </p>';
            }

            exit();
        }
    }

    /**
     * Sets the requested Locale.
     *
     * Processing order is GET before SESSION and Default.
     * This allows changing the language during the installation.
     * If session is empty or Step 1 then use DEFAULT.
     */
    public function handleRequest_Language()
    {
        date_default_timezone_set('Europe/Berlin');

        # Get language from GET
        if(isset($_GET['lang']) and empty($_GET['lang']) === false)
        {
            $this->locale = (string) htmlspecialchars($_GET['lang'], ENT_QUOTES, 'UTF-8');
        }
        else
        {
            # Get language from SESSION
            if(isset($_SESSION['lang']))
            {
                $this->locale = $_SESSION['lang'];
            }

            # SET DEFAULT locale
            if($this->step == 1 or empty($_SESSION['lang']))
            {
                $this->locale = 'german';
            }
        }
    }

    public function loadLanguage()
    {
        /**
         * Load Language File
         */
        try
        {
            $file = INSTALLATION_ROOT . 'languages' . DS . $this->locale . '.install.php';

            if(is_file($file) === true)
            {
                include_once $file;

                $this->language = new language;

                $_SESSION['lang'] = $this->locale;
            }
            else
            {
                throw new Clansuite_Installation_Exception('<span style="color:red">Language file missing: <strong>' . $file . '</strong></span>');
            }
        }
        catch(Exception $e)
        {
            exit($e);
        }
    }

    public function getTotalNumberOfInstallationSteps()
    {
        $this->total_steps = Clansuite_Installation_Helper::getTotalNumberOfSteps();
    }

    /**
     * Handles Installation Steps and Calculates Installation Progress Bar
     *
     * Procedure Notice:
     * If a STEP is successful,
     * proceed to the next, else return to the same STEP and display error.
     */
    public function determineCurrentStep()
    {
        # update the session with the given variables!
        $_SESSION = Clansuite_Installation_Helper::array_merge_rec($_POST, $_SESSION);

        /**
         * STEP HANDLING
         */
        if(isset($_SESSION['step']))
        {
            $this->step = (int) intval($_SESSION['step']);

            if(isset($_POST['step_forward']))
            {
                $this->step = $this->step + 1;
            }

            if(isset($_POST['step_backward']))
            {
                $this->step = $this->step - 1;
            }

            if($this->step >= $this->total_steps)
            {
                $this->step = $this->total_steps;
            }

            if($this->step == 0)
            {
                $this->step = 1;
            }
        }
        else
        {
            $this->step = 1;
        }

        unset($_SESSION['step_forward'], $_SESSION['step_backward']);
    }

    public function calculateInstallationProgress()
    {
        /**
         * Calculate Progress Percentage
         */
        $_SESSION['progress'] = Clansuite_Installation_Helper::calculateProgress($this->step, $this->total_steps);
    }

    public function processPreviousStep()
    {
        $previous_step = $this->step - 1;

        $prev_step_class = 'Clansuite_Installation_Step' . $previous_step;

        if(class_exists($prev_step_class))
        {
            $prev_step = new $prev_step_class(
                $this->language,
                $this->step,
                $this->total_steps,
                $this->error
            );

            if(false === method_exists($prev_step, 'validateFormValues'))
            {
                /**
                 * We just finished an installation step without sending any form values.
                 * Steps 1, 2, 3.
                 */

                return;
            }
            else
            {
                /**
                 * We finished an installation step with sending form values.
                 * Steps 4, 5, 6.
                 */

                // The incomming form values must be valid.
                if($prev_step->validateFormValues() === true)
                {
                    // The form values are valid.
                    // Now process them.
                    $prev_step->processValues();
                }
                else
                {
                    $this->error = $prev_step->error;
                    $this->step = $previous_step;
                }
            }
        }
    }

    public function renderStep()
    {
        /**
         * =========================================================
         *      SWITCH to the next Installation STEP
         * =========================================================
         */

        $step_class = 'Clansuite_Installation_Step' . $this->step;

        if(class_exists($step_class))
        {
            $_SESSION['step'] = $this->step;

           $step = new $step_class(
                $this->language,
                $this->step,
                $this->total_steps,
                $this->error
            );

           $step->render();
        }
    }

    public static function shutdown()
    {
        if(true == session_id())
        {
            // Save + close the session
            session_write_close();
        }
    }
}

class Clansuite_Installation_Helper
{
    /**
     * Writes the Database-Settings into the clansuite.config.php
     *
     * @param $data_array
     * @return BOOLEAN true, if clansuite.config.php could be written to the INSTALLATION_ROOT
     */
    public static function write_config_settings($data_array)
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
            // config not written
            return false;
        }
        // config written
        return true;
    }

    /**
     * Array Merge Recursive
     *
     * @param $arr1 array
     * @param $arr2 array
     * @return recusrive merged array
     */
    public static function array_merge_rec($arr1, $arr2)
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
                    $arr1[$k] = self::array_merge_rec($arr1[$k], $arr2[$k]);
                }
            }
        }
        return $arr1;
    }

    /**
     * removeDirectory
     *
     * Removes a directory and all files recursively.
     *
     * @param string The file or folder to be deleted.
     */
    public static function removeDirectory($dir)
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
                self::removeDirectory($file);
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

    /**
     * Returns the total number of installations steps
     * by counting the number of classes named "Clansuite_Installation_StepX".
     *
     * @return int Total number of install steps. $_SESSION['total_steps']
     */
    public static function getTotalNumberOfSteps()
    {
        # count only once
        if(isset($_SESSION['total_steps']))
        {
            return $_SESSION['total_steps'];
        }

        # count the number of classes named "Clansuite_Installation_StepX"
        for($i = 1; class_exists('Clansuite_Installation_Step' . $i) === true; $i++)
        {
            $_SESSION['total_steps'] = $i;
        }
        return $_SESSION['total_steps'];
    }

    /**
     * Calculates the installation progress in percentages
     * based on the total number of steps and the current step.
     *
     * @params int $current_step The number of the current install step.
     * @params int $total_steps The total number of install steps.
     * @return float progress-value
     */
    public static function calculateProgress($current_step, $total_steps)
    {
        if($current_step <= 1)
        {
            return 0;
        }

        return round((100 / $total_steps) * $current_step, 0);
    }

    /**
     * Fetches Model Paths for all modules
     *
     * @return array Array with all model directories
     */
    public static function getModelPathsForAllModules()
    {
        $model_dirs = array();

        $dirs = glob(ROOT . '/modules/' . '[a-zA-Z]*', GLOB_ONLYDIR);

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

    public static function getDoctrineEntityManager($connectionParams = null)
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
            Clansuite_Installation_Helper::getModelPathsForAllModules());
        $config->setMetadataDriverImpl($driverImpl);

        # finally: instantiate EntityManager
        $entityManager = \Doctrine\ORM\EntityManager::create($connection, $config, $event);

        return $entityManager;
    }
}

class Clansuite_Installation_Page
{
    public $values;
    public $step;
    public $total_steps;
    public $error;
    public $language;

    public function __construct($language, $step, $total_steps, $error = '')
    {
        $this->language = $language;
        $this->step = $step;
        $this->total_steps = $total_steps;
        $this->error = $error;
    }

    public function render()
    {
        /**
         * Fetch class variables into the local scope.
         * The just look unused, but are used in the included files.
         */
        $language       = $this->language;
        $error          = $this->error;
        $step           = $this->step;
        $total_steps    = $this->total_steps;

        if(method_exists($this, 'getDefaultValues'))
        {
            $values = $this->getDefaultValues();
        }

        if(DEBUG == false)
        {
            ob_start();
        }

        include INSTALLATION_ROOT . 'install_header.php';
        include INSTALLATION_ROOT . 'install_menu.php';
        include INSTALLATION_ROOT . 'install-step' . $step . '.php';
        include INSTALLATION_ROOT . 'install_footer.php';

        if(DEBUG == false)
        {
            ob_get_flush();
        }
    }

    public function setStep($step)
    {
        $this->step = $step;
    }

    public function setValues($values)
    {
        $this->values = $values;
    }

    public function setErrorMessage($error)
    {
        $this->error = $error;
    }

    public function getErrorMessage($error)
    {
        $this->error = $error;
    }

    public function setTotalSteps($total_steps)
    {
        $this->total_steps = $total_steps;
    }
}

/**
 * Step 1 - Language Selection
 */
class Clansuite_Installation_Step1 extends Clansuite_Installation_Page
{

}

/**
 * Step 2 - System Check
 */
class Clansuite_Installation_Step2 extends Clansuite_Installation_Page
{
}

/**
 * Step 3 - GPL License
 */
class Clansuite_Installation_Step3 extends Clansuite_Installation_Page
{
}

/**
 * STEP 4 - Database Source Name Configuration
 *
 * Procedure Notice:
 * 1) check if database settings are valid
 * 2) create table
 * 3) connect to database
 * 4) validate database schema
 * 5) insert database schema
 * 6) write database settings to config file
 */
class Clansuite_Installation_Step4 extends Clansuite_Installation_Page
{
    public function getDefaultValues()
    {
        $values = array();

        $values['host']     = isset($_SESSION['host'])     ? $_SESSION['host']     : 'localhost';
        $values['driver']   = isset($_SESSION['driver'])   ? $_SESSION['driver']   : 'pdo_mysql';
        $values['dbname']   = isset($_SESSION['dbname'])   ? $_SESSION['dbname']   : 'clansuite';
        $values['user']     = isset($_SESSION['user'])     ? $_SESSION['user']     : '';
        $values['password'] = isset($_SESSION['password']) ? $_SESSION['password'] : '';
        $values['prefix']   = isset($_SESSION['prefix'])   ? $_SESSION['prefix']   : 'cs_';
        $values['create_database'] = isset($_SESSION['create_database']) ? $_SESSION['create_database'] : '0';

        return $values;
    }

    public function validateFormValues()
    {
        /**
         *  Valid Database Settings.
         *
         * Ensure the database settings incomming from input-fields are valid.
         */
        if(!empty($_POST['config']['database']['dbname'])
                and ctype_alpha($_POST['config']['database']['dbname'])
                and preg_match('#^[a-zA-Z0-9]{1,}[a-zA-Z0-9_\-@]+[a-zA-Z0-9_\-@]*$#', $_POST['config']['database']['dbname'])
                and !empty($_POST['config']['database']['host'])
                and !empty($_POST['config']['database']['driver'])
                and !empty($_POST['config']['database']['user'])
                and ctype_alnum($_POST['config']['database']['user'])
                and isset($_POST['config']['database']['password']))
        {
            // Values are valid!
            return true;
        }
        else
        {
            // Setup Error Message
            $error = 'The provided database settings are not valid! ';
            $error .= $this->language['ERROR_FILL_OUT_ALL_FIELDS'];

            $this->setErrorMessage($error);

            /**
             * This adjusts the error message,
             * in case the validity of the "database name" FAILED.
             *
             * The database name has serious restrictions:
             * Forbidden are database names containing
             * only numbers and names like mysql-database commands.
             */
            if(isset($_POST['config']['database']['name'])
                    and preg_match('#^[a-zA-Z0-9]{1,}[a-zA-Z0-9_\-@]+[a-zA-Z0-9_\-@]*$#', $_POST['config']['database']['name']))
            {
                $error .= '<p>The database name you have entered ("' . $_POST['config']['database']['name'] . '") is invalid.</p>';
                $error .= '<p> It can only contain alphanumeric characters, periods or underscores.';
                $error .= ' You might use chars printed within brackets: [A-Z], [a-z], [0-9], [-_].</p>';
                $error .= '<p> Forbidden are database names containing only numbers and names like mysql-database commands.</p>';

                $this->setErrorMessage($error);
            }

            // Values are not valid.
            return false;
        }
    }

    public function processValues()
    {
        /**
         * 2) Create database.
         *
         * Has the user requested to create the database?
         */
        if(isset($_POST['config']['database']['create_database'])
                and $_POST['config']['database']['create_database'] == 'on')
        {
            try
            {
                # connection without dbname (must be blank for create table)
                $connectionParams = array(
                    'user' => $_POST['config']['database']['user'],
                    'password' => $_POST['config']['database']['password'],
                    'host' => $_POST['config']['database']['host'],
                    'driver' => $_POST['config']['database']['driver'],
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
                // force return
                $this->setStep(4);

                $error = $this->language['ERROR_WHILE_CREATING_DATABASE'] . NL . NL;
                $error .= $e->getMessage() . '.';

                $this->setErrorMessage($error);
            }
        }

        /**
         * 3) Connect to Database
         */
        # Drop Connection.
        unset($connection);

        # Setup Connection Parameters. This time with "dbname".
        $connectionParams = array(
            'dbname' => $_POST['config']['database']['dbname'],
            'user' => $_POST['config']['database']['user'],
            'password' => $_POST['config']['database']['password'],
            'host' => $_POST['config']['database']['host'],
            'driver' => $_POST['config']['database']['driver'],
        );

        $entityManager = Clansuite_Installation_Helper::getDoctrineEntityManager($connectionParams);

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
                # @todo this is experimental...
                var_dump($validation_error);
            }
        }
        catch(Exception $e)
        {
            // force return
            $this->setStep(4);

            $error = $language['ERROR_NO_DB_CONNECT'] . NL . $e->getMessage();

            $this->setErrorMessage($error);
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
            $html .= 'Do you want to force a database drop (' . $connectionParams['dbname'] . ')?' . NL;
            $html .= 'This will result in a total loss of all data and database tables.' . NL;
            $html .= 'It will allow for an clean installation of the database.' . NL;
            $html .= 'WARNING: Act carefully!' . NL;
            $html .= '<form action="index.php?step=4&recreate=true" method="post">';
            $html .= '<input type="submit" value="Recreate Database" class="retry"></form>';

            // force return
            $this->setStep(4);

            $error = $language['ERROR_NO_DB_CONNECT'] . NL . $e->getMessage();
            $error .= NL . NL . $html;

            $this->setErrorMessage($error);
        }

        /**
         * 6. Write Settings to clansuite.config.php
         */
        if(false === Clansuite_Installation_Helper::write_config_settings($_POST['config']))
        {
            // force return
            $this->setStep(4);

            $error = 'Config not written.' . NL;

            $this->setErrorMessage($error);
        }
    }
}

/**
 * Step 5 - Website Configuration
 */
class Clansuite_Installation_Step5 extends Clansuite_Installation_Page
{
    public function getDefaultValues()
    {
        $values = array();
        $values['pagetitle']  = isset($_SESSION['pagetitle'])  ? $_SESSION['pagetitle']  : 'Team Clansuite';
        $values['from']       = isset($_SESSION['from'])       ? $_SESSION['from']       : 'webmaster@website.com';
        $values['gmtoffset']  = isset($_SESSION['gmtoffset'])  ? $_SESSION['gmtoffset']  : '3600';
        $values['encryption'] = isset($_SESSION['encryption']) ? $_SESSION['encryption'] : 'SHA1';
        return $values;
    }

    public function validateFormValues()
    {
        # check if input-fields are filled
        if(isset($_POST['config']['template']['pagetitle']) &&
        isset($_POST['config']['email']['from']) &&
        isset($_POST['config']['language']['gmtoffset']))
        {
            // Values are valid.
            return true;
        }
        else
        {
            // some input fields are empty
            $this->setErrorMessage($this->language['ERROR_FILL_OUT_ALL_FIELDS']);

            // Values are not valid.
            return false;
        }
    }

    public function processValues()
    {
        $config_array = array();
        $config_array = $_POST['config'];

        // transform the GMTOFFSET (3600 = GMT+1) into a timezone name, like "Europe/Berlin".
        $config_array['language']['timezone'] = (string) timezone_name_from_abbr('', $_POST['config']['language']['timezone'], 0);

        # write Settings to clansuite.config.php
        if(false === Clansuite_Installation_Helper::write_config_settings($config_array))
        {
            $this->setStep(5);
            $this->setErrorMessage('Config not written <br />');
        }
    }
}

/**
 * Step 6 - Create Administrator Account
 */
class Clansuite_Installation_Step6 extends Clansuite_Installation_Page
{
    public function getDefaultValues()
    {
        $values = array();

        $values['admin_name']     = isset($_SESSION['admin_name'])     ? $_SESSION['admin_name']     : 'admin';
        $values['admin_password'] = isset($_SESSION['admin_password']) ? $_SESSION['admin_password'] : 'admin';
        $values['admin_email']    = isset($_SESSION['admin_email'])    ? $_SESSION['admin_email']    : 'admin@email.com';
        $values['admin_language'] = isset($_SESSION['admin_language']) ? $_SESSION['admin_language'] : 'en_EN';

        return $values;
    }

    public function validateFormValues()
    {
        if(($_POST['admin_name'] !== '') and ($_POST['admin_password'] !== ''))
        {
            // Values are valid.
            return true;
        }
        else
        {
            $this->setErrorMessage($this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN']);

            // Values are not valid.
            return false;
        }
    }

    public function processValues()
    {
        /**
         * security class is required
         * for building the user password and salt hashes.
         */
        require ROOT . 'core/security.core.php';

        # generate salted hash
        $hashArray = Clansuite_Security::build_salted_hash(
                        $_POST['admin_password'], $_SESSION['encryption']
        );

        /**
         * Insert admin user into the database.
         *
         * We are using a raw sql statement with bound variables passing it to Doctrine2.
         */
        $db = Clansuite_Installation_Helper::getDoctrineEntityManager()->getConnection();

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
            'hash' => $hashArray['hash'],
            'salt' => $hashArray['salt'],
            'joined' => time(),
            'language' => $_SESSION['admin_language'],
            'activated' => '1'
        );

        $stmt->execute($params);
    }

}

/**
 * STEP 7 - Installation Success. The END.
 */
class Clansuite_Installation_Step7 extends Clansuite_Installation_Page
{
}

/**
 * Clansuit Installation Exception
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Exception
 */
class Clansuite_Installation_Exception extends Exception
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