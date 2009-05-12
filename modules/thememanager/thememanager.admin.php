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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: users.module.php 2634 2008-12-12 22:07:48Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module Administration - Thememanager
 *
 * @author      Jens-André Koch  <vain@clansuite.com>
 * @copyright   Jens-André Koch, (2005 - onwards)
 * @since       0.2alpha
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Thememanager
 */
class Module_Thememanager_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        $this->config = $this->getClansuiteConfig();
    }

    public function action_admin_show()
    {
        $smarty = $this->getView();
        $smarty->assign('themes', $this->getThemesList());
        $smarty->setLayoutTemplate('admin/index.tpl');
        $this->prepareOutput();
    }

    /**
     * Get all avaiable Themes (Skins) by parsing the dirs in ROOT_THEMES
     *
     * @return array
     */
    public function getThemesList()
    {
        # init themes array
        $themes = array();
        $file = '';
        $i = 1;

        # loop through ROOT_THEMES dir
        $dirs = new DirectoryIterator(ROOT_THEMES);
        foreach ($dirs as $dir)
        {
            $i++;

            # exclude .svn and core dir, take only dirs with theme_info.xml in it
            if( (!$dir->isDot()) and ($dir != '.svn') and ($dir != 'core') and (is_file($dir->getPathName().DS.'theme_info.xml')) )
            {
                # add xml infos from file
                $theme_info[$i]               = self::parseThemeInformations($dir);

                # add fullpath
                $theme_info[$i]['fullpath' ]  = $dir->getPathName();

                # add dirname
                $theme_info[$i]['dirname']    = (string) $dir;

                # is this theme activated?
                if( $this->config['template']['theme'] == $dir )
                {
                    $theme_info[$i]['activated']  = true;
                }
                else
                {
                    $theme_info[$i]['activated']  = false;
                }

                # add preview image (preview_image should contain 2 files: [0]preview.img and [1]preview_thumb.img)
                $preview_image = glob( ROOT_THEMES. $dir . DS . 'preview*.{jpg,png,gif,jpeg,JPG,PNG,GIF,JPEG}', GLOB_BRACE);

                # turn ROOT_THEMES path into WWW_ROOT
                $preview_image = str_replace(ROOT_THEMES, WWW_ROOT_THEMES.'/', $preview_image);
                # fix slashes
                $preview_image = str_replace('\\','/', $preview_image);

                if ( is_array($preview_image) and (empty($preview_image) == false))
                {
                    $theme_info[$i]['preview_image']     = $preview_image[0];  # path to [0]preview
                    $theme_info[$i]['preview_thumbnail'] = $preview_image[1];  # path to [1]preview_thumb
                }
                else # show only nopreview.gif as thumbnail
                {
                    $theme_info[$i]['preview_thumbnail'] = WWW_ROOT_THEMES.'/core/images/nopreview.jpg';
                }
            }
        }

        # sort and return
        asort ($theme_info);
        return $theme_info;
    }

    /**
     * Fetches Theme Informations from the theme.xml of specified directory
     *
     * @param $themedirectory the directory of the theme
     * @return $themeinfos simplexml object
     */
    public static function parseThemeInformations($themedir)
    {
        $theme_info_file = ROOT_THEMES . $themedir . DS . 'theme_info.xml';

        # ensure we have simplexml available
        if (false == function_exists('simplexml_load_file') )
        {
            throw new Clansuite_Exception('Missing simplexml_load_file() function');
        }

        # get simple xml dataobject by loading theme.xml file
        # @todo i wonder if there is a way to get rid of the error-supression, without adding to much overhead
        $themeinfos_XML_obj = @simplexml_load_file($theme_info_file);

        # die in case we fetched no object
        if (false === $themeinfos_XML_obj)
        {
            throw new Clansuite_Exception('The Description File ('.$theme_info_file.') of the "'.$themedir.'" Theme is corrupted! Check it\'s XML Syntax and Structure.');
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