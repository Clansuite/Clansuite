<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    */

namespace Clansuite;

/**
 * Bootstrap
 */
session_start();

@set_time_limit(0);

# Security Handler
define('IN_CS', true);

# Debugging Handler
define('DEBUG', false);

# Define: DS; INSTALLATION_ROOT; ROOT; HTML Break; Carriage Return
define('DS', DIRECTORY_SEPARATOR);
define('INSTALLATION_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT', dirname(INSTALLATION_ROOT) . DIRECTORY_SEPARATOR);
define('ROOT_CACHE', ROOT . 'cache/');
define('ROOT_APP', ROOT . 'application/');
define('PROTOCOL', 'http://', false);
define('SERVER_URL', PROTOCOL . $_SERVER['SERVER_NAME'], false);
define('WWW_ROOT', SERVER_URL . '/application/', false);
define('WWW_ROOT_THEMES_CORE', WWW_ROOT . 'themes/core/');
define('NL', "<br />\r\n", false);
define('CR', "\n");

# load Clansuite Version constants
require ROOT . 'application/version.php';
\Clansuite\Version::setVersionInformation();

# Error Reporting Level
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', true);
ini_set('display_errors', true);

require ROOT . '/core/exception/exception.php';
require ROOT . '/core/exception/errorhandler.php';
set_exception_handler(array(new \Koch\Exception\Exception,'exception_handler'));

if(DEBUG)
{
    echo 'SESSION: ';
    print_r($_SESSION);
    echo 'POST: ';
    print_r($_POST);
}

/**
 * Startup Checks
 */

