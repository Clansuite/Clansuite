<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

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
 * @category    core
 * @subpackage  loader
 * @package     clansuite
 */
class Clansuite_Loader
{
    /**
     * clansuite_loader:register_autoload();
     *
     * Overwrites Zend Engines _autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * @access static
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
     * @param classname
     * @param directory without start/end slashes
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
     * @param classname
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
     * @param modname
     * @return boolean
     */
    public static function loadModul($modulename)
    {
        #$class_prefix = 'module_';
        #$classname = $class_prefix . strtolower($modname);
        $filename = ROOT_MOD . DIRECTORY_SEPARATOR . $modulename . DIRECTORY_SEPARATOR . strtolower($modulename) . '.module.php';
        #echo '<br>loaded Module => '. $filename;
        return self::requireFile($filename);
    }

    /**
     * loadFilter
     * requires: clansuite/core/filters/classname.filter.php
     * require if found
     *
     * @param classname
     * @return boolean
     */
    public static function loadFilter($classname)
    {
        $filename = ROOT . DIRECTORY_SEPARATOR . '/core/filters/' . strtolower($classname) . '.filter.php';
        #echo '<br>loaded Filter-Class => '. $filename;
        return self::requireFile($filename);
    }
}
?>