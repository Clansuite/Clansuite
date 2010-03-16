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
    private static $autoloaderMapFile = 'autoloader.map.php';

    /**
     * clansuite_loader:register_autoload();
     *
     * Overwrites Zend Engines __autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * PHP Manual: spl_autoload_register
     * @link http://www.php.net/manual/de/function.spl-autoload-register.php
     */
    public static function register_autoload()
    {
        @unlink(ROOT_LOGS . 'autoload_misses.log');
        
        # check if autoloading map exists, else create file
        $file = ROOT.'configuration/'.self::$autoloaderMapFile;
        if(is_file($file) == false)
        {
            @fopen($file, 'a', false); @fclose($file);            
        }
        
        spl_autoload_register(array (__CLASS__,'loadViaMapping'));
        spl_autoload_register(array (__CLASS__,'autoload'));
    }

    /**
     * Require File
     * if file found
     *
     * @param string $fileName The file to be required
     * @return bool
     */
    private static function requireFile($fileName, $classname = null)
    {
        if (is_file($fileName))
        {
            require $fileName;

            # log for the autoloaded files
            if(DEBUG == true)
            {
                $log = @fopen( ROOT_LOGS . 'autoload_hits.log', 'a', false);
                @fwrite($log, 'Autoloaded file: ' . str_replace('_', '/', $fileName) . PHP_EOL);
                fclose($log);
            }

            # if classname is given, its a mapping request
            if(false == is_null($classname))
            {
                # add class and filename to the mapping array
                self::addToMapping($fileName, $classname);
            }

            return true;
        }

            # log missed autoloads
            if(DEBUG == true)
            {                
                $log = @fopen( ROOT_LOGS . 'autoload_misses.log', 'a', false);
                @fwrite($log, 'Autoloaded file: ' . str_replace('_', '/', $fileName) . PHP_EOL);
                fclose($log);
            }
        
        return false;
    }

    private static function writeAutoloadingMap($array)
    {
        file_put_contents(ROOT.'configuration/'.self::$autoloaderMapFile,serialize($array));
    }

    private static function readAutoloadingMap()
    {
        return unserialize(file_get_contents(ROOT.'configuration/'.self::$autoloaderMapFile));
    }

    private static function addToMapping($filename, $classname)
    {
        $autoloading_map = (array) self::readAutoloadingMap();

        $new_path_mapping = array( $classname => $filename );

        $merged_map = array_merge($autoloading_map, $new_path_mapping);

        self::writeAutoloadingMap($merged_map);
    }

    /**
     * str_replace method to present the incomming classname
     * as a proper filename
     */
    private static function convertClassnameToFilename($classname)
    {
        # strtolower
        $classname = strtolower($classname);

        # replace "clansuite_renderer_factory" for the correct filename
        $classname = str_replace('clansuite_','',$classname);

        # replace the classname "renderer_factory" with "renderer.factory" for the correct filename
        $classname = str_replace('_','.',$classname);

        return $classname;
    }

    public static function loadViaMapping($classname)
    {
        if (class_exists($classname, false) or interface_exists($classname, false))
        {
            return true;
        }
    
        $autoloading_map = self::readAutoloadingMap();
        
        if(isset($autoloading_map[$classname]))
        {
            #Clansuite_Xdebug::PrintR($autoloading_map);
            
            return self::requireFile($autoloading_map[$classname]);
        }
    }
    
    /**
     * load
     *
     * @param string $classname The name of the factories class
     * @return boolean
     */
    public static function autoload($classname)
    {
        if (class_exists($classname, false) or interface_exists($classname, false))
        {
            return true;
        }
        
        $filenames = array (        
                        # Event = clansuite/core/events/classname.class.php
                        ROOT_CORE . 'events/' . strtolower($classname) . '.class.php',
                        # Core Class = clansuite/core/class_name.class.php
                        ROOT_CORE . self::convertClassnameToFilename($classname) . '.core.php',
                        # Factories = clansuite/core/factories/classname.php
                        ROOT_CORE . 'factories/' . self::convertClassnameToFilename($classname). '.php',
                        # Filter = clansuite/core/filters/classname.filter.php
                        ROOT_CORE . 'filters/' . substr($classname, 17) . '.filter.php',                        
                      );
                      
        foreach($filenames as $filename)
        {
            if(self::requireFile($filename, $classname) == true)
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
        $fileName = ROOT_MOD . $modulinfos['1'] . DS;

        # if there is a part [2], we have to require a submodule filename
        if(isset($modulinfos['2']))
        {
            # and if part [1] is "admin", we have to require a admin submodule filename
            if($modulinfos['2'] == 'admin')
            {
                # admin submodule filename, like news.admin.php
                $fileName .= strtolower($modulinfos['1']) . '.admin.php';
                #echo '<br>loaded Admin SubModule => '. $fileName;

                $classname .= 'Admin';
            }
            else
            {
                # normal submodule filename, like menueditor.module.php
                $fileName .= strtolower($modulinfos['2']) . '.module.php';
                #echo '<br>loaded SubModule => '. $fileName;
            }
        }
        else
        {
            # module filename
            $fileName .= strtolower($modulinfos['1']) . '.module.php';
            #echo '<br>loaded Module => '. $fileName;
        }

        return self::requireFile($fileName, $classname);
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
    public static function loadNamespace($classname)
    {
        $filename = str_replace('//', '/', $classname ) . '.php';
        return  self::requireFile($filename, $classname);
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