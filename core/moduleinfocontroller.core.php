<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_ModuleInfoController
 *
 * Class for ModuleManagement
 *
 * @todo
 * A. ModuleInfoScanner
 * B. ModuleInfoReader
 */
class Clansuite_ModuleInfoController
{
    /**
     * @var array contains the moduleinformations
     */
    private static $modulesinfo = false;

    /**
     * @var array contains the system-wide module registry
     */
    private static $modulesregistry  = false;

    /**
     * Checks if a modulename belongs to the core modules.
     *
     * @param string $modulename The modulename
     * @return boolean True if modulename is a core module, false otherwise.
     */
    public static function isCoreModule($modulename)
    {
        static $core_modules = array( 'account', 'categories', 'controlcenter', 'doctrine', 'menu', 'modulemanager',
                                       'users', 'settings', 'systeminfo', 'thememanager', 'templatemanager');
        return in_array($modulename, $core_modules);
    }

    /**
     * Get a list of all the module directories
     *
     * @return array
     */
    private static function getModuleDirectories()
    {
        return glob( ROOT_MOD . '[a-zA-Z]*', GLOB_ONLYDIR);
    }

    /**
     * Gather Module Informations from Manifest Files
     *
     * @staticvar array $modules
     * @staticvar array $modules_summary
     * @param mixed array|string $module array with modulenames or one modulename
     * @return moduleinformations
     */
    private static function scanModuleInformations($module = null)
    {
        # Init vars
        $module_directories = array();
        $number_of_modules = 0;

        if($module == null)
        {
            $module_directories = self::getModuleDirectories();
        }
        else
        {
            $module_directories = array ($module); # $module is either an array or an string
        }

        foreach( $module_directories as $modulepath )
        {
            # get the modulename, so strip the path info
            $modulename = str_replace( ROOT_MOD, '', $modulepath);
            # $modulename_by_dirname = str_replace( ROOT . 'modules' . DS ,'', $module_path);

            # create array with pieces of information about a module
            self::$modulesinfo[$modulename]['name']   = $modulename;
            self::$modulesinfo[$modulename]['id']     = $number_of_modules;
            self::$modulesinfo[$modulename]['path']   = $modulepath;
            self::$modulesinfo[$modulename]['core']   = self::isCoreModule($modulename);
            # active - based on /configuration/modules.config.php
            self::$modulesinfo[$modulename]['active'] = self::isActive($modulename);
            # hasMenu / ModuleNavigation
            self::$modulesinfo[$modulename]['menu']   = is_file($modulepath . DS . $modulename .'.menu.php');

            # hasInfo
            $module_infofile = $modulepath . DS . $modulename . '.info.php';
            $config_object = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config');
            if(is_file($module_infofile) === true)
            {
                #Clansuite_Debug::firebug($module_infofile);

                self::$modulesinfo[$modulename]['info'] = $config_object->readConfig($module_infofile);
            }
            else # create file in DEV MODE
            {
                # if the info file for a module does not exists yet, create it
                $config_object->writeConfig($module_infofile);
            }

            # hasRoutes

            # hasConfig
            $config = self::loadModuleInformations($modulename);
            if(isset($config[$modulename]))
            {
                self::$modulesinfo[$modulename]['config'] = $config[$modulename];
            }
            /*else
            {
                $modules[$modulename]['config'] = $config;
            }*/

            # take some stats: increase the module counter
            self::$modulesinfo['yy_summary']['counter'] = ++$number_of_modules;
        }
        ksort(self::$modulesinfo);

        return self::$modulesinfo;
    }

    public static function getModuleInformations($module = null)
    {
        # check if the infos of this specific module were catched before
        if($module === false and isset(self::$modulesinfo[$module]) === null)
        {
            return self::$modulesinfo[$module];
        }
        # fetch infos for all modules
        elseif(empty(self::$modulesinfo) and $module === null)
        {
            #Clansuite_Debug::printR(self::$modulesinfos);
            return self::scanModuleInformations();
        }
        else # fetch infos for the requested $module
        {
            return self::scanModuleInformations($module);
        }
    }

    public static function setModuleInformations($module_infos_array)
    {
        self::$modulesinfo = $module_infos_array;
    }

    public static function loadModuleInformations($modulename)
    {
        return Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')->readModuleConfig($modulename);
    }

    /**
     * Check if a module is active or deactived.
     *
     * @param boolean $module True if module activated, false otherwise.
     */
    public static function isActive($module)
    {
        if(empty(self::$modulesregistry[$module]))
        {
            self::$modulesregistry = self::readModuleRegistry();
        }

        if(isset(self::$modulesregistry[$module]['active']) and self::$modulesregistry[$module]['active'] == true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Reads the CMS Module Registry
     * This is the right method if you want to know if
     * a module is installed and active or deactivated.
     *
     * @return array Module Registry Array
     */
    public static function readModuleRegistry()
    {
        return Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')
                ->readConfig(ROOT . 'configuration' . DS . 'modules.config.php');
    }

    /**
     * Writes the Module Registry
     *
     * @param array $array The Module Registry Array to write.
     */
    public static function writeModuleRegistry($array)
    {

    }

    public function createModuleInfoFile($modulename)
    {
        return Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')
                ->writeConfig( ROOT . 'configuration' . DS . 'modules.config.php' );
    }
}
?>