# PHP Version Check
define('REQUIRED_PHP_VERSION', '5.3.0');
if(version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<=') === true)
{
    throw new Installation_Exception(
            'Your PHP Version is <b>' . PHP_VERSION . '</b>. Clansuite requires PHP <b>' . REQUIRED_PHP_VERSION . '</b>.', 1);
}

# PDO extension must be available
if(false === class_exists('PDO'))
{
    throw new Installation_Exception(
            '"<i>PHP_PDO</i>" extension not enabled. The extension is needed for accessing the database.', 2);
}

# php_pdo_mysql driver must be available
if(false === in_array('mysql', \PDO::getAvailableDrivers()))
{
    throw new Installation_Exception(
            '"<i>php_pdo_mysql</i>" driver not enabled. The extension is needed for accessing the database.', 3);
}

/**
 * Start Installation Application
 */
new \Clansuite\Installation;

/**
 * Clansuite Installation
 * ----------------------
 *
 * GET Requests
 * ------------
 * "?reset_session"      - Resets the installaton session
 * "?delete_installaton" - Deletes the Installation Folder
 */
class Installation
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
        spl_autoload_register('Clansuite\Installation::autoload');

        $this->handleRequest_deleteInstallationFolder();

        $this->loadDoctrine();

        $this->handleRequest_Language();
        $this->loadLanguage();

        $this->getTotalNumberOfInstallationSteps();

        $this->determineCurrentStep();
        $this->processPreviousStep();
        $this->calculateInstallationProgress();
        $this->renderStep();

        register_shutdown_function('Clansuite\Installation::shutdown');
    }

    private static function autoload($classname)
    {
        $classname = strtolower($classname);

        if (strpos($classname, 'doctrine') !== false)
        {
            return;
        }

        # remove namespace = classname to filename conversion
        $filename = str_replace('clansuite\installation\\', '', $classname);

        # load
        include INSTALLATION_ROOT . 'controller' . DIRECTORY_SEPARATOR . $filename . '.php';
    }

    public function loadDoctrine()
    {
        // Initialize Doctrine2 Autoloader
        require ROOT . 'libraries/Doctrine/Common/ClassLoader.php';
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine', realpath(ROOT . 'libraries/'));
        $classLoader->register();

        // Register "Doctrine Extensions" with the Autoloader
        $classLoader = new \Doctrine\Common\ClassLoader('DoctrineExtensions', realpath(ROOT . 'libraries/'));
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

            \Clansuite\Installation_Helper::removeDirectory(__DIR__);

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
            $file = INSTALLATION_ROOT . 'languages' . DIRECTORY_SEPARATOR . $this->locale . '.install.php';

            if(is_file($file) === true)
            {
                include_once $file;

                $classname = '\Clansuite\Installation\Language\\' . $this->locale;
                $this->language = new $classname;

                $_SESSION['lang'] = $this->locale;
            }
            else
            {
                throw new \Clansuite\Installation_Exception('<span style="color:red">Language file missing: <strong>' . $file . '</strong></span>');
            }
        }
        catch(\Exception $e)
        {
            exit($e);
        }
    }

    public function getTotalNumberOfInstallationSteps()
    {
        $this->total_steps = \Clansuite\Installation_Helper::getTotalNumberOfSteps();
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
        $_SESSION = \Clansuite\Installation_Helper::array_merge_rec($_POST, $_SESSION);

        /**
         * STEP HANDLING
         */
        if(isset($_SESSION['step']))
        {
            $this->step = (int) intval($_SESSION['step']);

            if( isset($_POST['step_forward']) and ($this->step == $_POST['submitted_step']))
            {
                $this->step = $this->step + 1;
            }

            if(isset($_POST['step_backward']) and ($this->step == $_POST['submitted_step']))
            {
                $this->step = $this->step - 1;
            }
        }
        else
        {
            $this->step = 1;
        }

        if($this->step >= $this->total_steps)
        {
            $this->step = $this->total_steps;
        }

        if($this->step == 0)
        {
            $this->step = 1;
        }

        # remove not needed values
        unset($_SESSION['step_forward'], $_SESSION['step_backward'], $_SESSION['submitted_step']);
    }

    public function calculateInstallationProgress()
    {
        /**
         * Calculate Progress Percentage
         */
        $_SESSION['progress'] = \Clansuite\Installation_Helper::calculateProgress($this->step, $this->total_steps);
    }

    public function processPreviousStep()
    {
        # there isn't a controller before step 1
        if($this->step == 1)
        {
            return;
        }

        $previous_step = $this->step - 1;

        $prev_step_class = '\Clansuite\Installation\Step' . $previous_step;

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

                /**
                 * let's test if an errormessage was set during
                 * validateFormValues() and processValues()
                 */
                if($prev_step->error != '')
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

        $step_class = '\Clansuite\Installation\Step' . $this->step;

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

class Installation_Helper
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
        include ROOT . 'core/config/adapter/ini.php';

        /**
         * Throw not needed setting out, before data_array gets written to file.
         */
        unset($data_array['step_forward']);
        unset($data_array['lang']);
        unset($data_array['database']['create_database']);

        # base class is needed for \Koch\Config\Adpater\INI
        if(false === class_exists('AbstractConfig', false))
        {
            require ROOT . 'core/config/abstractconfig.php';
        }

        # read skeleton settings = minimum settings for initial startup
        # (not asked from user during installation, but required paths, default actions, etc.)
        $installer_config = \Koch\Config\Adapter\Ini::readConfig(INSTALLATION_ROOT . 'config.skeleton.ini');

        # array merge: overwrite the array to the left, with the array to the right, when keys identical
        $data_array = array_merge_recursive($data_array, $installer_config);

        # Write Config File to ROOT Directory
        if(false === \Koch\Config\Adapter\Ini::writeConfig(ROOT_APP . 'configuration/clansuite.php', $data_array))
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
     * by counting the number of classes named "\Clansuite\Installation_StepX".
     *
     * @return int Total number of install steps. $_SESSION['total_steps']
     */
    public static function getTotalNumberOfSteps()
    {
        # count the files only once
        if(isset($_SESSION['total_steps']))
        {
            return $_SESSION['total_steps'];
        }

        # get array with all installaton step files
        $step_files = glob('controller/step*.php');

        # count the number of files named "stepX"
        $_SESSION['total_steps'] = count($step_files);

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

        /**
         * All Module Entites
         */
        $dirs = glob(ROOT_APP . '/modules/' . '[a-zA-Z]*', GLOB_ONLYDIR);

        foreach($dirs as $key => $dir_path)
        {
            # Entity Path
            $entity_path = $dir_path . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR;

            if(is_dir($entity_path))
            {
                $model_dirs[] = $entity_path;
            }

            # Repository Path
            $repos_path = $dir_path . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR . 'repositories' . DIRECTORY_SEPARATOR;

            if(is_dir($repos_path))
            {
                $model_dirs[] = $repos_path;
            }
        }

        /**
         * Core Entities
         */
        $model_dirs[] = ROOT_APP . 'doctrine';

        # array_unique
        $model_dirs = array_keys(array_flip($model_dirs));

        #Clansuite_Debug::printR($model_dirs);
        return $model_dirs;
    }

    public static function getDoctrineEntityManager($connectionParams = null)
    {
        try
        {
            if(is_array($connectionParams) === false)
            {
                include ROOT . 'core/config/adapter/ini.php';

                # get clansuite config
                $clansuite_config = \Koch\Config\Adapter\INI::readConfig(ROOT_APP . 'configuration/clansuite.php');

                # reduce config array to the dsn/connection settings
                $connectionParams = $clansuite_config['database'];
            }

            # connect
            $config = new \Doctrine\DBAL\Configuration();
            $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            $connection->setCharset('UTF8');

            # get Event and Config
            $event = $connection->getEventManager();
            $config = new \Doctrine\ORM\Configuration();

            # add Table Prefix
            $prefix = $connectionParams['prefix'];
            $tablePrefix = new \DoctrineExtensions\TablePrefix\TablePrefix($prefix);
            $event->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

            # setup Cache
            $cache = new \Doctrine\Common\Cache\ArrayCache();
            $config->setMetadataCacheImpl($cache);

            # setup Proxy Dir
            $config->setProxyDir(realpath(ROOT . 'application\doctrine'));
            $config->setProxyNamespace('proxies');

            # setup Annotation Driver
            $driverImpl = $config->newDefaultAnnotationDriver(
                \Clansuite\Installation_Helper::getModelPathsForAllModules());
            $config->setMetadataDriverImpl($driverImpl);

            # finally: instantiate EntityManager
            $entityManager = \Doctrine\ORM\EntityManager::create($connection, $config, $event);

            return $entityManager;
        }
        catch(\Exception $e)
        {
            $msg = 'The initialization of Doctrine2 failed!' . NL . NL . 'Reason: ' . $e->getMessage();
            throw new \Clansuite\Installation_Exception($msg);
        }
    }
}

