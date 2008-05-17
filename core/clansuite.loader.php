<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005 - onwards
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
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
 * TODO by vain:
 * 1. Check about default implementation and support of ini_set paths while autoloading!
 * 2. Check about specific file-extension and their support while autoloading,
 *    like ".inc" or ".inc.php". maybe it's faster, because hardcoded c+?
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
     * Overwrites Zend Engines _autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * @static
     * @access public
     */
    public static function register_autoload()
    {
        spl_autoload_register(array ('clansuite_loader','loadCoreClass'));
        spl_autoload_register(array ('clansuite_loader','loadClass'));
        spl_autoload_register(array ('clansuite_loader','loadFilter'));
    }

    /**
     * Require File
     * if file found
     *
     * @param string $filename The file to be required
     * @static
     * @access private
     *
     * @return bool
     */
    private static function requireFile($filename)
    {
        if (is_file($filename))
        {
            require ($filename);
            return true;
        }
        return false;
    }

    /**
     * Load a Class with name and dir
     * Extensions .class.php
     *
     * @param string $classname The class, which should be loaded
     * @param string $directory without start/end slashes
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadClass($classname, $directory = NULL)
    {
        $filename = ROOT . $directory . DIRECTORY_SEPARATOR . strtolower($classname) . '.class.php';
        #echo '<br>loaded Class => '. $filename;
        return self::requireFile($filename);
    }

    /**
     * loadCoreClass
     * requires: clansuite/core/class_name.class.php
     * require if found
     *
     * @param string $classname
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadCoreClass($classname)
    {
        $filename = ROOT_CORE . DIRECTORY_SEPARATOR . strtolower($classname) . '.class.php';
        #echo '<br>loaded Core-Class => '. $filename;
        return self::requireFile($filename);
    }

    /**
     * loadModul
     *
     * - constructs classname
     * - constructs absolute filename
     * - hands both to requireFile, returns true if successfull
     *
     * classname for modules is fixed 'module_' . $modname
     * absolute filename: e.g. 'clansuite/modules/'. $modname .'.module.php''
     *
     * @param string $modulename The name of the module, which should be loaded
     * @static
     * @access public
     *
     * @return boolean
     *
     * @todo: this 'module_" prefixing is complete bullshit, but we have to get going!
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
    	    #echo $spos; exit;
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
        #var_dump($modulinfos);

        # construct first part of filename
        $filename = ROOT_MOD . DIRECTORY_SEPARATOR . $modulinfos['1'] . DIRECTORY_SEPARATOR;

        # if there is a part [2], we have to require a submodule filename
        if(isset($modulinfos['2']))
        {
            # and if part [1] is "admin", we have to require a admin submodule filename
            if($modulinfos['2'] == 'admin')
            {
                # admin submodule filename, like news.admin.php
                $filename .= strtolower($modulinfos['1']) . '.admin.php';
                #echo '<br>loaded Admin SubModule => '. $filename;

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
        return self::requireFile($filename);
    }

    /**
     * loadFilter
     * requires: clansuite/core/filters/classname.filter.php
     * require if found
     *
     * @param string $classname The name of the filter class
     * @static
     * @access public
     *
     * @return boolean
     */
    public static function loadFilter($classname)
    {
        $filename = ROOT . DIRECTORY_SEPARATOR . '/core/filters/' . strtolower($classname) . '.filter.php';
        #echo '<br>loaded Filter-Class => '. $filename;
        return self::requireFile($filename);
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
     * @return object / method response
     * @todo: we have to profile this!
     */
    public static function callMethod($class, $method, array $arguments = array())
    {
        switch (count($arguments))
        {
            case 0:
                return new $class->method();
            case 1:
                return new $class->$method($arguments[0]);
            case 2:
                return new $class->$method($arguments[0], $arguments[1]);
            case 3:
                return new $class->$method($arguments[0], $arguments[1], $arguments[2]);
            default:
                return call_user_func_array( array($class, $method), $arguments );
        }
    }
}
?>