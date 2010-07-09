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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
´   *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Config
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Config
 */
class Clansuite_Config extends Clansuite_Config_Base
{
    function __construct($configfile = 'configuration/clansuite.config.php')
    {
        $this->config = $this->readConfig($configfile);
    }

    /**
     * Reads a configuration file
     *
     * @param string $configfile
     * @return object $this->config
     */
    public function readConfig($configfile)
    {
        if(false === is_object($this->config))
        {
            $this->config = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        return $this->config;
    }

    /**
     * Reads a configuration file of a module ($modulename . '.config.php')
     *
     * @param $modulename Name of Module
     * @return array Module Configuration Array
     */
    public function readModuleConfig($modulename = null)
    {
        # if no modulename is set, determine the name of the current module
        if($modulename === null)
        {
            $modulename = Clansuite_Dispatcher::getModuleName();
        }

        $configfile = ROOT_MOD . $modulename . DS . $modulename . '.config.php';

        return Clansuite_Config_Factory::getConfiguration($configfile);
    }

    /**
     * Write module configuration file
     *
     * @example
     * $this->writeModuleConfig('news', $data);
     * This method works as a shortcut to writeConfig(). Normally you would write
     * $config->confighandler->writeConfig( ROOT_MOD . 'news'.DS.'news.config.php', $data);
     *
     * @see writeConfig()
     *
     * @param $modulename
     * @param $array the configuration array to write
     */
    public function writeModuleConfig($modulename, $cfg_array)
    {
        if(false === is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        $this->confighandler->writeConfig(ROOT_MOD . $modulename . DS . $modulename . '.config.php', $array);
    }

    /**
     * Write a config file
     *
     * @example To write a module cfg for module news:
     * $config->confighandler->writeConfig( ROOT_MOD . 'news'.DS.'news.config.php', $data);
     *
     * @param $file path and the filename you want to write
     * @param $array the configuration array to write. Defaults to null = empty array.
     */
    public function writeConfig($filename, $array = null)
    {
        if($array === null)
        {
            $array = array();
        }

        if(false === is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($filename);
        }

        $this->confighandler->writeConfig($filename, $array);
    }
}
?>