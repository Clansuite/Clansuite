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
 * Clansuite Module
 *
 * Module:      Thememanager
 * Submodule:   Admin
 *
 * @author      Jens-André Koch  <vain@clansuite.com>
 * @copyright   Jens-André Koch, (2005 - onwards)
 * @since       0.2alpha
 *
 * @package     clansuite
 * @category    module
 * @subpackage  thememanager
 */
class Module_Thememanager_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    public function action_admin_show()
    {

        $themes = self::getThemesList();

        $smarty = $this->getView();
        $smarty->assign('themes',$themes);

        # Set Layout Template
        $smarty->setLayoutTemplate('admin/index.tpl');
        $this->prepareOutput();
    }

    /**
     * Get all avaiable Themes by parsing the dirs in THEMES
     *
     * @return array
     */
    static public function getThemesList()
    {
        # init themes array
        $themes = array();
        $file = '';
        $i = 1;

        # loop through THEMES dir
        $dirs = new DirectoryIterator(ROOT_THEMES);
        foreach ($dirs as $dir)
        {
           $i++;
           if( (!$dir->isDot()) && ($dir != '.svn') && ($dir != 'core') && (is_file($dir->getPathName().DS.'theme.xml')) )
           {
              $themeInfos = self::getThemeInformations($dir);
              $themes[$i] = $themeInfos;
              $themes[$i]['fullpath' ] = $dir->getPathName();
              $themes[$i]['dirname'] = (string) $dir;
           }
        }

       # sort and return
       asort ($themes);
       return $themes;
    }

    /**
     * Fetches Theme Informations from the theme.xml of specified directory
     *
     * @return $themeinfos simplexml object
     */
    static public function getThemeInformations($dir)
    {
        # get simple xml dataobject by loading theme.xml file
        $themeinfos_xml_obj = simplexml_load_file(ROOT_THEMES . $dir . DS . 'theme.xml');

        # die in case we fetched no object
        if($themeinfos_xml_obj === false)
        {
            die( $themeinfofile . 'corrupted!');
        }

        # structure object to array
        $themeinfos = Clansuite_Functions::object2array($themeinfos_xml_obj);

        return $themeinfos;
    }
}
?>