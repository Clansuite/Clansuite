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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: clansuite.config.php 2009 2008-05-07 15:34:26Z xsign $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite_Config_Base
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Config
 */
abstract class Clansuite_Config_Base
{
    /**
     * Returns $this->config Object as Array
     *
     * @access   public
     * @return   config array
     */
    public function toArray()
    {
        $array = array();
        $array = $this->config;
        return $array;
    }

    /**
     * Gets a config file item based on keyname
     *
     * @access   public
     * @param    string    the config item key
     * @return   void
     */
    public function __get($configkey)
    {
        if ( isset($this->config[$configkey]) )
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
     * @access   public
     * @param    string    the config item key
     * @param    string    the config item value
     * @return   void
     *
     */
    public function __set($configkey, $configvalue)
    {
        $this->config[$configkey] = $configvalue;
        return true;
    }

    // method that will allow 'isset' to work on these variables
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // method to allow 'unset' calls to work on these variables
    public function __unset($key)
    {
        unset($this->config[$key]);
        #echo 'Variable was unset:'. $key;
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
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>