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
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Theme
 *
 * This class provides abstracted access to a theme's theme_info.xml file.
 */
class Clansuite_Theme implements ArrayAccess
{
    var $theme;
    var $theme_info_array;

    /**
     * Constructor, or what ;)
     *
     * @param string $theme Name of the Theme.
     * @return Clansuite_Theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;

        $this->getThemeInfos($theme);

        return $this;
    }
    
    /**
     * Returns Theme Infos as array.
     *
     * @param string $theme Name of the Theme.
     * @return array Theme_Info.xml as Array.
     */
    public function getThemeInfos($theme = null)
    {
        # set theme
        if(isset($theme))
        {
            $this->theme = $theme;
        }

        # figure out, if it is a frontend or backend theme
        if(is_file(ROOT_THEMES_FRONTEND . $this->theme . DS . 'theme_info.xml'))
        {
            $theme_info_file = ROOT_THEMES_FRONTEND . $this->theme . DS . 'theme_info.xml';
        }
        elseif(is_file(ROOT_THEMES_BACKEND . $this->theme . DS . 'theme_info.xml'))
        {
            $theme_info_file = ROOT_THEMES_BACKEND . $this->theme . DS . 'theme_info.xml';
        }
        else
        {
            throw new Clansuite_Exception('Theme not found: '. $this->theme, '100');
        }

        # fetch the xml handler
        include ROOT_CORE . '/config/xml.config.php';

        # read theme info xml file into array
        return $this->theme_info_array = Clansuite_Config_XMLHandler::readConfig($theme_info_file);
    }

    public function getName()
    {
        return $this->theme_info_array['name'];
    }

    public function getAuthor()
    {
        return $this->theme_info_array['name'];
    }

    public function getVersion()
    {
        return $this->theme_info_array['theme_version'];
    }

    public function getRequiredClansuiteVersion()
    {
        return $this->theme_info_array['required_clansuite_version'];
    }

    public function getDate()
    {
        return $this->theme_info_array['date'];
    }

    public function getLayoutFiles()
    {
        return $this->theme_info_array['layoutfiles'];
    }

    public function getCssFiles()
    {
        return $this->theme_info_array['cssfiles'];
    }

    public function getRenderEngine()
    {
        return $this->theme_info_array['renderengine'];
    }

    public function isBackendTheme()
    {
        return (bool) $this->theme_info_array['backendtheme'];
    }
    
    public static function isFrontendTheme()
    {
        return (bool) $this->theme_info_array['backendtheme'] === true ? false : true;
    }

    public function getArray()
    {
        return $this->theme_info_array;
    }
    
    /**
     * Gets item based on keyname
     *
     * @param    string    the config item key
     * @return   void
     */
    public function __get($configkey)
    {
        if(isset($this->theme_info_array[$configkey]))
        {
            return $this->theme_info_array[$configkey];
        }
        else
        {
            return null;
        }
    }

    /**
     * Set item based on key:value
     *
     * @param string the item key
     * @param string the item value
     * @return   void
     */
    public function __set($key, $value)
    {
        $this->theme_info_array[$key] = $value;
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
        return isset($this->theme_info_array[$name]);
    }

    /**
     * Method allows 'unset' calls to work on $this->data
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->theme_info_array[$key]);
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->theme_info_array[$offset]);
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
        unset($this->theme_info_array[$offset]);
        return true;
    }
}
?>
