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
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Mapper
 *
 * Provides helper methods to transform (map)
 * (a) the controller name into the specific application classname and filename
 * (b) the action name into the specific application actioname.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Mapper
 */
class Clansuite_Mapper
{
    /**
     * Classname prefix for modules
     *
     * @const string
     */
    const MODULE_CLASS_PREFIX = 'Clansuite_Module';

    /**
     * Method prefix for module actions
     *
     * @const string
     */
    const METHOD_PREFIX = 'action';

    /**
     * @var string Name of the Default Module
     */
    private static $defaultModule = 'news';

    /**
     * @var string Name of the Default Action
     */
    private static $defaultAction = 'show';

    /**
     * Maps the controller and subcontroller (optional) to filename
     *
     * @param string $controller Name of Controller
     * @param string $subcontroller Name of SubController (optional)
     * @return string filename
     */
    public static function mapControllerToFilename($module_path, $controller, $subcontroller = null)
    {
        $filename = '';
        $filename_postfix = '';

        # construct the module_path, like "/clansuite/modules/news/" + "controller/"
        $module_path = $module_path . 'controller' . DS;

        # subcontroller
        if(isset($subcontroller) and 'admin' == $subcontroller)
        {
            $filename_postfix = '.admin.php';
        }
        elseif(isset($subcontroller) and $subcontroller != 'admin') # any subcontroller name as postfix
        {
            $filename_postfix = '.'.$subcontroller.'.php';
        }
        else # apply standard postfix
        {
            $filename_postfix = '.module.php';
        }

        $filename = $module_path . $controller . $filename_postfix;

        unset($filename_postfix, $module_path);

        return $filename;
    }

    /**
     * Maps Controller and SubController (optional)
     *
     * @param string $controller Name of Controller
     * @param string $subcontroller Name of SubController (optional)
     *
     * @return string classname
     */
    public static function mapControllerToClassname($controller, $subcontroller = null)
    {
        $classname = '';

        # attach controller
        $classname .= '_' . ucfirst($controller);

        # attach subcontroller to classname
        if(isset($subcontroller))
        {
            $classname .= '_' . ucfirst($subcontroller);
        }

        return self::MODULE_CLASS_PREFIX . $classname;
    }

    /**
     * Maps the action to it's method name.
     * The prefix 'action_' (pseudo-namesspace) is used for all actions.
     * Example: A action named "show" will be mapped to "action_show()"
     * This is also a way to ensure some kind of whitelisting via namespacing.
     *
     * The use of submodules like News_Admin is also supported.
     * In this case the actionname is action_admin_show().
     *
     * @param  string $action the action
     * @param  string $submodule the submodule
     * @return string the mapped method name
     */
    public static function mapActionToActioname($action, $submodule = null)
    {
        # set default value for action, when not set by URL
        if(false === isset($action))
        {
            $action = self::$defaultAction;
        }

        # if a $submodule is set, use it as a PREFIX on $action
        if(isset($submodule))
        {
            $action = $submodule . '_' . $action;
        }

        # all clansuite actions are prefixed with 'action_'
        return self::METHOD_PREFIX . '_' . $action;
    }
}
?>