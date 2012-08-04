<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Mvc;

/**
 * Mapper
 *
 * Provides helper methods to transform (map)
 * (a) the controller name into the specific application classname and filename
 * (b) the action name into the specific application actioname.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Mapper
 */
class Mapper extends \ArrayObject
{
    /* @const string Classname prefix for modules = Namespace */
    const MODULE_CLASS_PREFIX = 'Clansuite\Application\Modules';

    /* @const string postfix for module controller files */
    const MODULE_FILE_POSTFIX = 'Controller.php';

    /* @const string Method prefix for module actions */
    const METHOD_PREFIX = 'action';

    /* @const string Name of the Default Module */
    const DEFAULT_MODULE = 'news';

    /* @const string Name of the Default Action */
    const DEFAULT_ACTION = 'index';

    /**
     * Maps the controller and subcontroller (optional) to filename
     *
     * @param  string $controller    Name of Controller
     * @param  string $subcontroller Name of SubController (optional)
     * @return string filename
     */
    public static function mapControllerToFilename($module_path, $controller, $subcontroller = null)
    {
        // append module_path, e.g. "/clansuite/modules/news/" + "Controller/"
        $module_path .= 'Controller/';

        // if subcontroller set and name valid ([a-zA-Z0-9])
        if ($subcontroller !== null and ctype_alnum($subcontroller)) {
            // append subcontroller to controller, e.g. "News" + "Admin"
            $controller .= ucfirst($subcontroller);
        }

        // Mapping Example:
        // "/clansuite/modules/news/" + "Controller/" + "Index" + "Controller.php"
        // "/clansuite/modules/news/" + "Controller/" + "IndexSub" + "Controller.php"
        return $module_path . $controller . self::MODULE_FILE_POSTFIX;
    }

    /**
     * Maps Controller and SubController (optional)
     *
     * @param  string $controller    Name of Controller
     * @param  string $subcontroller Name of SubController (optional)
     * @return string classname
     */
    public static function mapControllerToClassname($controller, $subcontroller = null)
    {
        $classname = '';

        // attach controller (<Controller_FirstPart>)
        $classname .= '\\' . $controller;

        // "\News\Controller\News" (<Modulename>\Controller\<Controller_FirstPart>)
        $classname .= '\Controller' . $classname;

        // attach subcontroller to classname (<Modulename>\Controller\<Controller_FirstPart><SubController>)
        if ($subcontroller !== null) {
            $classname .= ucfirst($subcontroller);
        }

        // "Clansuite\Application\Module\News\Controller\" + "News" + "Controller"
        return self::MODULE_CLASS_PREFIX . $classname . 'Controller';
    }

    /**
     * Maps the action to it's method name.
     * The prefix 'action_' (pseudo-namespace) is used for all actions.
     * Example: A action named "show" will be mapped to "action_show()"
     * This is also a way to ensure some kind of whitelisting via namespacing.
     *
     * The use of submodules like NewsAdminController is also supported.
     * In this case the actionname is action_admin_show().
     * @todo drop this, makes no sense; use just action_show() on both controllers
     * @todo alter automatic template mapping to "NewsShow.tpl"; "NewsAdminShow.tpl"
     *
     * @param  string $action    the action
     * @param  string $submodule the submodule
     * @return string the mapped method name
     */
    public static function mapActionToActioname($action, $submodule = null)
    {
        // set default value for action, when not set by URL
        if (false === isset($action)) {
            $action = self::DEFAULT_ACTION;
        }

        // if a $submodule is set, use it as a PREFIX on $action
        if ($submodule !== null) {
            $action = $submodule . '_' . $action;
        }

        // all clansuite actions are prefixed with 'action_'
        return self::METHOD_PREFIX . '_' . $action;
    }
}
