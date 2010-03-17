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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_Loader
 *
 * This Loader overwrites the normal _autoload with our own user defined loading functions.
 * We register the multiple loaders in the constructor.
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

    private static $autoloaderMapFile = 'autoloader.config.php';
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
        $file = ROOT.'configuration/'.self::$autoloaderMapFile;
        if(is_file($file) == false)
        {
            # file not existant, create it
            @fopen($file, 'a', false); @fclose($file);
        }
        else # load it
        {
            $this->autoload_map = $this->readAutoloadingMap();
        }

        # finally!
        $this->register_autoload();
    }

    /**
     * clansuite_loader:register_autoload();
     *
     * Overwrites Zend Engines __autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * PHP Manual: spl_autoload_register
     * @link http://www.php.net/manual/de/function.spl-autoload-register.php
     */
    public function register_autoload()
    {
        spl_autoload_register(array ($this,'loadViaMapping'));
        spl_autoload_register(array ($this,'autoload'));
    }

    /**
     * Require File
     * if file found
     *
     * @param string $filename The file to be required
     * @return bool
     */
    private function requireFileAndMap($filename, $classname = null)
    {
        if (is_file($filename))
        {
            require $filename;

            # log for the autoloaded files
            if(DEBUG == true)
            {
                $log = @fopen( ROOT_LOGS . 'autoload_hits.log', 'a', false);
                @fwrite($log, 'Autoloaded file: ' . str_replace('_', '/', $filename) . PHP_EOL);
                fclose($log);
            }

            # if classname is given, its a mapping request
            if(false == is_null($classname))
            {
                # add class and filename to the mapping array
                $this->addToMapping($filename, $classname);
            }

            return true;
        }
        else
        {
            # log missed autoloads
            if(DEBUG == true)
            {
                $log = @fopen( ROOT_LOGS . 'autoload_misses.log', 'a', false);
                @fwrite($log, 'Autoloaded file: ' . str_replace('_', '/', $filename) . PHP_EOL);
                fclose($log);
            }

            return false;
        }
    }

    /**
     * Require File if file found
     *
     * @param string $filename The file to be required
     * @param string $classname The classname (hopefully) inside this file
     * @return bool
     */
    private static function requireFile($filename, $classname = null)
    {
        if (is_file($filename))
        {
            require $filename;
            return true;
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
        $file_header =  "; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>". PHP_EOL;
        $file_header .= '// This file was autogenerated on ' . date('d-m-Y H:i') . ' by Clansuite_Loader::writeAutoloadingMap().' . PHP_EOL;
        # Length of file_header = echo strlen($file_header);

        file_put_contents(ROOT.'configuration/'.self::$autoloaderMapFile, $file_header . serialize($array));
    }

    /**
     * readAutoloadingMap reads the content of the autoloading map file.
     */
    private static function readAutoloadingMap()
    {
        return unserialize(file_get_contents(ROOT.'configuration/'.self::$autoloaderMapFile, null, null, 164));
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
        $merged_map = array_merge($this->autoloading_map, array( $classname => $filename ));

        self::writeAutoloadingMap($merged_map);
    }

    /**
     * str_replace method to present the incomming classname
     * as a proper filename
     */
    private static function convertClassnameTofilename($classname)
    {
        # strtolower
        $classname = strtolower($classname);

        # replace "clansuite_renderer_factory" for the correct filename
        $classname = str_replace('clansuite_','',$classname);

        # replace the classname "renderer_factory" with "renderer.factory" for the correct filename
        $classname = str_replace('_','.',$classname);

        return $classname;
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

        $autoloading_map = self::readAutoloadingMap();

        if(isset($autoloading_map[$classname]))
        {
            #Clansuite_Xdebug::firebug($autoloading_map);

            #return $this->requireFileAndMap($autoloading_map[$classname]);
            return self::requireFile($autoloading_map[$classname]);
        }
    }

    /**
     * load
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

        $filenames = array (
                        # Core Class = clansuite/core/class_name.class.php
                        ROOT_CORE . self::convertClassnameTofilename($classname) . '.core.php',
                        # Factories = clansuite/core/factories/classname.php
                        ROOT_CORE . 'factories/' . self::convertClassnameTofilename($classname). '.php',
                        # Filter = clansuite/core/filters/classname.filter.php
                        ROOT_CORE . 'filters/' . substr($classname, 17) . '.filter.php',
                        # Event = clansuite/core/events/classname.class.php
                        ROOT_CORE . 'events/' . strtolower($classname) . '.class.php',
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
        $classname = 'Clansuite';

        # check for prefix 'module_'
        $spos=strpos(strtolower($modulename), 'module_');
        if (is_int($spos) && ($spos==0))
        {
            # ok, 'module_' is prefixed, do nothing
        }
        else
        {
            # add the prefix
            $modulename = 'module_'. strtolower($modulename);
        }

        /**
         * now we have a common string like 'module_admin_menueditor' or 'module_news'
         * which we split at underscore, via explode
         * like: Array ( [0] => module [1] => admin [2] => menueditor )
         * or  : Array ( [0] => module [1] => news )
         */
        $modulinfos = explode("_", $modulename);

        # construct first part of filename
        $filename = ROOT_MOD . $modulinfos['1'] . DS;

        # if there is a part [2], we have to require a submodule filename
        if(isset($modulinfos['2']))
        {
            # and if part [1] is "admin", we have to require a admin submodule filename
            if($modulinfos['2'] == 'admin')
            {
                # admin submodule filename, like news.admin.php
                $filename .= strtolower($modulinfos['1']) . '.admin.php';
                #echo '<br>loaded Admin SubModule => '. $filename;

                $classname .= 'Admin';
            }
            else
            {
                # normal submodule filename, like menueditor.module.php
                $filename .= strtolower($modulinfos['2']) . '.module.php';
                #echo '<br>loaded SubModule => '. $filename;
            }
        }
        else
        {
            # module filename
            $filename .= strtolower($modulinfos['1']) . '.module.php';
            #echo '<br>loaded Module => '. $filename;
        }

        # @todo move Module loading into the autoloader
        #Clansuite_Xdebug::firebug($filename);
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
     * callMethod
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
    public static function callMethod($class, $method, array $arguments = array())
    {
        # if $class is not an object, we have to initialize the class
        if (!is_object($class))
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
}
?>