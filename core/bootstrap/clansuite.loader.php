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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
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
    private static $autoloader_map = array();

    /**
     * clansuite_loader:register_autoloaders();
     *
     * Overwrites Zend Engines __autoload cache with our own loader-functions
     * by registering single file loaders via spl_autoload_register($load_function)
     *
     * PHP Manual: spl_autoload_register
     * @link http://www.php.net/manual/de/function.spl-autoload-register.php
     */
    public static function register_autoloaders()
    {
        self::setupAutoloader();

        spl_autoload_register( 'Clansuite_Loader::loadViaMapping' );
        spl_autoload_register( 'Clansuite_Loader::autoload' );
    }

    public static function setupAutoloader()
    {
        # reset autoload logs
        if(defined('DEBUG') and DEBUG === true)
        {
            @unlink(ROOT_LOGS . 'autoload_hits.log');
            @unlink(ROOT_LOGS . 'autoload_misses.log');
        }

        # check if file for the autoloading map exists
        $file = ROOT_CONFIG . 'autoloader.config.php';
        if(is_file($file) === false)
        {
            # file not existant, create it
            $file_resource = fopen($file, 'a', false);
            fclose($file_resource);
            unset($file_resource);
        }
        else # load it
        {
            self::$autoloader_map = self::readAutoloadingMap();
        }
    }

    /**
     * Require File (and register it to the autoloading map file)
     *
     * @param string $filename The file to be required
     * @return bool True on success of require, false otherwise.
     */
    public static function requireFileAndMap($filename, $classname = null)
    {
        if (is_file($filename) === true)
        {
            include $filename;

            # if classname is given, its a mapping request
            if(isset($classname) and class_exists($classname, false) == true)
            {
                # add class and filename to the mapping array
                self::addToMapping($filename, $classname);

                # log for the autoloaded files
                /*if(DEBUG == true)
                {
                    self::logHit($filename);
                }*/
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
        if(is_file($filename) === true)
        {
            include $filename;

            if(null === $classname) # just file included
            {
                return true;
            }
            if(isset($classname) and class_exists($classname, false) === true)
            {
                return true;
            }
            else
            {
                return false;
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
        #Clansuite_Debug::firebug('Length of file_header :'. echo mb_strlen($file_header));

        file_put_contents(ROOT_CONFIG . 'autoloader.config.php', serialize($array));
    }

    /**
     * Reads the content of the autoloading map file and returns it unserialized.
     *
     * @return unserialized file content of autoload.config file
     */
    private static function readAutoloadingMap()
    {
        # Note: delete the autoloader.config.php file, if you get an unserialization error like "error at offset xy"
        return unserialize(file_get_contents(ROOT_CONFIG . 'autoloader.config.php'));
    }

    /**
     * Adds a new $classname to $filename mapping to the map array.
     * The new map array is written to file.
     *
     * @param $filename  Filename is the file to load.
     * @param $classname Classname is the lookup key for $filename.
     */
    private static function addToMapping($filename, $classname)
    {
        $filename = str_replace('//','\\', $filename);
        self::$autoloader_map = array_merge( (array) self::$autoloader_map, array( $classname => $filename ));

        self::writeAutoloadingMap(self::$autoloader_map);
    }

    /**
     * Loads a file by classname using the autoloader map.
     *
     * @param $classname The classname to look for in the autoloading map.
     * @return boolean True on file load, otherwise false.
     */
    public static function loadViaMapping($classname)
    {
        if (class_exists($classname, false) or interface_exists($classname, false))
        {
            return true;
        }

        if(isset(self::$autoloader_map[$classname]))
        {
            return self::requireFile(self::$autoloader_map[$classname]);
        }
    }

    /**
     * autoload
     *
     * @param string $classname The name of the factories class
     * @return boolean
     */
    public static function autoload($classname)
    {
        # check if class was already loaded
        if (class_exists($classname, false) or interface_exists($classname, false))
        {
            return true;
        }

        self::autoloadExclusions($classname);

        self::autoloadInclusions($classname);

        /**
         * Start Classname to Filename Mapping
         */

        $filename = mb_strtolower($classname);

        # strip 'clansuite_' from beginning of the string
        if(false !== mb_strpos($filename, 'clansuite_'))
        {
            $filename = mb_substr($filename, 10);
        }

        $filenames = array (
            # Core Class
            # clansuite/core/class_name.core.php
            ROOT_CORE . str_replace('_','',$filename) . '.core.php',
            # Factories
            # clansuite/core/factories/classname.factory.php
            # don't add factory is already in the classname
            ROOT_CORE . 'factories/' . str_replace('_','.',$filename) . '.php',
            # Filter
            # clansuite/core/filters/classname.filter.php
            ROOT_CORE . 'filters/' . mb_substr($filename, 7) . '.filter.php',
            # Event
            # clansuite/core/events/classname.class.php
            ROOT_CORE . 'events/' . $classname . '.class.php',
            # Viewhelper
            # clansuite/core/viewhelper/classname.core.php
            ROOT_CORE . 'viewhelper/' . str_replace('_','.',$filename) . '.core.php',
        );

        foreach($filenames as $filename)
        {
            if(self::requireFileAndMap($filename, $classname) === true)
            {
                return true;
            }
        }
    }

    /**
     * Includes a certain classname by using a manually maintained autoloading map.
     *
     * @param string $classname Classname to check for inclusion.
     * @return true if classname was included
     */
    public static function autoloadInclusions($classname)
    {
        # define component directories
        $datagrid_dir = ROOT_CORE . 'viewhelper' . DS . 'datagrid' . DS;
        $form_dir     = ROOT_CORE . 'viewhelper' . DS . 'form' . DS;

        # autoloading map
        $map = array(
        # datagrid mappings
        'Clansuite_Datagrid'                  => $datagrid_dir . 'datagrid.core.php',
        'Clansuite_Datagrid_Column'           => $datagrid_dir . 'datagridcol.core.php',
        # form mappings
        'Clansuite_Form'                      => $form_dir . 'form.core.php',
        'Clansuite_Formelement'               => $form_dir . 'formelement.core.php',
        'Clansuite_Form_Decorator'            => $form_dir . 'formdecorator.core.php',
        'Clansuite_Formelement_Decorator'     => $form_dir . 'formdecorator.core.php',
        'Clansuite_Formelement_Formgenerator' => $form_dir . 'formgenerator.core.php',
        'Clansuite_Array_Formgenerator'       => $form_dir . 'formgenerator.core.php',
        );

        # check if classname is in autoloading map
        if(isset($map[$classname]))
        {
            # get filename for that classname
            $filename = $map[$classname];

            # and include that one
            if(self::requireFileAndMap($filename, $classname) === true)
            {
                return true;
            } 
            unset($filename);
        }
    }

    /**
     * Excludes a certain classname from the autoloading.
     *
     * @param string $classname Classname to check for exclusion.
     * @return false (if classname is to exclude)
     */
    public static function autoloadExclusions($classname)
    {
        /**
         * Classname Exclusions
         */

        $classnames_to_exclude = array('Cs', 'Smarty_Internal');

        foreach($classnames_to_exclude as $classname_to_exclude)
        {
            if (false !== mb_strpos($classname, $classname_to_exclude))
            {
                #Clansuite_Debug::firebug('Class exluded: '.$classname.'. Found '.$classname_to_exclude.' in classname.');
                return false;
            }
        }

        /**
         * Exlude Doctrine, Smarty libraries from autoloading. They have their own autoloading handlers.
         * But include our own wrapper classes for both libraries.
         */

        # this means if 'Doctrine" is found, but not 'Clansuite_Doctrine', exclude from our autoloading
        if (false !== mb_strpos($classname, 'Doctrine') and false === mb_strpos($classname, 'Clansuite_Doctrine'))
        {
            return false;
        }

        # this means if 'Smarty" is found, but not 'Clansuite_Smarty', exclude from our autoloading
        if (false !== mb_strpos($classname, 'Smarty') and false === mb_strpos($classname, 'Clansuite_Smarty'))
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
        $modulename = mb_strtolower($modulename);

        # check for prefix 'clansuite_module_'
        $spos = mb_strpos($modulename, 'clansuite_module_');
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

        $classname = self::toUnderscoredUpperCamelCase($modulename);

        $moduleinfos = explode('_', $modulename);
        unset($modulename);
        $filename = ROOT_MOD;

        # if there is a part [3], we have to require a submodule filename
        if(isset($moduleinfos['3']))
        {
            # and if part [3] is "admin", we have to require a admin submodule filename
            if($moduleinfos['3'] == 'admin')
            {
                # admin submodule filename, like news.admin.php
                $filename .= $moduleinfos['2'] . DS . 'controller' . DS . $moduleinfos['2'] . '.admin.php';
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
     * Transforms a string from underscored_lower_case to Underscored_Upper_Camel_Case.
     *
     * @param string $string String in underscored_lower_case format.
     * @return $string String in Upper_Camel_Case.
     */
    public static function toUnderscoredUpperCamelCase($string)
    {
        $upperCamelCase = str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($string))));
        return $upperCamelCase;
    }
}
?>