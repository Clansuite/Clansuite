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
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
 *
 * Note by vain:
 * This is not based on usage of path environment via ini_set('include_path', "xy" ... '); ,
 * because it's too complex for me - i simply cant remove paths, so i don't want to mess around with it.
 * Sorry, but include_path is not my favorite choice when it comes to path assignments.
 * Any ideas or help on that topic? Report to board or contact me.
 *
 * @todo by vain:
 * 1. Check about default implementation and support of ini_set paths while autoloading!
 * 2. Check about specific file-extension and their support while autoloading,
 *    like ".inc" or ".inc.php". maybe it's faster, because hardcoded c+?
 *
 * PHP Manual: __autoload
 * @http://www.php.net/manual/en/language.oop5.autoload.php
 *
 * @package     clansuite
 * @category    core
 * @subpackage  loader
 */
class Clansuite_Loader
{
    /**
     * clansuite_loader:register_autoload();
     *
     * Overwrites Zend Engines __autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * PHP Manual: spl_autoload_register
     * @link http://www.php.net/manual/de/function.spl-autoload-register.php
     * @static
     * @access public
     */
    public static function register_autoload()
    {
        spl_autoload_register(array (__CLASS__,'loadCoreClass'));
        #spl_autoload_register(array (__CLASS__,'loadClass'));
        spl_autoload_register(array (__CLASS__,'loadFilter'));
        spl_autoload_register(array (__CLASS__,'loadFactory'));
    }

    /**
     * Require File
     * if file found
     *
     * @param string $fileName The file to be required
     * @static
     * @access private
     *
     * @return bool
     */
    private static function requireFile($fileName)
    {
        if (is_file($fileName))
        {
            require_once $fileName;
            return true;
        }
        return false;
    }

    /**
     * str_replace method to presents the incomming classname
     * as a proper filename
     */
    private static function prepareClassnameAsFilename($className)
    {
        # replace "Clansuite_View_Factory" for the correct filename
        $className = str_replace('Clansuite_','',$className);
        # replace the classname "view_factory" with "view.factory" for the correct filename
        $className = str_replace('_','.',$className);

        return $className;
    }

    /**
     * Load a Class with name and dir
     * Extensions .class.php
     *
     * @param string $className The class, which should be loaded
     * @param string $directory without start/end slashes
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadClass($className, $directory = null)
    {
        if (class_exists($className, false))
        {
            return false;
        }
        $fileName = ROOT . $directory . strtolower($className) . '.class.php';
        #echo '<br>loaded Class => '. $fileName;
        return self::requireFile($fileName);
    }

    /**
     * Load a Library by name and dir
     * Extensions .php
     *
     * @param string $className The class, which should be loaded
     * @param string $directory without start/end slashes
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadLibrary($className, $directory = null)
    {
        if (class_exists($className, false))
        {
            return false;
        }
        $fileName = ROOT_LIBRARIES;
        if ( $directory != null )
        {
            $fileName .= $directory . DS;
        }
        $fileName .= $className . '.php';
        #echo '<br>loaded Library => '. $fileName;
        return self::requireFile($fileName);
    }

    /**
     * loadCoreClass
     * requires: clansuite/core/class_name.class.php
     * require if found
     *
     * @param string $className
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadCoreClass($className)
    {
        if (class_exists($className, false))
        {
            return false;
        }

        $className = self::prepareClassnameAsFilename($className);

        $fileName = ROOT_CORE . strtolower($className) . '.core.php'; #@todo rename files from .class.php to .core.php  + commit
        #echo '<br>loaded Core-Class => '. $fileName;
        return self::requireFile($fileName);
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
     * @param string $modulename The name of the module, which should be loaded.
     * @static
     * @access public
     *
     * @return boolean
     *
     * String Variants to consider:
     * 1) admin
     * 2) module_admin
     * 3) module_admin_menueditor
     *
     */
    public static function loadModul($modulename)
    {
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
        return self::requireFile($fileName);
    }

    /**
     * loadFilter
     * requires: clansuite/core/filters/classname.filter.php
     * require if found
     *
     * @param string $className The name of the filter class
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadFilter($className)
    {
        if (class_exists($className, false))
        {
            return false;
        }

        $fileName = ROOT . 'core/filters/' . strtolower($className) . '.filter.php';
        #echo '<br>loaded Filter-Class => '. $fileName;
        return self::requireFile($fileName);
    }

    /**
     * loadFactories
     * requires: clansuite/core/factories/classname.filter.php
     * require if found
     *
     * @param string $className The name of the factories class
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadFactory($className)
    {
        if (class_exists($className, false))
        {
            return false;
        }

        $className = self::prepareClassnameAsFilename($className);

        $fileName = ROOT . 'core/factories/' . strtolower($className) . '.php';
        #echo '<br>loaded Factory-Class => '. $fileName;
        return self::requireFile($fileName);
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
            $className  = (string) $class;
            $method     = (string) $method;

            # initalize class
            $class = new $className;
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