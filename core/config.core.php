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
        $this->config = $this->confighandler->toArray();        
    }

    public function readConfig($configfile)
    {
        if( ! is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        # @todo check if confighandler is of that configfile, else readConfig
        # check object name auf teilstring configtype object(Clansuite_Config_INIHandler)
        return $this->confighandler->readConfig($configfile);
    }

    /**
     * Reads a configuration file of a module
     *
     * @param $modulename Name of Module
     */
    public function readConfigForModule($modulename = null)
    {
        # if no modulename is set, determine the name of the current module
        if(is_null($modulename))
        {
            $modulename = Clansuite_Module_Controller_Resolver::getModuleName();
        }
        # @todo support for different configtypes
        $configfile = ROOT_MOD.$modulename.DS.$modulename.'.config.php';
        return $this->confighandler->readConfig($configfile);
    }

    /**
     * Write module configuration file
     *
     * Examples to write a module cfg:
     * a) direct $config->confighandler->writeConfig( ROOT_MOD . 'news'.DS.'news.config.php', $data);
     * b) indirect with this method $config->writeConfigForModule('news', 'ini', $cfg_array);
     *
     * @param $modulename
     * @param $type determines the configuration handler type, like ini, yaml, db
     * @param $cfg_array the configuration array to write
     */
    public function writeConfigForModule($modulename, $type, $cfg_array)
    {
        if( ! is_object($this->confighandler))
        {
            $this->confighandler = Clansuite_Config_Factory::getConfiguration($configfile);
        }

        $this->confighandler->writeConfig( ROOT_MOD.$modulename.DS.$modulename.'.config.php', $cfg_array);
    }
}
?>