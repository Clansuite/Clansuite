<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Config;

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
    public function __construct()
    {
        // if empty get from Clansuite_CMS
        if (empty($this->config)) {
            $this->config = \Clansuite\CMS::getClansuiteConfig();
        }
    }

    /**
     * Reads a configuration file
     *
     * @param  string $configfile
     * @return object $this->config
     */
    public function readConfig($configfile)
    {
        if (false === is_object($this->config)) {
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
        // if no modulename is set, determine the name of the current module
        if ($modulename === null) {
            $modulename = \Koch\Router\TargetRoute::getModule();
        }

        $file = ROOT_MOD . $modulename . DIRECTORY_SEPARATOR . $modulename . '.config.php';

        if (is_file($file)) {
            return Factory::getConfiguration($configfile);
        } else { // module has no configuration file

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
        if (null == $modulename) {
            $modulename = Koch\Router\TargetRoute::getModule();
        }
        $this->writeConfig(ROOT_MOD . $modulename . DIRECTORY_SEPARATOR . $modulename . '.config.php', $array);
    }

    /**
     * Write a config file
     *
     * @example To write a module cfg for module news:
     * $config->confighandler->writeConfig( ROOT_MOD . 'news'. DIRECTORY_SEPARATOR .'news.config.php', $data);
     *
     * @param $file path and the filename you want to write
     * @param $array the configuration array to write. Defaults to null = empty array.
     */
    public function writeConfig($filename, $array = null)
    {
        if ($array === null) {
            $array = array();
        }

        Factory::getHandler($filename)->writeConfig($filename, $array);
    }
}
