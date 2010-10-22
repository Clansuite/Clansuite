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
    public $theme = '';
    public $theme_info = array();

    /**
     * Constructor, or what ;)
     *
     * @param string $theme Name of the Theme.
     * @return Clansuite_Theme
     */
    public function __construct($theme)
    {
        $this->setThemename($theme);

        $this->getInfoArray($theme);

        return $this;
    }

    public function setThemename($theme)
    {
        # set theme
        if(isset($theme))
        {
            $this->theme = $theme;
        }
        else
        {
            throw new Clansuite_Exception('No Themename given.', '100');
        }
    }

    public function getCurrentThemeInfoFile()
    {
        # get array for frontend or backend theme
        $themepaths = Clansuite_Renderer_Base::getThemeTemplatePaths();

        foreach($themepaths as $themepath)
        {
            $theme_info_file = $themepath . DS . 'theme_info.xml';

            if(is_file($theme_info_file_path))
            {
                return $theme_info_file;
            }
        }
    }

    public function getPath($theme = null)
    {
        if($theme == null)
        {
            $theme = $this->getName();
        }

        if(is_dir(ROOT_THEMES_FRONTEND . $theme . DS))
        {
            return ROOT_THEMES_FRONTEND . $theme . DS;
        }

        if(is_dir(ROOT_THEMES_BACKEND . $theme . DS))
        {
            return ROOT_THEMES_BACKEND . $theme . DS;
        }
    }

    public function getWWWPath($theme = null)
    {
        if($theme == null)
        {
            $theme = $this->getName();
        }

        # check absolute, return www
        if(is_dir(ROOT_THEMES_FRONTEND . $theme ))
        {
             return WWW_ROOT_THEMES_FRONTEND . $theme . '/';
        }

        # check absolute, return www
        if(is_dir(ROOT_THEMES_BACKEND . $theme))
        {
            return WWW_ROOT_THEMES_BACKEND . $theme . '/';
        }
    }

    public function getInfoFile($theme)
    {
        $theme_info_file = $this->getPath($theme) . 'theme_info.xml';

        if(is_file($theme_info_file))
        {
            return $theme_info_file;
        }
        else
        {
            throw new Clansuite_Exception('The Themeinfo file was not found on Theme: '. $theme, '100');
        }

        return $theme_info_file;
    }

    /**
     * Returns Theme Infos as array.
     *
     * @param string $theme Name of the Theme.
     * @return array Theme_Info.xml as Array.
     */
    public function getInfoArray($theme = null)
    {
        $theme_info_file = $this->getInfoFile($theme);

        # read theme info xml file into array
        $theme_info_array = Clansuite_Config_XML::readConfig($theme_info_file);

        #Clansuite_Debug::printR($theme_info_array);

        # when setting array as object property remove the inner theme array
        $this->theme_info = $theme_info_array['theme'];

        return $this->theme_info;
    }

    /**
     * --------------------------------------------------------------------------------------------
     *  GETTERS
     * --------------------------------------------------------------------------------------------
     */

    /**
     * Gets shortname or folder name.
     *
     * @return string short name / folder name.
     */
    public function getName()
    {
        return $this->theme;
    }

    public function getFullName()
    {
        return $this->theme_info['name'];
    }

    public function getAuthor()
    {
        return $this->theme_info['authors'];
    }

    public function getVersion()
    {
        return $this->theme_info['theme_version'];
    }

    public function getRequiredClansuiteVersion()
    {
        return $this->theme_info['required_clansuite_version'];
    }

    public function getDate()
    {
        return $this->theme_info['date'];
    }

    public function getLayout()
    {
        return $this->theme_info['layout'];
    }

    public function getCss()
    {
        return $this->theme_info['css'];
    }

    public function getCSSFile()
    {
        if(isset($this->theme_info['css']['mainfile']))
        {
            return $this->getWWWPath() . 'css/' . $this->theme_info['css']['mainfile'];
        }
        elseif(false === isset($this->theme_info['css']['mainfile']))
        {
            # maybe we have a theme css file named after the theme
            $css_file = $this->getWWWPath() . 'css/' . $this->getName() . '.css';

            if(is_file($css_file))
            {
                return $css_file;
            }
        }
        else # css is hopefully hardcoded or missing !
        {
            return null;
        }
    }

    public function getLayoutFile()
    {
        if(isset($this->theme_info['layout']['mainfile']))
        {
            #return $this->getPath() . $this->theme_info['layout']['mainfile'];
            return $this->theme_info['layout']['mainfile'];
        }
        elseif(false === isset($this->theme_info['layout']['mainfile']))
        {
            # maybe we have a main template css file named after the theme
            # $layout_file = $this->getPath() . $this->getName() . '.tpl';
            $layout_file = $this->getName() . '.tpl';

            if(is_file($layout_file))
            {
                return $layout_file;
            }
        }
        else # no main layout found !
        {
            throw new Clansuite_Exception('No Layout File defined. Check ThemeInfo File of ' . $this->getName(), 9090);
        }
    }

    public function getJSFile()
    {
        if(isset($this->theme_info['javascript']['mainfile']))
        {
            return $this->getWWWPath() . 'javascript/' . $this->theme_info['javascript']['mainfile'];
        }
        elseif(false === isset($this->theme_info['javascript']['mainfile']))
        {
            # maybe we have a main javascript file named after the theme
            $js_file = $this->getWWWPath() . 'javascript/' . $this->getName() . '.js';

            if(is_file($js_file))
            {
                return $js_file;
            }
        }
        else # no main javascript file found !
        {
            return null;
        }
    }

    public function getRenderEngine()
    {
        return $this->theme_info['renderengine'];
    }

    public function isBackendTheme()
    {
        return (bool) $this->theme_info['backendtheme'];
    }

    public function isFrontendTheme()
    {
        return (bool) $this->theme_info['backendtheme'] === true ? false : true;
    }

    public function getArray()
    {
        return $this->theme_info;
    }
}
?>