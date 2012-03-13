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
    * @copyright  Jens-Andr� Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\View\Helper;

use Koch\View\AbstractRenderer;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch_Theme
 *
 * This class provides abstracted (object) access to a theme's theme_info.xml file.
 */
class Theme
{
    public $theme = '';
    public $theme_info = array();

    /**
     * Constructor, or what ;)
     *
     * @param string $theme Name of the Theme.
     * @return Koch_Theme
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
            throw new \Exception('No Themename given.', '100');
        }
    }

    /**
     * Getter for the "theme_info.xml" file of the currently activated theme.
     *
     * @return string Filepath to "theme_info.xml" of the currently activated theme.
     */
    public function getCurrentThemeInfoFile()
    {
        # get array for frontend or backend theme
        $themepaths = AbstractRenderer::getThemeTemplatePaths();

        foreach($themepaths as $themepath)
        {
            $theme_info_file = $themepath . DS . 'theme_info.xml';

            if(is_file($theme_info_file_path))
            {
                return $theme_info_file;
            }
        }
    }

    /**
     * Looks for the requested theme in the frontend and backend theme folder
     * and returns the theme path.
     *
     * @param string $theme Theme name.
     * @return string Path to theme.
     */
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

    /**
     * Looks for the requested theme in the frontend and backend theme folder
     * and returns the web path of the theme.
     *
     * @param string $theme Theme name.
     * @return string Webpath of theme (for usage in templates).
     */
    public function getWebPath($theme = null)
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

    /**
     * Returns "theme_info.xml" for the requested theme.
     *
     * @param string $theme Theme name.
     * @return string File path to "theme_info.xml" file.
     * @throws Koch_Exception
     */
    public function getInfoFile($theme)
    {
        $theme_info_file = $this->getPath($theme) . 'theme_info.xml';

        if(is_file($theme_info_file))
        {
            return $theme_info_file;
        }
        else
        {
            throw new \Exception('The Themeinfo file was not found on Theme: '. $theme, '100');
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
        $theme_info_array = \Koch\Config\Adapter\XML::readConfig($theme_info_file);

        #Koch_Debug::printR($theme_info_array);

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
        $browserInfo = new \Koch\Tools\Browserinfo();

        if($browserInfo->isIE())
        {
            $cssPostfix = '_ie';
        }
        else
        {
            $cssPostfix = '';
        }


        if(isset($this->theme_info['css']['mainfile']))
        {
            $part = explode('.', $this->theme_info['css']['mainfile']);
            $cssname = $part[0] . $cssPostfix . '.' . $part[1];
            return $this->getWebPath() . 'css/' . $cssname;
        }
        elseif(false === isset($this->theme_info['css']['mainfile']))
        {
            # maybe we have a theme css file named after the theme
            $css_file = $this->getWebPath() . 'css/' . $this->getName() . '.css';

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
            throw new \Exception('No Layout File defined. Check ThemeInfo File of ' . $this->getName(), 9090);
        }
    }

    public function getJSFile()
    {
        if(isset($this->theme_info['javascript']['mainfile']))
        {
            return $this->getWebPath() . 'javascript/' . $this->theme_info['javascript']['mainfile'];
        }
        elseif(false === isset($this->theme_info['javascript']['mainfile']))
        {
            # maybe we have a main javascript file named after the theme
            $js_file = $this->getWebPath() . 'javascript/' . $this->getName() . '.js';

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

    public static function getThemeDirectories()
    {
        $themes = array();

        $themes = array_merge(
            self::iterateDir(ROOT_THEMES_FRONTEND, 'frontend'),
            self::iterateDir(ROOT_THEMES_BACKEND, 'backend')
        );

        return $themes;
    }

    /**
     * Iterates over a theme dir (backend / frontend) and fetches some data.
     *
     * @param string $dir ROOT_THEMES_FRONTEND, ROOT_THEMES_BACKEND
     * @param string $type 'frontend' or 'backend'
     * @param boolean $only_index_name
     * @return string
     */
    protected static function iterateDir($dir, $type, $only_index_name = true)
    {
        $dirs = '';
        $dir_tmp = '';
        $i = 0;
        $themes = array();

        $dirs = new \DirectoryIterator( $dir );

        foreach($dirs as $dir)
        {
            /**
             * Skip early on dots, like "." or ".." or ".svn", by cheching the first char.
             * we can not use DirectoryIterator::isDot() here, because it only checks "." and "..".
             */
            $dir_tmp = $dir->getFilename();

            if ($dir_tmp{0} === '.')
            {
                continue;
            }

            /**
             * take only directories in account, which contain a "theme_info.xml" file
             */
            if(is_file($dir->getPathName() . DS . 'theme_info.xml'))
            {
                $i = $i + 1;

                if($only_index_name === false)
                {
                    # add fullpath
                    $themes[$i]['path'] = $dir->getPathName();

                    # set frontend as type
                    $themes[$i]['type']    = $type;

                    # add dirname
                    $themes[$i]['name'] = $type . DS .  (string) $dir;
                }
                else
                {
                    # add dirname
                    $themes[$i] = $type . DS . (string) $dir;
                }
            }
        }

        unset($i, $dirs, $dir_tmp);

        return $themes;
    }
}
?>