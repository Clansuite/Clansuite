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
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Config_Base
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Configuration
 */
abstract class Clansuite_Config_Base /*extends ArrayObject*/ implements ArrayAccess
{
    /**
     * Configuration Array
     * protected = only visible to childs
     *
     * @var array
     */
    protected $config = array();

    /**
     * Returns $this->config Object as Array
     * On "unset = true" the array is returned and unset to save memory
     * and to avoid duplication of the config array.
     *
     * @param boolean $unset If unset is true, $this->config array will be unset. Defaults to false.
     * @return   config array
     */
    public function toArray($unset = false)
    {
        $array = array();
        $array = $this->config;

        if($unset === true)
        {
            unset($this->config);
        }

        return $array;
    }

    /**
     * Gets a config file item based on keyname
     *
     * @param    string    the config item key
     * @return   void
     */
    public function __get($configkey)
    {
        if(isset($this->config[$configkey]))
        {
            return $this->config[$configkey];
        }
        else
        {
            return null;
        }
    }

    /**
     * Set a config file item based on key:value
     *
     * @param string the config item key
     * @param string the config item value
     * @return   void
     */
    public function __set($key, $value)
    {
        $this->config[$key] = $value;
        return true;
    }

    /**
     * Method allows 'isset' to work on $this->data
     *
     * @param string $name Name of Variable Key $this->data[$name]
     * @return return mixed
     */
    public function __isset($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * Method allows 'unset' calls to work on $this->data
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->config[$key]);
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if(is_null($offset) === true)
        {
            $this->config[] = $value;
        }
        else
        {
            $this->config[$offset] = $value;
        }        
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>