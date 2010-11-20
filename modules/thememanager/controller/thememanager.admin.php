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
    * @version    SVN: $Id: users.module.php 2634 2008-12-12 22:07:48Z vain $
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Thememanager_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Thememanager
 */
class Clansuite_Module_Thememanager_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        #$this->getModuleConfig();
    }

    public function action_admin_show()
    {
        $view = $this->getView();
        $view->assign('themes', $this->getThemesList());
        $this->display();
    }

    public function action_admin_delete()
    {
        $theme_to_delete  = (string) $this->request->getParameterFromGet('theme');

        if(isset($theme_to_delete))
        {
            $themes = $this->getThemesList();

            foreach ($themes as $theme)
            {
                if($theme['dirname'] == $theme_to_delete)
                {
                    #Clansuite_Functions::delete_dir_content($theme['fullpath']);
                }
            }

            $this->response->redirectNoCache('/thememanager/admin', 2, 302, _('success#Theme deleted: ') . $theme_to_delete);
        }
        else
        {
           $this->redirect('/thememanager/admin');
        }
    }

    public function getThemes($themes_directory)
    {
        # init themes array
        $themes = array();
        $file = '';
        $i = 1;

        # loop through ROOT_THEMES dir
        $dirs = new DirectoryIterator($themes_directory);
        foreach($dirs as $dir)
        {
            $i++;
            # exclude .svn and core dir, take only dirs with theme_info.xml in it
            if((!$dir->isDot()) and ($dir != '.svn') and ($dir != 'core') and (is_file($dir->getPathName() . DS . 'theme_info.xml')))
            {
                # add xml infos from file
                $theme_info[$i] = self::parseThemeInformations($themes_directory . $dir);

                # add fullpath
                $theme_info[$i]['fullpath'] = $dir->getPathName();

                # add templatefilename
                if(isset($theme_info[$i]['layoutfiles']['layoutfile']['@attributes']['tpl']))
                {
                    $theme_info[$i]['layouttpl'] = $theme_info[$i]['layoutfiles']['layoutfile']['@attributes']['tpl'];
                    $theme_info[$i]['layoutpath'] = $theme_info[$i]['fullpath'] . DS . $theme_info[$i]['layouttpl'];
                }

                # add dirname
                $theme_info[$i]['dirname'] = (string) $dir;

                /**
                 * is this theme activated as global fallback ?
                 * @see clansuite main config
                 */
                $this->getClansuiteConfig();
                if($this->config['template']['frontend_theme'] == $dir or
                   $this->config['template']['backend_theme']  == $dir)
                {
                    $theme_info[$i]['globally_active'] = true;
                }
                else
                {
                    $theme_info[$i]['globally_active'] = false;
                }

                # is this theme active for the individual user?
                if($_SESSION['user']['frontend_theme'] == $dir or
                   $_SESSION['user']['backend_theme']  == $dir)
                {
                    $theme_info[$i]['user_active'] = true;
                }
                else
                {
                    $theme_info[$i]['user_active'] = false;
                }

                # add preview image (preview_image should contain 2 files: [0]preview.img and [1]preview_thumb.img)
                $preview_image = glob($themes_directory . $dir . DS . 'preview*.{jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG}', GLOB_BRACE);

                # turn ROOT_THEMES path into WWW_ROOT
                if(strpos(strtolower($themes_directory), 'frontend'))
                {
                    $preview_image = str_replace(ROOT_THEMES_FRONTEND, WWW_ROOT_THEMES_FRONTEND . '', $preview_image);
                }
                else
                {
                    $preview_image = str_replace(ROOT_THEMES_BACKEND, WWW_ROOT_THEMES_BACKEND . '', $preview_image);
                }

                # fix slashes
                $preview_image = str_replace('\\', '/', $preview_image);

                if(is_array($preview_image) and (empty($preview_image) == false))
                {
                    $theme_info[$i]['preview_image'] = $preview_image[0];  # path to [0]preview
                    $theme_info[$i]['preview_thumbnail'] = $preview_image[1];  # path to [1]preview_thumb
                }
                else # show only nopreview.gif as thumbnail
                {
                    $theme_info[$i]['preview_thumbnail'] = WWW_ROOT_THEMES . 'core/images/nopreview.jpg';
                }
            }
        }

        # sort and return
        asort($theme_info);
        return $theme_info;
    }

    /**
     * Get array with pieces of information about all the avaiable Themes (Skins)
     *
     * @return array
     */
    public function getThemesList()
    {
        $frontend_theme_infos = $this->getThemes(ROOT_THEMES_FRONTEND);
        $backend_theme_infos  = $this->getThemes(ROOT_THEMES_BACKEND);
        $theme_infos = array_merge($frontend_theme_infos, $backend_theme_infos);
        return $theme_infos;
    }

    /**
     * Fetches Theme Informations from the theme.xml of specified directory
     *
     * @param $themedirectory the directory of the theme
     * @return $themeinfos simplexml object
     */
    public static function parseThemeInformations($themedir)
    {
        $theme_info_file = $themedir . DS . 'theme_info.xml';

        # ensure we have simplexml available
        if(false == function_exists('simplexml_load_file'))
        {
            throw new Clansuite_Exception('Missing simplexml_load_file() function');
        }

        if(is_file($theme_info_file) and is_readable($theme_info_file))
        {
            # get simple xml dataobject by loading theme.xml file
            # @todo i wonder if there is a way to get rid of the error-supression, without adding to much overhead
            $themeinfos_XML_obj = @simplexml_load_file($theme_info_file);
        }
        else
        {
            throw new Clansuite_Exception('The Theme Description File (' . $theme_info_file . ') is missing or not readable.');
        }

        # die in case we fetched no object
        if(false === $themeinfos_XML_obj)
        {
            throw new Clansuite_Exception('The Description File (' . $theme_info_file . ') of the "' . $themedir . '" Theme is corrupted! Check it\'s XML Syntax and Structure.');
        }

        # structure object to array
        $themeinfos = Clansuite_Functions::object2array($themeinfos_XML_obj);

        return $themeinfos;
    }

    public static function generateThemeInfoXML()
    {

    }

    public static function widget_frontend_themechooser()
    {

    }

    public static function widget_backend_themechooser()
    {

    }
}
?>