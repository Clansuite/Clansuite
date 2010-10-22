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
        spl_autoload_register( 'Clansuite_Loader::autoload' );
    }

    public static function loadMapFile()
    {
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
    public static function includeFileAndMap($filename, $classname)
    {
        include $filename;

        # add class and filename to the mapping array
        self::addToMapping($filename, $classname);

        return true;
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
     * Loads a file by classname using the autoloader map file
     *
     * @param $classname The classname to look for in the autoloading map.
     * @return boolean True on file load, otherwise false.
     */
    public static function autoloadByMappingFile($classname)
    {
        # load the mapping file
        self::loadMapFile();

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
        if (true === class_exists($classname, false) or true === interface_exists($classname, false))
        {
            return true;
        }

        /**
         * if the classname is to exclude, then
         * 1) stop autoloading immediately
         *
         * Note: autoloadExclusions returns false if classname was found
         */
        if(false === self::autoloadExclusions($classname))
        {
            return false;
        }

        /**
         * try to load the file by searching the
         * 2) hardcoded mapping table
         *
         * autoloadInclusions returns true if classname was included
         */
        if(true === self::autoloadInclusions($classname))
        {
            return true;
        }

        /**
         * try to load the file by searching the
         * 3) automatically created mapping table.
         *
         * Note: the mapping table is loaded from file.
         */
        if(true === self::autoloadByMappingFile($classname))
        {
            return true;
        }

        /**
         * Try to load the file by searching
         * 4 ) several paths
         *
         * Note: If the file is found, it's added to the mapping file.
         * The next time the file is requested, it will be loaded
         * via the method above (3)!
         */
        if(true === self::autoloadTryPathsAndMap($classname))
        {
            return true;
        }

        /**
         * if classname was not found by any of the above methods
         * 5) Autoloading Fail
         */
        return false;
    }

    public static function autoloadTryPathsAndMap($classname)
    {
        # Start Classname to Filename Mapping
        $filename = mb_strtolower($classname);

        # strip 'clansuite_' from beginning of the string
        if(false !== mb_strpos($filename, 'clansuite_'))
        {
            $filename = mb_substr($filename, 10);
        }

        # Core Class
        # clansuite/core/class_name.core.php
        $file = ROOT_CORE . str_replace('_','',$filename) . '.core.php';
        if(is_file($file))
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Event
        # clansuite/core/events/classname.class.php
        $file = ROOT_CORE . 'events' . DS . $classname . '.class.php';
        if(is_file($file))
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Filter
        # clansuite/core/filters/classname.filter.php
        $file = ROOT_CORE . 'filters' . DS . mb_substr($filename, 7) . '.filter.php';
        if(is_file($file))
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Viewhelper
        # clansuite/core/viewhelper/classname.core.php
        $file = ROOT_CORE . 'viewhelper' . DS . str_replace('_','.',$filename) . '.core.php';
        if(is_file($file))
        {
            return self::includeFileAndMap($file, $classname);
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
        # /core/config
        'Clansuite_Config_Base'               => ROOT_CORE . 'config'. DS . 'config.base.php',
        'Clansuite_Config_INI'                => ROOT_CORE . 'config'. DS . 'ini.config.php',
        'Clansuite_Config_XML'                => ROOT_CORE . 'config'. DS . 'xml.config.php',
        'Clansuite_Config_YAML'               => ROOT_CORE . 'config'. DS . 'yaml.config.php',
        'Clansuite_Renderer_Base'             => ROOT_CORE . 'renderer' . DS . 'renderer.base.php',
        # /core
        'Clansuite_Staging'                   => ROOT_CORE . 'staging.core.php',
        'Clansuite_UTF8'                      => ROOT_CORE . 'utf8.core.php',
        'Clansuite_Config'                    => ROOT_CORE . 'config.core.php',
        'Clansuite_HttpRequest'               => ROOT_CORE . 'httprequest.core.php',
        'Clansuite_HttpResponse'              => ROOT_CORE . 'httpresponse.core.php',
        'Clansuite_FilterManager'             => ROOT_CORE . 'filtermanager.core.php',
        'Clansuite_Localization'              => ROOT_CORE . 'localization.core.php',
        'Clansuite_Inputfilter'               => ROOT_CORE . 'inputfilter.core.php',
        'Clansuite_User'                      => ROOT_CORE . 'user.core.php',
        'Clansuite_Router'                    => ROOT_CORE . 'router.core.php',
        'Clansuite_Security'                  => ROOT_CORE . 'security.core.php',
        'Clansuite_Session'                   => ROOT_CORE . 'session.core.php',
        'Clansuite_Filter_Interface'          => ROOT_CORE . 'filtermanager.core.php',
        'Clansuite_Gettext_Extractor'         => ROOT_CORE . 'gettext.core.php',
        'Clansuite_DoorKeeper'                => ROOT_CORE . 'doorkeeper.core.php',
        'Clansuite_Functions'                 => ROOT_CORE . 'functions.core.php',
        'Clansuite_XML2JSON'                  => ROOT_CORE . 'xml2json.core.php',
        'Clansuite_Doctrine'                  => ROOT_CORE . 'doctrine.core.php',
        'Clansuite_Front_Controller'          => ROOT_CORE . 'frontcontroller.core.php',
        'Clansuite_Module_Controller'         => ROOT_CORE . 'modulecontroller.core.php',
        'Clansuite_EventDispatcher'           => ROOT_CORE . 'eventdispatcher.core.php',
        'Clansuite_Breadcrumb'                => ROOT_CORE . 'breadcrumb.core.php',
        # /core/factories
        'Clansuite_Config_Factory'            => ROOT_CORE . 'factories/config.factory.php',
        'Clansuite_Renderer_Factory'          => ROOT_CORE . 'factories/renderer.factory.php',
        'Clansuite_Logger_Factory'            => ROOT_CORE . 'factories/logger.factory.php',
        'Clansuite_Cache_Factory'             => ROOT_CORE . 'factories/cache.factory.php',
        # /viewhelper/datagrid
        'Clansuite_Datagrid'                  => $datagrid_dir . 'datagrid.core.php',
        'Clansuite_Datagrid_Column'           => $datagrid_dir . 'datagridcol.core.php',
        # /viewhelper/form
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
            if(true === self::requireFile($filename, $classname))
            {
                return true;
            }
            else
            {
                return false;
            }
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
        # define parts of classnames for exclusion
        $classnames_to_exclude = array('Cs', 'Smarty_Internal');

        foreach($classnames_to_exclude as $classname_to_exclude)
        {
            if (false !== mb_strpos($classname, $classname_to_exclude))
            {
                # Clansuite_Debug::firebug('Class exluded: '.$classname.'. Found '.$classname_to_exclude.' in classname.');
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
        if (false !== mb_strpos($classname, 'Smarty') and
            false === mb_strpos($classname, '_Smarty'))
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