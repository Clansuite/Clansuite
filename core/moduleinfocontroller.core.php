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

    private static $l10n_sys_locales = array();

    /**
     * Setter for module infos
     *
     * @param array $module_infos_array
     */
    public static function setModuleInformations($module_infos_array)
    {
        self::$modulesinfo = $module_infos_array;
    }

    /**
     * Setter for modules registry
     *
     * @param array $module_registry_array
     */
    public static function setModuleRegistry($module_registry_array)
    {
        self::$modulesregistry = $module_registry_array;
    }

    /**
     * Reads the CMS Module Registry
     *
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
        Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')
         ->writeConfig(ROOT . 'configuration' . DS . 'modules.config.php');
    }

    /**
     * Returns the module configuration as array
     *
     * @param string $modulename
     * @return array Module Configuration Array
     */
    public static function readModuleConfig($modulename)
    {
        return Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')
                ->readModuleConfig($modulename);
    }

    /**
     * Checks if a modulename belongs to the core modules.
     *
     * @param string $modulename The modulename
     * @return boolean True if modulename is a core module, false otherwise.
     */
    public static function isACoreModule($modulename)
    {
        # hardcoded
        static $core_modules = array( 'account', 'categories', 'controlcenter', 'doctrine', 'menu', 'modulemanager',
                                      'users', 'settings', 'systeminfo', 'thememanager', 'templatemanager');

        # @todo extract from module info file if core module or not

        return in_array($modulename, $core_modules);
    }

    /**
     * Get a list of all the module directories
     *
     * @return array
     */
    public static function getModuleDirectories()
    {
        return glob( ROOT_MOD . '[a-zA-Z]*', GLOB_ONLYDIR );
    }

    /**
     * Get a list of all the module names
     *
     * 4 in 1 method, handling the following cases:
     * 1. array with module names
     * 2. named array with modulenames
     * 3. array with module names and paths
     * 4. named array with modulenames and paths
     *
     * @param boolean $only_modulenames Toggle between only_names (true) and names+paths.
     * @param boolean $named_array Toggle between named (true) and unnamed array.
     * @return array( $modulename => $module_path )
     */
    public static function getModuleNames($named_array = false, $only_modulenames = false)
    {
        $modules = array();

        $module_dirs = self::getModuleDirectories();

        foreach($module_dirs as $module_path)
        {
            # strip path off
            $modulename = str_replace( ROOT_MOD, '', $module_path);

            if($only_modulenames === true)
            {
                if($named_array === false)
                {
                    $modules[] = $modulename;
                }
                else
                {
                    $modules[] = array ( 'name' => $modulename);
                }
            }
            else
            {
                if($named_array === false)
                {
                    $modules[] = array( $modulename => $module_path );
                }
                else
                {
                    $modules[] = array ( 'name' => $modulename,
                                         'path' => $module_path);
                }
            }
        }

        return $modules;
    }

    /**
     * Returns all activated modules
     *
     * @return array $activated_modules_array
     */
    public static function getAllActivatedModules()
    {
        $activated_modules_array = array();

        $modules = self::getModuleNames(true);

        foreach($modules as $module)
        {
            if(true === self::isModuleActive($module))
            {
                $activated_modules_array[$module] = self::$modulesregistry[$module];
            }
        }

        return $activated_modules_array;
    }

    /**
     * Checks if a module is active or deactived.
     *
     * @param boolean $module True if module activated, false otherwise.
     */
    public static function isModuleActive($module)
    {
        # load module registry, if not available yet
        if(empty(self::$modulesregistry[$module]))
        {
            self::$modulesregistry = self::readModuleRegistry();
        }

        # check, if the module is
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
     * Fetches all pieces of information of a certain module
     *
     * @param string $module
     * @return array Module Informations
     */
    public static function getModuleInformations($module = null)
    {
        $modulename = strtolower($module);

        # check if the infos of this specific module were catched before
        if(isset(self::$modulesinfo[$modulename]))
        {
            return self::$modulesinfo[$modulename];
        }

        # fetch infos for the requested $module
        return self::loadModuleInformations($module);
    }

    public static function buildModuleRegistry()
    {
        foreach( $module_directories as $module_path )
        {
            # strip off path info and get the modulename
            $modulename = str_replace( ROOT_MOD, '', $module_path);
        }

        self::writeModuleRegistry();
    }

    /**
     * Gather Module Informations from Manifest Files
     *
     * @staticvar array $modulesinfo
     * @param mixed array|string $module array with modulenames or one modulename
     * @return moduleinformations (self::$modulesinfo)
     */
    public static function loadModuleInformations($module = null)
    {
        # Init vars
        $module_directories = array();
        $number_of_modules = 0;

        /**
         * either fetch the module requested via parameter $module
         * fetch all modules
         */
        if($module === null)
        {
            $module_directories = self::getModuleDirectories();
        }
        else
        {
            # cast string to array
            $module_directories[] = ROOT_MOD . $module;
        }

        foreach( $module_directories as $modulepath )
        {
            /**
             * create array with pieces of information about a module
             */

            # 1) get the modulename, by stripping off the path info
            $modulename = str_replace( ROOT_MOD, '', $modulepath);

            self::$modulesinfo[$modulename]['name']   = $modulename;
            self::$modulesinfo[$modulename]['id']     = $number_of_modules;
            self::$modulesinfo[$modulename]['path']   = $modulepath;
            self::$modulesinfo[$modulename]['core']   = self::isACoreModule($modulename);

            # active - based on /configuration/modules.config.php
            self::$modulesinfo[$modulename]['active'] = self::isModuleActive($modulename);

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
            $config = self::readModuleConfig($modulename);
            if(isset($config[$modulename]))
            {
                self::$modulesinfo[$modulename]['config'] = $config[$modulename];

                # properties
                if( isset($config['properties']))
                {
                    self::$modulesinfo[$modulename]['settings'] = $config['properties'];
                }

                # acl
                if( isset($config['properties_acl']))
                {
                    self::$modulesinfo[$modulename]['acl'] = $config['properties_acl'];
                }
            }
            /*else
            {
                $modules[$modulename]['config'] = $config;
            }*/

            # hasLanguages
            self::$modulesinfo[$modulename]['languages'] = self::getLanguageInfosForModule($modulepath);

            # take some stats: increase the module counter
            self::$modulesinfo['yy_summary']['counter'] = ++$number_of_modules;
        }

        ksort(self::$modulesinfo);

        #Clansuite_Debug::printR(self::$modulesinfo);

        return self::$modulesinfo;
    }

    public static function getLanguageInfosForModule($modulepath)
    {
        $langinfo = array();

        # we are looking at the languages folder a given module
        $module_lang_dir = $modulepath . DS . 'languages';

        # return, if that languages directory does not exist
        if(false === is_dir($module_lang_dir))
        {
            return 'No language dir.';
        }

        # if the language definitions are not already loaded, load them
        if(empty(self::$l10n_sys_locales))
        {
            # fetch arrays containing locale data
            require ROOT_CORE . 'gettext' . DS . 'locales.gettext.php';

            self::$l10n_sys_locales = $l10n_sys_locales;
        }
        
        # preg_match('/^[a-z]{2}(_[A-Z]{2})?\./', $locale )
        
        $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($module_lang_dir),
                            \RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($iterator as $file)
        {
            if(0 === preg_match('/.(mo|po)$/', $file->getFileName()))
            {
                 continue; # echo 'Skipped : '. $file->getFileName() . '<br>';
            }

            # reduce module language path to "/en_gb/LC_MESSAGES"
            $langdir = str_replace($module_lang_dir , '', realpath($file->getPathName()));
            # grab language dir
            preg_match('/(.*)LC_MESSAGES/i', $langdir, $matches);
            # unslash
            $language = trim($matches[1], DS);
            #Clansuite_Debug::printR($language);

            # get more data about that language by its shorthand
            if(isset(self::$l10n_sys_locales[$language]) == true)
            {
                #Clansuite_Debug::printR($l10n_sys_locales[$language]);

                $country_www = self::$l10n_sys_locales[$language]['country-www'];
                $lang_native = self::$l10n_sys_locales[$language]['lang-native'];
                $lang = self::$l10n_sys_locales[$language]['lang'];
            }
            else
            {
                $country_www = 'unknown';
                $lang_native = '<em>locale: </em>' . $language;
                $lang = $language;
            }

            $langinfo = array();
            #$langinfo[] = $language;
            $langinfo[$language]['pathName']       = realpath($file->getPathName());
            $langinfo[$language]['fileName']       = $file->getFileName();
            $langinfo[$language]['filePermString'] = self::file_permissions($langinfo[$language]['pathName']);
            $langinfo[$language]['fileReadable']   = $file->isReadable();
            $langinfo[$language]['fileWriteable']  = $file->isWritable();
            $langinfo[$language]['timestamp']      = date(DATE_FORMAT, $file->getCTime());
            $langinfo[$language]['country_www']    = $country_www;
            $langinfo[$language]['lang_native']    = $lang_native;
            $langinfo[$language]['lang']           = $lang;
            $langinfo[$language]['poclass']        = '-' . ($file->isReadable() ? 'r' : '') . ($file->isWritable() ? 'w' : '');
            $langinfo[$language]['moclass']        = '-' . ($file->isReadable() ? 'r' : '') . ($file->isWritable() ? 'w' : '');

            #Clansuite_Debug::printR($langinfo);
        }

        return $langinfo;
    }

    /**
     * Returns file permissions as string
     *
     * @staticvar array $permissions
     * @param type $filename
     * @return string File Permissions as string, e.h. "rwx", "rw-"
     */
    private static function file_permissions($filename)
    {
        static $permissions = array("---", "--x", "-w-", "-wx", "r--", "r-x", "rw-", "rwx");
        $perm_oct = substr(decoct(fileperms($filename)), 3);
        return "[" . $permissions[(int) $perm_oct[0]] . '|' . $permissions[(int) $perm_oct[1]] . '|' . $permissions[(int) $perm_oct[2]] . "]";
    }
}
?>