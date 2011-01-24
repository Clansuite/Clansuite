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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core Class for Breadcrumb Handling
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Breadcrumb
 */
class Clansuite_Breadcrumb
{
    /**
     * @var array $path contains the complete path structured as array
     */
    private static $path = array();

    /**
     * @var array $breadcrumb contains the array structure for a new breadcrumb
     */
    private static $breadcrumb = array('title' => '',
                                       'link' => '',
                                       'module' => '',
                                       'submodule' => '',
                                       'action' => '');

    /**
     * Adds a new breadcrumb
     *
     * @param string $title Name of the trail element
     * @param string $link Link of the trail element
     * @param string $replace_array_position Position in the array to replace with name/trail. Start = 0.
     */
    public static function add($title, $link = '', $replace_array_position = null)
    {
        # init new breadcrumb array
        $breadcrumb = self::$breadcrumb;

        # set data to breadcrumb
        $breadcrumb['title'] = ucfirst($title);
        $breadcrumb['link']  = WWW_ROOT . ltrim($link, '/');

        # replace
        if(isset($replace_array_position))
        {
            self::$path[$replace_array_position] = $breadcrumb;
        }
        else # no, just add
        {
            self::$path[] = $breadcrumb;
        }

        unset($breadcrumb);
    }

    /**
     * Replace is a convenience method for add - it can
     * Remembering you that you might want to replace existing breadcrumbs.
     *
     * @param string $title Name of the trail element
     * @param string $link Link of the trail element
     * @param string $replace_array_position Position in the array to replace with name/trail. Start = 0.
     */
    public static function replace($title, $link = '', $replace_array_position = null)
    {
        self::add($title, $link = '', $replace_array_position);
    }

    /**
     * Adds breadcrumbs dynamically based on current module, submodule and action
     * This might look a bit rough to the user.
     * Please prefer adding bc's manually via add().
     */
    public static function addDynamicBreadcrumbs()
    {
        $moduleName    = Clansuite_TargetRoute::getModuleName();
        $submoduleName = Clansuite_TargetRoute::getSubModuleName();
        $actionName    = Clansuite_TargetRoute::getActionName();

        $url = 'index.php?mod=' . $moduleName;

        if((mb_strlen($submoduleName) > 0))
        {
            $url .= '&amp;sub=' . $submoduleName;
            self::add(_($submoduleName), $url);
        }
        else
        {
            self::add(_($moduleName), $url);
        }

        if((mb_strlen($actionName) > 0) and $actionName != 'action_show')
        {
            #$action = substr($actionName, 7);
            $url .= '&amp;action=' . $action;
            self::add(_(ucfirst($action)), $url);
        }
    }

    /**
     * Getter for the breadcrumbs/trail array
     */
    public static function getTrail()
    {
        # if we got only one breadcrumb element, then only Home was set before
        if(count(self::$path) == 1)
        {
            # add crumbs automatically
            self::addDynamicBreadcrumbs();
        }

        return self::$path;
    }

    public static function initBreadcrumbs()
    {
        $moduleName     = Clansuite_TargetRoute::getModuleName();
        $submoduleName  = Clansuite_TargetRoute::getSubModuleName();
        $actionName     = Clansuite_TargetRoute::getActionName();

        /**
         * Breadcrumb Level 0    =>    Home or Controlcenter
         */

        # ControlCenter (Backend)
        if($moduleName == 'controlcenter' or $submoduleName == 'admin')
        {
            # Set Pagetitle "Control Center" and Breadcrumb-Link = '/index.php?mod=controlcenter'
            Clansuite_Breadcrumb::add('Control Center', '/controlcenter');
        }
        else # Home (Frontend)
        {
            Clansuite_Breadcrumb::add('Home');
        }

        /**
         * Breadcrumb Level 1    =>    Module
         */
        if($moduleName != 'controlcenter')
        {
            # Construct relative URL
            $url  = 'index.php?mod=' . $moduleName;
            $trailName = $moduleName;

            # Add action Part only, if not no submodule following
            if( (mb_strlen($actionName) > 0) and (mb_strlen($submoduleName) == 0))
            {
                $url .= '&amp;action=' . substr($actionName, 7);
            }

            # if this is an request to an submodule admin, we append that to the URL
            if( (mb_strlen($submoduleName) > 0)  and ($submoduleName == 'admin'))
            {
                $url .= '&amp;sub=admin';
            }
        }

        # add submodule part
        if(mb_strlen($submoduleName) > 0 and ($submoduleName != 'admin'))
        {
            # Construct URL
            $url .= '&amp;sub=' . $submoduleName;
            $trailName = $submoduleName;

            # Add action Part now
            if(mb_strlen($actionName) > 0)
            {
                $url .= '&amp;action=' . substr($actionName, 7);
            }
        }

        # Set Pagetitle and Breadcrumbs for that SubModule
        Clansuite_Breadcrumb::add( T_( $trailName ), $url );

        # Debug Display for Breadcumbs
        # Clansuite_Debug::firebug(Clansuite_Breadcrumb::getTrail());
    }
}
?>