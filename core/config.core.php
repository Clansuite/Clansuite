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
 * Class hierarchy
 *
 * Clansuite_Config_Base (generic access methods => magic set/get + arrayaccess implementation)
 * |
 * \-- Clansuite_Config
 *     |
 *     \-- Clansuite_Factory
 *         |
 *         \-- Clansuite_Config_xyHandler
 *
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Config
 */
class Clansuite_Config extends Clansuite_Config_Base implements ArrayAccess
{
    # object
    public $confighandler;

    # array
    public $config;

    function __construct($configfile = 'configuration/clansuite.config.php')
    {
        $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);

        # @todo remove config array from instance
        $this->config = $this->confighandler->toArray();
    }

    public function readConfig($configfile)
    {
        if(!is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        return $this->confighandler->readConfig($configfile);
    }

    /**
     * Reads a configuration file of a module
     *
     * Handles different configuration filetypes - just pass the modulename
     * @sse Clansuite_Config->determineConfigurationHandlerTypeBy()
     *
     * @param $modulename Name of Module
     * @return array Module Configuration Array
     */
    public function readModuleConfig($modulename = null)
    {
        # if no modulename is set, determine the name of the current module
        if($modulename === null)
        {
            $modulename = Clansuite_Module_Controller_Resolver::getModuleName();
        }

        $configfile = ROOT_MOD . $modulename . DS . $modulename . '.config.php';
        return $this->confighandler->readConfig($configfile);
    }

    /**
     * Write module configuration file
     *
     * @example To write a module cfg for module news:
     * $config->confighandler->writeConfig( ROOT_MOD . 'news'.DS.'news.config.php', $data);
     *
     * @param $modulename
     * @param $array the configuration array to write
     */
    public function writeModuleConfig($modulename, $cfg_array)
    {
        if(!is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        $this->confighandler->writeConfig(ROOT_MOD . $modulename . DS . $modulename . '.config.php', $array);
    }

    /**
     * Write a config file
     *
     * @example To write a module cfg for module news:
     * $config->confighandler->writeConfig( ROOT.'configuration/my.config.php', $data);
     *
     * @param $file path and the filename you want to write
     * @param $array the configuration array to write. Defaults to null = empty array.
     */
    public function writeConfig($filename, $array = null)
    {
        if($array == null)
        {
            $array = array();
        }

        if(!is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($filename);
        }

        $this->confighandler->writeConfig($filename, $array);
    }

    /**
     * Magic Method __call()
     *
     * When a method call to the config is not defined, it is catched by __call().
     * So the purpose of this method is to delegate method calls to the confighandler,
     * which might implement them. We test if the method exists there before execution.
     * This result is, that you have the full combination of methods of this class and the instantiated
     * confighandler, without losing methods. And without using a method-call construct like
     * $config->confighandler->writeConfig();
     *
     * Several Performance-Issues:
     * 1) costs for calling __call
     * 2) costs for calling call_user_func_array()
     * 3) the nested call stack itself: the bigger the stack, the slower it becomes.
     */
    public function __call($method, $args)
    {
        if(method_exists($this->confighandler, $method) === true)
        {
            return call_user_func_array( array($this->confighandler, $method), $args);
        }
    }
}
?>