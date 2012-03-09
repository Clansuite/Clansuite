<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Autoload;

/**
 * Koch Loader
 *
 * This Loader overwrites the Zend Engines_autoload with our own user defined loading functions.
 * The main function of this class is autoload() it's registered via spl_autoload_register($load_function).
 * There are several loader-functions, which are used by autoload().
 * Autoload will run, if a file is not found.
 * The procedure is (1) exclusions, (2) inclusions, (3) mapping file or apc, (4) mapping table.
 *
 * Usage:
 * 1) include this file
 * 2) spl_autoload_register('Koch_Loader::autoload');
 *
 * PHP Manual: __autoload
 * @link http://www.php.net/manual/en/language.oop5.autoload.php
 *
 * PHP Manual: spl_autoload_register
 * @link http://www.php.net/manual/de/function.spl-autoload-register.php
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Loader
 */
class Loader
{
    /**
     * @var boolean APC on/off.
     * This toggles the usage of APC (when true) or File (when false) for reading and writing the classmap array.
     * @see addToMapping();
     */
    public static $use_apc = false;

    /**
     * @var array Generated Classmap from File or APC.
     */
    private static $autoloader_map = array();

    /**
     * @var array Manually defined Classmap
     * @see autoloadInclusions()
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
        if(true === class_exists($classname, false) or true === interface_exists($classname, false))
        {
            return false;
        }

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
     * In these cases Koch Framework has the first autoloader in the stack,
     * but is not responsible for loading.
     *
     * @param string $classname Classname to check for exclusion.
     * @return Returns true, if the class is to exclude.
     */
    public static function autoloadExclusions($classname)
    {
        # define parts of classnames for exclusion
        $classnames_to_exclude = array('Smarty_Internal'); # 'Cs'

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

        # this means if 'Doctrine" is found, but not 'Koch_Doctrine', exclude from our autoloading
        if (false !== strpos($classname, 'Doctrine') and false === strpos($classname, 'Koch_Doctrine'))
        {
            return true;
        }

        # this means if 'Smarty" is found, but not 'Koch_Smarty', exclude from our autoloading
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
        'Koch_Config_Base'               => 'config/base.php',
        'Koch_Config_INI'                => 'config/adapter/ini.php',
        'Koch_Config_XML'                => 'config/adapter/xml.php',
        'Koch_Config_YAML'               => 'config/adapter/yaml.php',
        'Koch_Renderer_Base'             => 'renderer/renderer.base.php',
        # /core
        'Koch_Staging'                   => 'staging.php',
        'Koch_UTF8'                      => 'utf8core.php',
        'Koch_Config'                    => 'config.php',
        'Koch_ACL'                       => 'aclcore.php',
        'Koch_HttpRequest'               => 'httprequestcore.php',
        'Koch_HttpResponse'              => 'httpresponse.php',
        'Koch_FilterManager'             => 'filtermanager.php',
        'Koch_Localization'              => 'localization.php',
        'Koch_Inputfilter'               => 'inputfilter.php',
        'Koch_User'                      => 'user.php',
        'Koch_Router'                    => 'router.php',
        'Koch_TargetRoute'               => 'targetroute.php',
        'Koch_Mapper'                    => 'mapper.php',
        'Koch_Security'                  => 'security.php',
        'Koch_Session'                   => 'session.php',
        'Koch_Filter_Interface'          => 'filtermanager.php',
        'Koch_Gettext_Extractor'         => 'gettext.php',
        'Koch_DoorKeeper'                => 'doorkeeper.php',
        'Koch_Functions'                 => 'functions.php',
        'Koch_XML2JSON'                  => 'xml2json.php',
        'Koch_Doctrine'                  => 'doctrine.php',
        'Koch_DoctrineTools'             => 'doctrine.php',
        'Koch_Front_Controller'          => 'frontcontroller.php',
        'Koch_Module_Controller'         => 'modulecontroller.php',
        'Koch_EventDispatcher'           => 'eventdispatcher.php',
        'Koch_Breadcrumb'                => 'breadcrumb.php',
        # /core/files
        'Koch_File'                      => 'files/file.php',
        'Koch_Directory'                 => 'files/file.php',
        'Koch_Upload'                    => 'files/upload.php',
        'Koch_Download'                  => 'files/download.php',
        # /core/tools
        'Koch_Browserinfo'               => 'tools/browserinfo.php',
        'Koch_Cssbuilder'                => 'tools/cssbuilder.php',
        # /core/renderer
        'Koch_View_Mapper'               => 'renderer/templatemapper.php',
        # /viewhelper/
        'Koch_Theme'                     => 'viewhelper/theme.php',
        # /viewhelper/datagrid
        'Koch_Datagrid'                  => 'viewhelper/datagrid/datagrid.php',
        'Koch_Datagrid_Column'           => 'viewhelper/datagrid/datagridcol.php',
        # /viewhelper/form
        'Koch_Form'                      => 'viewhelper/form/form.php',
        'Koch_Formelement'               => 'viewhelper/form/formelement.php',
        'Koch_Form_Decorator'            => 'viewhelper/form/formdecorator.php',
        'Koch_Formelement_Decorator'     => 'viewhelper/form/formelementdecorator.php',
        'Koch_Formelement_Validator'     => 'viewhelper/form/formelementvalidator.php',
        'Koch_Formelement_Input'         => 'viewhelper/form/elements/input.php',
        'Koch_Formelement_Button'        => 'viewhelper/form/elements/button.php',
        'Koch_Formelement_Imagebutton'   => 'viewhelper/form/elements/imagebutton.php',
        'Koch_Formelement_Checkbox'      => 'viewhelper/form/elements/checkbox.php',
        'Koch_Formelement_Select'        => 'viewhelper/form/elements/select.php',
        'Koch_Array_Formgenerator'       => 'viewhelper/form/generators/array.php',
        'Koch_Doctrine_Formgenerator'    => 'viewhelper/form/generators/doctrine.php',
        'Koch_Xml_Formgenerator'         => 'viewhelper/form/generators/xml.php',
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
        if(empty(self::$autoloader_map) === true)
        {
            if(self::$use_apc === true)
            {
                self::$autoloader_map = self::readAutoloadingMapApc();
            }
            else # load the mapping from file
            {
                self::$autoloader_map = self::readAutoloadingMapFile();
            }
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
        # clansuite/core/class_name.php
        $file = ROOT_CORE . str_replace('_','',$filename) . '.php';
        if(is_file($file) === true)
        {
            return self::includeFileAndMap($file, $classname);
        }

        # Event
        # clansuite/core/events/classname.event.php
        $file = ROOT_CORE . 'events' . DS . $classname . '.event.php';
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
        # clansuite/core/view/helper/classname.php
        $file = ROOT_CORE . 'view/helper/' . str_replace('_','.',$filename) . '.php';
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
     * @return boolean True if stored.
     */
    public static function writeAutoloadingMapApc($array)
    {
        return apc_store('CLANSUITE_CLASSMAP', $array);
    }

    /**
     * Adds a new $classname to $filename mapping to the map array.
     * The new map array is written to apc or file.
     *
     * @param $filename  Filename is the file to load.
     * @param $classname Classname is the lookup key for $filename.
     * @return boolean True if added to map.
     */
    public static function addToMapping($filename, $classname)
    {
        self::$autoloader_map = array_merge( (array) self::$autoloader_map, array( $classname => $filename ));

        if(self::$use_apc === true)
        {
            return self::writeAutoloadingMapApc(self::$autoloader_map);
        }
        else
        {
            return self::writeAutoloadingMapFile(self::$autoloader_map);
        }
    }

    /**
     * Includes a certain library classname by using a manually maintained autoloading map.
     * Functionally the same as self::autoloadInclusions().
     *
     * You can load directly:
     * Snoopy, SimplePie, PclZip, graph, GeSHi, feedcreator, browscap, bbcode
     *
     * You can also pass a custom map, like so:
     * loadLibrary('xtemplate', ROOT_LIBRARIES . 'xtemplate/xtemplate.class.php' )
     *
     * @param string $classname Library classname to load.
     * @param string $path Path to the class.
     * @return true if classname was included
     */
    public static function loadLibrary($classname, $path = null)
    {
        # check if class was already loaded
        if (true === class_exists($classname, false))
        {
            return true;
        }

        $classname = strtolower($classname);

        if(isset($path))
        {
            $map = array($classname, $path);
        }
        else
        {
            # autoloading map - ROOT_LIBRARIES/..
            $map = array(
                'snoopy'        => ROOT_LIBRARIES . 'snoopy/Snoopy.class.php',
                'simplepie'     => ROOT_LIBRARIES . 'simplepie/simplepie.inc',
                'pclzip'        => ROOT_LIBRARIES . 'pclzip/pclzip.lib.php',
                'graph'         => ROOT_LIBRARIES . 'graph/graph.class.php',
                'geshi'         => ROOT_LIBRARIES . 'geshi/geshi.php',
                'feedcreator'   => ROOT_LIBRARIES . 'feedcreator/feedcreator.class.php',
                'browscap'      => ROOT_LIBRARIES . 'browscap/Browscap.php',
                'bbcode'        => ROOT_LIBRARIES . 'bbcode/stringparser_bbcode.class.php',
            );
        }

        # check if classname is in autoloading map
        if(isset($map[$classname]) === true)
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
     * Getter for the autoloader classmap.
     *
     * @return array autoloader classmap.
     */
    public static function getAutoloaderClassMap()
    {
        return self::$autoloader_map;
    }
}
?>