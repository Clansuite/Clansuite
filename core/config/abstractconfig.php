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

namespace Koch\Config;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch Framework - Abstract Base Class for Config
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Configuration
 */
abstract class AbstractConfig /*extends ArrayObject*/ implements \ArrayAccess
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
     * Gets a Config Value or sets a default value
     *
     * @example
     * Usage for one default variable:
     * self::getConfigValue('items_newswidget', '8');
     * Gets the value for the key items_newswidget from the moduleconfig or sets the value to 8.
     *
     * Usage for two default variables:
     * self::getConfigValue('items_newswidget', $_GET['numberNews'], '8');
     * Gets the value for the key items_newswidget from the moduleconfig or sets the value
     * incomming via GET, if nothing is incomming, sets the default value of 8.
     *
     * @param string $keyname The keyname to find in the array.
     * @param mixed $default_one A default value, which is returned, if the keyname was not found.
     * @param mixed $default_two A default value, which is returned, if the keyname was not found and default_one is null.
     */
    public function getConfigValue($keyname, $default_one = null, $default_two = null)
    {
        # try a lookup of the value by keyname
        $value = Clansuite_Functions::array_find_element_by_key($keyname, $this->config);

        # return value or default
        if(empty($value) === false)
        {
            return $value;
        }
        elseif( $default_one != null )
        {
            return $default_one;
        }
        elseif( $default_two != null )
        {
            return $default_two;
        }
        else
        {
            return null;
        }
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

    /**
     * ArrayAccess::offsetExists()
     *
     * @param   mixed $offset
     * @return  mixed Clansuite_Config value
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    /**
     * ArrayAccess::offsetGet()
     *
     * @param   mixed $offset
     * @return  mixed Clansuite_Config value
     */
    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    /**
     * ArrayAccess::offsetSet()
     *
     * @param   mixed $offset
     * @param   mixed $value
     */
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

    /**
     * ArrayAccess::offsetUnset()
     *
     * @param   mixed $offset
     * @return  bool true
     */
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>