<?php
   /**
    * Koch Framework
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Config;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch\Config
 *
 * This is a configuration container class.
 * It's build around the $config array, which is the storage container for settings.
 *
 * We use some php magic in here:
 * The array access implementation makes it seem that $config is an array,
 * even though it's an object! Why we do that? Because less to type!
 * The __set, __get, __isset, __unset are overloading functions to work with that array.
 *
 * Usage :
 * get data : $cfg->['name'] = 'john';
 * get data, using get() : echo $cfg->get ('name');
 * get data, using array access: echo $cfg['name'];
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Config
 */
class Config extends AbstractConfig
{
    /**
     * This object is injected via DI.
     * The depending object needs a version of the config.
     * We fetch it from Clansuite_CMS.
     */
    function __construct()
    {
        # if empty get from Clansuite_CMS
        if(empty($this->config))
        {
            $this->config = \Clansuite\CMS::getClansuiteConfig();
        }
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
            $this->config = Factory::getConfiguration($configfile);
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
            $modulename = Koch\Router\TargetRoute::getModuleName();
        }

        $file = ROOT_MOD . $modulename . DS . $modulename . '.config.php';

        if(is_file($file))
        {
            return Factory::getConfiguration($configfile);
        }
        else # module has no configuration file
        {
            return array();
        }
    }

    /**
     * Write module configuration file
     *
     * @example
     * $this->writeModuleConfig('news', $data);
     *
     * @see writeConfig()
     *
     * @param $array the configuration array to write
     * @param $modulename The Modulename
     */
    public function writeModuleConfig($array, $modulename = null)
    {
        if(null == $modulename)
        {
            $modulename = Koch\Router\TargetRoute::getModuleName();
        }
        $this->writeConfig(ROOT_MOD . $modulename . DS . $modulename . '.config.php', $array);
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

        Factory::getHandler($filename)->writeConfig($filename, $array);
    }
}
?>