class Installation_Page
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
         * The just seem to be unused, in fact they are used by the included files.
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

        include INSTALLATION_ROOT . 'view/header.php';
        include INSTALLATION_ROOT . 'view/sidebar.php';
        include INSTALLATION_ROOT . 'view/step' . $step . '.php';
        include INSTALLATION_ROOT . 'view/footer.php';

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
        # if we already have an error message, then append the next one
        if($this->error != '')
        {
            $this->error .= $error;
        }
        else
        {
            $this->error = $error;
        }
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
 * Clansuite Installation Exception
 *
 * @category    Clansuite
 * @package     Installation
 * @subpackage  Exception
 */
class Installation_Exception extends \Exception
{
    /**
     * Define Exceptionmessage && Code via constructor
     * and hand it over to the parent Exception Class
     */
    public function __construct($message = '', $code = 0)
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
        $html .= '<head><title>Clansuite Installation Error</title>';
        $html .= '<link rel="stylesheet" href="../themes/core/css/error.css" type="text/css" />';
        $html .= '</head><body>';

        /**
         * Fieldset for Exception Message
         */
        $html .= '<fieldset class="error_yellow">';
        $html .= '<div style="float:left; padding: 15px;">';
        $html .= '<img src="images/Clansuite-Toolbar-Icon-64-error.png" style="border: 2px groove #000000;" alt="Clansuite Error Icon" /></div>';
        $html .= '<legend>Clansuite Installation Error</legend>';
        $html .= '<p><strong>' . $this->getMessage() . '</strong>';

        /**
         * Display a table with all pieces of information of the exception.
         */
        if(DEBUG == true)
        {
            $html .= '<table>';
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
        $html .= '<fieldset class="error_yellow">';
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

        # stfu...
        exit($html);
    }
}

?>