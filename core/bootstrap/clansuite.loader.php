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
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Loader
 *
 * This Loader overwrites the normal _autoload with our own user defined loading functions.
 * We register the multiple loaders in the constructor via a call to register_autoloaders().
 * There are several loader-functions, each seperated by the directories they are loading classes from.
 * Autoload will run, if a file is not found.
 *
 * PHP Manual: __autoload
 * @http://www.php.net/manual/en/language.oop5.autoload.php
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Loader
 */
class Clansuite_Loader
{
    private static $instance = null;

    private $autoloading_map = array();

    /**
     * returns an instance / singleton
     *
     * @return an instance of the loader
     */
    public static function getInstance()
    {
        if (self::$instance == 0)
        {
            self::$instance = new Clansuite_Loader();
        }
        return self::$instance;
    }

    public function __construct()
    {
        # reset autoload logs
        if(DEBUG == true)
        {
            @unlink(ROOT_LOGS . 'autoload_hits.log');
            @unlink(ROOT_LOGS . 'autoload_misses.log');
        }
        # check if file for the autoloading map exists
        $file = ROOT.'configuration/autoloader.config.php';
        if(is_file($file) === false)
        {
            # file not existant, create it
            fopen($file, 'a', false);
            fclose($file);
        }
        else # load it
        {
            $this->autoload_map = $this->readAutoloadingMap();
        }

        # finally!
        $this->register_autoloaders();
    }

    /**
     * clansuite_loader:register_autoloaders();
     *
     * Overwrites Zend Engines __autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * PHP Manual: spl_autoload_register
     * @link http://www.php.net/manual/de/function.spl-autoload-register.php
     */
    public function register_autoloaders()
    {
        spl_autoload_register(array ($this,'loadViaMapping'));
        spl_autoload_register(array ($this,'autoload'));
    }

    /**
     * Require File (and register it to the autoloading map file)
     *
     * @param string $filename The file to be required
     * @return bool True on success of require, false otherwise.
     */
    public function requireFileAndMap($filename, $classname = null)
    {
        if (is_file($filename) === true)
        {
            include $filename;

            # if classname is given, its a mapping request
            if(null === $classname and class_exists($classname, false) === true)
            {
                # add class and filename to the mapping array
                $this->addToMapping($filename, $classname);

                # log for the autoloaded files
                if(DEBUG == true)
                {
                    self::logHit($filename);
                }
            }

            return true;
        }
        else
        {
            # log missed autoloads
            if(DEBUG == true)
            {
                self::logMiss($filename);
            }

            return false;
        }
    }

    private static function logHit($filename)
    {
        $log = fopen( ROOT_LOGS . 'autoload_hits.log', 'a', false);
        fwrite($log, 'Autoload Hit: ' . str_replace('_', '/', $filename) . PHP_EOL);
        fclose($log);
    }

    private static function logMiss($filename)
    {
        $log = fopen( ROOT_LOGS . 'autoload_misses.log', 'a', false);
        fwrite($log, 'Autoload Miss: ' . str_replace('_', '/', $filename) . PHP_EOL);
        fclose($log);
    }

