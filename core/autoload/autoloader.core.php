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
 * This Loader overwrites the Zend Engines_autoload with our own user defined loading functions.
 * The main function of this class is autoload() it's registered via spl_autoload_register($load_function).
 * There are several loader-functions, which are used by autoload().
 * Autoload will run, if a file is not found.
 * The procedure is (1) exclusions, (2) inclusions, (3) mapping file or apc, (4) mapping table.
 *
 * Usage:
 * 1) include this file
 * 2) spl_autoload_register('Clansuite_Loader::autoload');
 *
 * PHP Manual: __autoload
 * @link http://www.php.net/manual/en/language.oop5.autoload.php
 *
 * PHP Manual: spl_autoload_register
 * @link http://www.php.net/manual/de/function.spl-autoload-register.php
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Loader
 */
class Clansuite_Loader
{
    /**
     * The Constant USE_APC is used in addToMapping().
     * It toggles the usage of APC (when true) or File (when false) for reading and writing the classmap array.
     */
    const USE_APC = false;

    /**
     * @var array Generated Classmap from File or APC.
     */
    private static $autoloader_map = array();

    /**
     * @var array Manually defined Classmap, @see autoloadInclusions().
     */
    private static $inclusions_map = array();

    /**
     * Autoloads a Class
     *
     * @param string $classname The name of the class
     * @return boolean True on successful class loading, false otherwise.
     */
    public static function autoload($classname)
    {
        /**
         * if the classname is to exclude, then
         * 1) stop autoloading immediately by
         * returning false, to save any pointless processing
         */
        if(true === self::autoloadExclusions($classname))
        {
            return false;
        }

        /**
         * try to load the file by searching the
         * 2) hardcoded mapping table
         *
         * Note: autoloadInclusions returns true if classname was included
         */
        if(true === self::autoloadInclusions($classname))
        {
            return true;
        }

        /**
         * try to load the file by searching the
         * 3) automatically created mapping table.
         *
         * Note: the mapping table is loaded from APC or file.
         */
        if(true === self::autoloadByApcOrFileMap($classname))
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

    /**
     * Excludes a certain classname from the autoloading.
     *
     * Some libraries have their own autoloaders, like e.g. Smarty.
     * In these cases Clansuite has the first autoloader in the stack,
     * but is not responsible for loading.
     *
     * @param string $classname Classname to check for exclusion.
     * @return Returns true, if the class is to exclude.
     */
    public static function autoloadExclusions($classname)
    {
        # define parts of classnames for exclusion
        $classnames_to_exclude = array('Cs', 'Smarty_Internal');

        foreach($classnames_to_exclude as $classname_to_exclude)
        {
            if (false !== strpos($classname, $classname_to_exclude))
            {
                return true;
            }
        }

        /**
         * Exlude Doctrine, Smarty libraries from autoloading. They have their own autoloading handlers.
         * But include our own wrapper classes for both libraries.
         */

        # this means if 'Doctrine" is found, but not 'Clansuite_Doctrine', exclude from our autoloading
        if (false !== strpos($classname, 'Doctrine') and false === strpos($classname, 'Clansuite_Doctrine'))
        {
            return true;
        }

        # this means if 'Smarty" is found, but not 'Clansuite_Smarty', exclude from our autoloading
        if (false !== strpos($classname, 'Smarty') and false === strpos($classname, '_Smarty'))
        {
            return true;
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
        # autoloading map
        self::$inclusions_map = array(
        # /core/config
        'Clansuite_Config_Base'               => 'config/config.base.php',
        'Clansuite_Config_INI'                => 'config/ini.config.php',
        'Clansuite_Config_XML'                => 'config/xml.config.php',
        'Clansuite_Config_YAML'               => 'config/yaml.config.php',
        'Clansuite_Renderer_Base'             => 'renderer/renderer.base.php',
        # /core
        'Clansuite_Staging'                   => 'staging.core.php',
        'Clansuite_UTF8'                      => 'utf8.core.php',
        'Clansuite_Config'                    => 'config.core.php',
        'Clansuite_ACL'                       => 'acl.core.php',
        'Clansuite_HttpRequest'               => 'httprequest.core.php',
        'Clansuite_HttpResponse'              => 'httpresponse.core.php',
        'Clansuite_FilterManager'             => 'filtermanager.core.php',
        'Clansuite_Localization'              => 'localization.core.php',
        'Clansuite_Inputfilter'               => 'inputfilter.core.php',
        'Clansuite_User'                      => 'user.core.php',
        'Clansuite_Router'                    => 'router.core.php',
        'Clansuite_Security'                  => 'security.core.php',
        'Clansuite_Session'                   => 'session.core.php',
        'Clansuite_Filter_Interface'          => 'filtermanager.core.php',
        'Clansuite_Gettext_Extractor'         => 'gettext.core.php',
        'Clansuite_DoorKeeper'                => 'doorkeeper.core.php',
        'Clansuite_Functions'                 => 'functions.core.php',
        'Clansuite_XML2JSON'                  => 'xml2json.core.php',
        'Clansuite_Doctrine'                  => 'doctrine.core.php',
        'Clansuite_DoctrineTools'             => 'doctrine.core.php',
        'Clansuite_Front_Controller'          => 'frontcontroller.core.php',
        'Clansuite_Module_Controller'         => 'modulecontroller.core.php',
        'Clansuite_EventDispatcher'           => 'eventdispatcher.core.php',
        'Clansuite_Breadcrumb'                => 'breadcrumb.core.php',
        # /core/factories
        'Clansuite_Config_Factory'            => 'factories/config.factory.php',
        'Clansuite_Renderer_Factory'          => 'factories/renderer.factory.php',
        'Clansuite_Cache_Factory'             => 'factories/cache.factory.php',
        # /core/files
        'Clansuite_File'                      => 'files/file.core.php',
        'Clansuite_Directory'                 => 'files/file.core.php',
        'Clansuite_Upload'                    => 'files/upload.core.php',
        'Clansuite_Download'                  => 'files/download.core.php',
        # /viewhelper/
        'Clansuite_Theme'                     => 'viewhelper/theme.core.php',
        # /viewhelper/datagrid
        'Clansuite_Datagrid'                  => 'viewhelper/datagrid/datagrid.core.php',
        'Clansuite_Datagrid_Column'           => 'viewhelper/datagrid/datagridcol.core.php',
        # /viewhelper/form
        'Clansuite_Form'                      => 'viewhelper/form/form.core.php',
        'Clansuite_Formelement'               => 'viewhelper/form/formelement.core.php',
        'Clansuite_Formelement_Input'         => 'viewhelper/form/formelements/input.form.php',
        'Clansuite_Form_Decorator'            => 'viewhelper/form/formdecorator.core.php',
        'Clansuite_Formelement_Decorator'     => 'viewhelper/form/formdecorator.core.php',
        'Clansuite_Array_Formgenerator'       => 'viewhelper/form/formgenerators/array.formgenerator.php',
        'Clansuite_Doctrine_Formgenerator'    => 'viewhelper/form/formgenerators/doctrine.formgenerator.php',
        'Clansuite_Xml_Formgenerator'         => 'viewhelper/form/formgenerators/xml.formgenerator.php',
        );

        # check if classname is in autoloading map
        if(isset(self::$inclusions_map[$classname]) === true)
        {
            include ROOT_CORE . self::$inclusions_map[$classname];
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Loads a file by classname using the autoloader mapping array from file or apc
     *
     * @param $classname The classname to look for in the autoloading map.
     * @return boolean True on file load, otherwise false.
     */
    public static function autoloadByApcOrFileMap($classname)
    {
        if(self::USE_APC === true)
        {
            self::$autoloader_map = self::readAutoloadingMapApc();
        }
        else # load the mapping from file
        {
            self::$autoloader_map = self::readAutoloadingMapFile();
        }

        if(isset(self::$autoloader_map[$classname]) === true)
        {
            include self::$autoloader_map[$classname];
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * PSR-0 Loader
     *
     * - hardcoded namespaceSeparator
     * - hardcoded extension
     *
     * @link https://groups.google.com/group/php-standards/web/psr-0-final-proposal
     * @link http://gist.github.com/221634
     */
    public static function autoloadIncludePath($classname)
    {
        # trim opening namespace separator
        $classname = ltrim($classname, '\\');

        $filename  = '';
        $namespace = '';

        # determine position of last namespace separator
        if (false !== ($lastNsPos = strripos($classname, '\\')))
        {
            # everything before it, is the namespace
            $namespace = substr($classname, 0, $lastNsPos);
            # everything after it, is the classname
            $classname = substr($classname, $lastNsPos + 1);

            # replace every namespace separator with a directory separator
            $filename  = str_replace('\\', DS, $namespace) . DS;
        }

        # convert underscore to DS
        $filename .= str_replace('_', DS, $classname) . '.php';

        # searches on include path for the file and returns absolute path
        $filename = stream_resolve_include_path($filename);

        if(is_string($filename) === true)
        {

            include $filename;
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Loads a file by trying several absolute paths
     *
     * @param $classname The classname to look for in the autoloading map.
     * @return boolean True on file load, otherwise false.
     */
    public static function autoloadTryPathsAndMap($classname)
    {
        # Start Classname to Filename Mapping
        $filename = strtolower($classname);

        # strip 'clansuite_' from beginning of the string
        if(false !== strpos($filename, 'clansuite_'))
        {
            $filename = substr($filename, 10);
        }

        # Core Class
        # clansuite/core/class_name.core.php
        $file = ROOT_CORE . str_replace('_','',$filename) . '.core.php';
        if(is_file($file) === true)
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Event
        # clansuite/core/events/classname.class.php
        $file = ROOT_CORE . 'events' . DS . $classname . '.class.php';
        if(is_file($file) === true)
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Filter
        # clansuite/core/filters/classname.filter.php
        $file = ROOT_CORE . 'filters' . DS . substr($filename, 7) . '.filter.php';
        if(is_file($file) === true)
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Viewhelper
        # clansuite/core/viewhelper/classname.core.php
        $file = ROOT_CORE . 'viewhelper' . DS . str_replace('_','.',$filename) . '.core.php';
        if(is_file($file) === true)
        {
            return self::includeFileAndMap($file, $classname);
        }
    }

    /**
     * Include File (and register it to the autoloading map file)
     *
     * This procedure ensures, that the autoload mapping array dataset
     * is increased stepwise resulting in a decreasing number of autoloading tries.
     *
     * @param string $filename The file to be required
     * @return bool True on success of require, false otherwise.
     */
    public static function includeFileAndMap($filename, $classname)
    {
        $filename = realpath($filename);

        # conditional include
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
        $filename = realpath($filename);

        if(is_file($filename) === true)
        {
            include $filename;

            if(null === $classname) # just a file include, classname unimportant
            {
                return true;
            }
            elseif(class_exists($classname, false) === true)
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
     * Writes the autoload mapping array into a file.
     * The target file is ROOT.'configuration/'.self::$autoloader
     * The content to be written is an associative array $array,
     * consisting of the old mapping array appended by a new mapping.
     *
     * @param $array associative array with relation of a classname to a filename
     */
    public static function writeAutoloadingMapFile($array)
    {
        $mapfile = ROOT_CONFIG . 'autoloader.classmap.php';

        if(is_writable($mapfile) === true)
        {
            $bytes_written = file_put_contents($mapfile, serialize($array), LOCK_EX);

            if($bytes_written === false)
            {
                trigger_error('Autoloader could not write the map cache file: ' . $mapfile, E_USER_ERROR);
            }
            else
            {
                return true;
            }
        }
        else
        {
            trigger_error('Autoload cache file not writable: ' . $mapfile, E_USER_ERROR);
        }
    }

    /**
     * Reads the content of the autoloading map file and returns it unserialized.
     *
     * @return unserialized file content of autoload.config file
     */
    public static function readAutoloadingMapFile()
    {
        # check if file for the autoloading map exists
        $mapfile = ROOT_CONFIG . 'autoloader.classmap.php';

        # create file, if not existant
        if(is_file($mapfile) === false)
        {
            $file_resource = fopen($mapfile, 'a', false);
            fclose($file_resource);
            unset($file_resource);

            return array();
        }
        else # load map from file
        {
            # Note: delete the autoloader.config.php file, if you get an unserialization error like "error at offset xy"
            return unserialize(file_get_contents($mapfile));
        }
    }

    /**
     * Reads the autoload mapping array from APC.
     *
     * @return array automatically generated classmap
     */
    public static function readAutoloadingMapApc()
    {
        return apc_fetch('CLANSUITE_CLASSMAP');
    }

    /**
     * Writes the autoload mapping array to APC.
     *
     * @return array automatically generated classmap
     */
    public static function writeAutoloadingMapApc($array)
    {
        apc_store('CLANSUITE_CLASSMAP', $array);
    }

    /**
     * Adds a new $classname to $filename mapping to the map array.
     * The new map array is written to apc or file.
     *
     * @param $filename  Filename is the file to load.
     * @param $classname Classname is the lookup key for $filename.
     */
    private static function addToMapping($filename, $classname)
    {
        self::$autoloader_map = array_merge( (array) self::$autoloader_map, array( $classname => $filename ));

        if(self::USE_APC === true)
        {
            self::writeAutoloadingMapApc(self::$autoloader_map);
        }
        else
        {
            self::writeAutoloadingMapFile(self::$autoloader_map);
        }
    }

    /**
     * Includes a certain library classname by using a manually maintained autoloading map.
     * Functionally the same as self::autoloadInclusions().
     *
     * Snoopy, SimplePie, PclZip, graph, GeSHi, feedcreator, browscap, bbcode
     *
     * @param string $classname Library classname to load.
     * @return true if classname was included
     */
    public static function loadLibrary($classname)
    {
        # check if class was already loaded
        if (true === class_exists($classname, false))
        {
            return true;
        }

        # autoloading map - ROOT_LIBRARIES/..
        $map = array(
            'snoopy'        => 'snoopy/Snoopy.class.php',
            'simplepie'     => 'simplepie/simplepie.inc',
            'pclzip'        => 'pclzip/pclzip.lib.php',
            'graph'         => 'graph/graph.class.php',
            'geshi'         => 'geshi/geshi.php',
            'feedcreator'   => 'feedcreator/feedcreator.class.php',
            'browscap'      => 'browscap/Browscap.php',
            'bbcode'        => 'bbcode/stringparser_bbcode.class.php',
        );

        $classname = strtolower($classname);

        # check if classname is in autoloading map
        if(isset($map[$classname]) === true)
        {
            # get filename for that classname
            $filename = ROOT_LIBRARIES . $map[$classname];

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
}
?>