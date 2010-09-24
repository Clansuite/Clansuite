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
class Clansuite_Theme
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

    public function getArray()
    {
        return $this->theme_info_array;
    }
}
?>