    /**
     * Require File if file found
     *
     * @param string $filename The file to be required
     * @param string $classname The classname (hopefully) inside this file.
     * @return bool
     */
    public static function requireFile($filename, $classname = null)
    {
        if (is_file($filename) === true)
        {
            include $filename;

            if(null === $classname and class_exists($classname, false) === true)
            {
                throw new Clansuite_Exception('Class '. $classname .' could not be found.');
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * writeAutoloadingMap($array)
     * Writes the autoload mapping data (the relation of a classname to a filename) into a file.
     * The target file is ROOT.'configuration/'.self::$autoloader
     * The content to be written is an associative array $array consisting of the old mapping array appended by a new mapping.
     * This procedure ensures, that the autoload mapping data is increased stepwise resulting in a decreasing number of autoloading tries.
     *
     * @param $array associative array
     */
    private static function writeAutoloadingMap($array)
    {
        $file_header  =  "; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>". PHP_EOL;
        $file_header .= '// This file was autogenerated on ' . date('d-m-Y H:i') . ' by Clansuite_Loader::writeAutoloadingMap().' . PHP_EOL;
        #Clansuite_Debug::firebug('Length of file_header :'. echo strlen($file_header));

        file_put_contents(ROOT.'configuration/autoloader.config.php', serialize($array));
    }

    /**
     * readAutoloadingMap
     *
     * Reads the content of the autoloading map file and returns it unserialized.
     */
    private static function readAutoloadingMap()
    {
        /**
         * Note: delete the autoloader.config.php file, if you get an unserialization error like "error at offset xy"
         */
        return unserialize(file_get_contents(ROOT.'configuration/autoloader.config.php'));
    }

    /**
     * Adds a new $classname to $filename mapping to the map array.
     * The new map array is written to file.
     *
     * @param $filename  Filename is the file to load.
     * @param $classname Classname is the lookup key for $filename.
     */
    private function addToMapping($filename, $classname)
    {
        $filename = str_replace('//','\\', $filename);
        $this->autoloading_map = array_merge($this->autoloading_map, array( $classname => $filename ));
    }

    function __destruct()
    {
        self::writeAutoloadingMap($this->autoloading_map);
    }

    /**
     * str_replace method to present the incomming classname as a proper filename
     *
     * @param $filename
     * @param $path_type
     */
    private static function convertClassnameToFilename($classname, $type = null)
    {
        $filename = strtolower($classname);

        # strip 'clansuite_' from beginning of the string
        if(false !== strpos($filename, 'clansuite_'))
        {
            $filename = substr($filename, 10); # formerly str_replace('clansuite_','',$classname);
        }

        # slash fix
        $filename = str_replace('//','\\', $filename);

        switch($type)
        {
            case 'filters':
                $filename = substr($filename, 7);
                break;

            case 'core':
                $filename =  str_replace('_','',$filename);
                break;

            case 'events':
                # $filename =
                break;

            default:
                # replace the classname "renderer_factory" with "renderer.factory" for the correct filename
                $filename = str_replace('_','.',$filename);
        }

        #Clansuite_Debug::firebug($filename);

        return $filename;
    }

    /**
     * Loads a file by classname using the autoloader map.
     *
     * @param $classname The classname to look for in the autoloading map.
     * @return boolean True on file load, otherwise false.
     */
    public function loadViaMapping($classname)
    {
        if (class_exists($classname, false) or interface_exists($classname, false))
        {
            return true;
        }

        self::autoloadExclusions($classname);

        if(isset($this->autoload_map[$classname]))
        {
            return self::requireFile($this->autoload_map[$classname]);
        }
    }

    /**
     * autoload
     *
     * @param string $classname The name of the factories class
     * @return boolean
     */
    public function autoload($classname)
    {
        if (class_exists($classname, false) or interface_exists($classname, false))
        {
            return true;
        }

        self::autoloadExclusions($classname);

        $filenames = array (
            # Core Class
            # clansuite/core/class_name.class.php
            ROOT_CORE . self::convertClassnameToFilename($classname, 'core') . '.core.php',
            # Factories
            # clansuite/core/factories/classname.php
            ROOT_CORE . 'factories/' . self::convertClassnameToFilename($classname) . '.php',
            # Filter
            # clansuite/core/filters/classname.filter.php
            ROOT_CORE . 'filters/' . self::convertClassnameToFilename($classname, 'filters') . '.filter.php',
            # Event
            # clansuite/core/events/classname.class.php
            ROOT_CORE . 'events/' . strtolower($classname) . '.class.php',
            # Viewhelper
            # clansuite/core/viewhelper/classname.core.php
            ROOT_CORE . 'viewhelper/' . self::convertClassnameToFilename($classname) . '.core.php',
        );

        foreach($filenames as $filename)
        {
            if($this->requireFileAndMap($filename, $classname) == true)
            {
                return true;
            }
        }
    }

    /**
     * Exlude Doctrine, Smarty from autoloading, because they have their own autoloading handler
     * Include our own wrapper classes for these libraries
     */
    public static function autoloadExclusions($classname)
    {
        if (false !== strpos($classname, 'Doctrine') and
            false !== strpos($classname, 'Smarty') and
            false === strpos($classname, 'Clansuite_Smarty') and
            false === strpos($classname, 'Clansuite_Doctrine'))
        {
            return false;
        }
    }

    /**
     * loadModul
     *
     * - constructs classname
     * - constructs absolute filename
     * - hands both to requireFile, returns true if successfull
     *
     * The classname for modules is prefixed 'module_' . $modname
     * The filename is 'clansuite/modules/'. $modname .'.module.php'
     *
     * String Variants to consider:
     * 1) admin
     * 2) module_admin
     * 3) module_admin_menueditor
     *
     * @param string $modulename The name of the module, which should be loaded.
     * @return boolean
     */
    public static function loadModul($modulename)
    {
        $modulename = strtolower($modulename);

        # check for prefix 'clansuite_module_'
        $spos = strpos($modulename, 'clansuite_module_');
        if (is_int($spos) and ($spos==0))
        {
            # ok, 'clansuite_module_' is prefixed, do nothing
            unset($spos);
        }
        else
        {
            # add the prefix
            $modulename = 'clansuite_module_'. $modulename;
            unset($spos);
        }

        /**
         * now we have a common string like 'clansuite_module_admin_menu' or 'clansuite_module_news'
         * which we split at underscore, via explode, resulting in an array
         * like: Array ( [0] => clansuite [1] => module [2] => admin [3] => menu )
         * or  : Array ( [0] => clansuite [1] => module [2] => news )
         */
        $moduleinfos = explode('_', $modulename);
        $classname = '';

        $i = 0;
        foreach ($moduleinfos as $moduleinfo)
        {
            if($i == 0)
            {
                $classname .= ucfirst($moduleinfo);
                ++$i;
            }
            else
            {
                $classname .= '_'.ucfirst($moduleinfo);
            }
        }

        $filename = ROOT_MOD;

        # if there is a part [3], we have to require a submodule filename
        if(isset($moduleinfos['3']))
        {
            # and if part [3] is "admin", we have to require a admin submodule filename
            if($moduleinfos['3'] == 'admin')
            {
                # admin submodule filename, like news.admin.php
                $filename .= $moduleinfos['2'] . DS . 'controller' . DS . $moduleinfos['2'] . '.admin.php';

                $classname .= 'Admin';
            }
            else
            {
                # normal submodule filename, like menueditor.module.php
                $filename .= $moduleinfos['3'] . DS . 'controller' . DS . $moduleinfos['3'] . '.module.php';
            }
        }
        else
        {
            # module filename
            $filename .= $moduleinfos['2'] . DS . 'controller' . DS . $moduleinfos['2'] . '.module.php';
        }

        return self::requireFile($filename, $classname);
    }

    /**
     * loadNamespace
     * requires a file by it's namespace
     * PHP5.3+
     *
     * Usage:
     * a) no alias (note: first slash "\" determines absolute path)
     * $httprequest = new \com\clansuite\core\httprequest;
     * b) with namespace alias
     * use \com\clansuite\core as core;
     * $httprequest = new core\httprequest;
     *
     * @param string $classname The name of the class to autoload
     * @return boolean
     */
    public function loadNamespace($classname)
    {
        $filename = str_replace('//', '/', $classname ) . '.php';
        return  $this->requireFileAndMap($filename, $classname);
    }

    /**
     * callClassMethod
     *
     * This method is some kind of performance tweak.
     * It's thought as a functional replacement of call_user_func_array,
     * because it's too slow calling stuff.
     * So instead we call the class->method directly with up to 3 parameters.
     * After that we use call_user_func_array.
     * Looks stupid, but may result in an speedup while calling!
     *
     * @param $class Takes name of the class or the class object itself.
     * @param $method Methodname to call.
     * @param $arguments Array of Arguments for the Method Call.
     *
     * @return object / method response
     */
    public static function callClassMethod($class, $method, array $arguments = array())
    {
        # if $class is not an object, we have to initialize the class
        if (false === is_object($class))
        {
            # ensure type
            $classname  = (string) $class;
            $method     = (string) $method;

            # initalize class
            $class = new $classname;
        }

        switch (count($arguments))
        {
            case 0:
                return $class->$method();
            case 1:
                return $class->$method($arguments[0]);
            case 2:
                return $class->$method($arguments[0], $arguments[1]);
            case 3:
                return $class->$method($arguments[0], $arguments[1], $arguments[2]);
            default:
                return call_user_func_array( array($class, $method), $arguments );
        }
    }

    /**
     * callMethod
     *
     * Like callClassMethod, but not for classes :D
     *
     * This method is some kind of performance tweak.
     * It's thought as a functional replacement of call_user_func_array,
     * because it's too slow calling stuff.
     * So instead we call the method directly with up to 3 parameters.
     * After that we use call_user_func_array.
     * Looks stupid, but may result in an speedup while calling!
     *
     * @param $class Takes name of the class or the class object itself.
     * @param $method Methodname to call.
     * @param $arguments Array of Arguments for the Method Call.
     *
     * @return object / method response
     */
    public static function callMethod($method, array $arguments = array())
    {
        switch (count($arguments))
        {
            case 0:
                return $method();
            case 1:
                return $method($arguments[0]);
            case 2:
                return $method($arguments[0], $arguments[1]);
            case 3:
                return $method($arguments[0], $arguments[1], $arguments[2]);
            default:
                return call_user_func_array( $method, $arguments );
        }
    }
}
?>