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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\View\Helper;

/**
 * Koch Framework Class for Breadcrumb Handling
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Breadcrumb
 */
class Breadcrumb
{
    /**
     * @var array $path contains the complete path structured as array
     */
    private static $path = array();

    /**
     * Adds a new breadcrumb
     *
     * @param string $title Name of the trail element
     * @param string $link Link of the trail element
     * @param string $replace_array_position Position in the array to replace with name/trail. Start = 0.
     */
    public static function add($title, $link = '', $replace_array_position = null)
    {
        # $breadcrumb contains the array structure for a new breadcrumb
        $breadcrumb = array('title' => '', 'link' => '');

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
     * Replace is a convenience method for add.
     * Remembering you that you might want to replace existing breadcrumbs.
     *
     * @param string $title Name of the trail element
     * @param string $link Link of the trail element
     * @param string $replace_array_position Position in the array to replace with name/trail. Start = 0.
     */
    public static function replace($title, $link = '', $replace_array_position = null)
    {
        self::add($title, $link, $replace_array_position);
    }

    /**
     * Adds breadcrumbs dynamically based on current module, submodule and action.
     * This might look a bit rough to the user.
     * Please prefer adding breadcrumbs manually via add().
     */
    public static function addDynamicBreadcrumbs()
    {
        $moduleName    = Koch_TargetRoute::getModuleName();
        $submoduleName = Koch_TargetRoute::getSubModuleName();
        $actionName    = Koch_TargetRoute::getActionNameWithoutPrefix();

        if(isset($moduleName) and $moduleName != 'controlcenter')
        {
            $url = 'index.php?mod=' . $moduleName;

            # Level 2
            if(isset($submoduleName))
            {
                $url .= '&amp;sub=' . $submoduleName;
                $moduleName .= ' '.ucfirst($submoduleName);
            }
            self::add($moduleName, $url);

            # Level 3
            if(isset($actionName))
            {

                $url .= '&amp;action=' . $actionName;
                self::add(ucfirst($actionName), $url);
            }
        }
    }

    /**
     * Getter for the breadcrumbs/trail array
     *
     * @param bool $dynamic_add If true, adds the breadcrumbs dynamically (default), otherwise just returns.
     * @return array self::$path The breadcrumbs array.
     */
    public static function getTrail($dynamic_add = true)
    {
        # if we got only one breadcrumb element, then only Home/CC was set before
        if(count(self::$path) == 1 and $dynamic_add === true)
        {
            # add crumbs automatically
            self::addDynamicBreadcrumbs();
        }

        return self::$path;
    }

    /**
     * Breadcrumb Level 0    =>    Home or Controlcenter
     *
     * @param string $moduleName The module name.
     * @param string $submoduleName The submodule name.
     */
    public static function initBreadcrumbs($moduleName = null, $submoduleName = null)
    {
        # ControlCenter (Backend)
        if($moduleName == 'controlcenter' or $submoduleName == 'admin')
        {
            Koch_Breadcrumb::add('Control Center', '/index.php?mod=controlcenter');
        }
        else # Home (Frontend)
        {
            Koch_Breadcrumb::add('Home');
        }
    }

    /**
     * Resets the breadcrumbs array.
     */
    public static function resetBreadcrumbs()
    {
        self::$path = array();
    }
}
?>