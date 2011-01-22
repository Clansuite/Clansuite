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
    * @copyright  Jens-André Koch (2005 - onwards)
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
 * Configuration Factory
 *
 * The static method getConfiguration() includes and instantiates a Configuration Engine Object
 * and injects the configfile.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Config
 */
class Clansuite_Config_Factory
{
    /**
     * Instantiates the correct subclass determined by the fileextension
     *
     * Possible Extensions of the Configuration Files
     *  .config.php
     *  .config.xml
     *  .config.yaml
     *  .info.php
     *
     * @param $configfile string path to configuration file
     * @return Cache Engine Object reads the configfile -> access to values via $config
     */
    public static function determineConfigurationHandlerTypeBy($configfile)
    {
        # init var
        $adapter = '';

        # use the filename only to detect adapter
        # @todo simplify extension detection, but watch out for .info.php
        #$extension = strtolower(pathinfo($configfile, PATHINFO_EXTENSION));

        $configfile = basename($configfile);
        preg_match('^(.config.php|.config.xml|.config.yaml|.info.php)$^', $configfile, $extension);
        $extension = $extension[0];

        # the fileextensions .config.php means it's an .ini file
        # the content of the file IS NOT a php-array as you might think
        if($extension == '.config.php' or $extension == '.info.php')
        {
            $adapter = 'ini'; # @todo change this to 'php' (read/write of php-array)
        }
        elseif($extension == '.config.xml')
        {
            $adapter = 'xml';
        }
        elseif($extension == '.config.yaml')
        {
            $adapter = 'yaml';
        }
        else
        {
            throw new Clansuite_Exception('No handler for that type of configuration file found (' . $extension .')');
        }

        return $adapter;
    }

    /**
     * Get Configuration
     *
     * Two in one method: determines the configuration handler automatically for a configfile.
     * Uses the confighandler to load the configfile and return the object.
     * The returned object contains the confighandler and the config array.
     *
     * @param $configfile Configuration file to load
     * @return Configuration Handler Object with confighandler and array of configfile.
     */
    public static function getConfiguration($configfile)
    {
        $handler = self::getHandler($configfile);

        return $handler->readConfig($configfile);
    }

    /**
     * Get Configuration Handler
     *
     * @param string $configfile
     * @return object Configuration Handler Object
     */
    public static function getHandler($configfile)
    {
        $type = self::determineConfigurationHandlerTypeBy($configfile);

        $handler = self::getConfigurationHandler($type);

        return $handler;
    }

    /**
     * getConfiguration
     *
     * @param string $adapter a configuration filename type like "php", "xml", "yaml", "ini"
     * @return Configuration Handler Object with confighandler and array of configfile.
     */
    public static function getConfigurationHandler($adapter)
    {
        # path to configuration handler classes
        $file = ROOT_CORE . 'config' . DS . mb_strtolower($adapter) . '.config.php';

        if(is_file($file) === true)
        {
            $class = 'Clansuite_Config_' . mb_strtoupper($adapter);
            if(false === class_exists($class, false))
            {
                include $file;
            }

            if(true === class_exists($class, false))
            {
                # instantiate and return the specific confighandler with the $configfile to read
                return $class::getInstance();
                #call_user_func($class.'::getInstance');
                #return new $class();
            }
            else
            {
                throw new Clansuite_Exception('Config_Factory -> Class not found: ' . $class, 40);
            }
        }
        else
        {
            throw new Clansuite_Exception('Config_Factory -> File not found: ' . $class, 41);
        }
    }
}
?>