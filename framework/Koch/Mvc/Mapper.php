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
    const MODULE_NAMESPACE = 'Clansuite\Application\Modules';

    /* @const string suffix for module controller files */
    const MODULE_CLASS_SUFFIX = 'Controller.php';

    /* @const string Method prefix for module actions */
    const ACTION_PREFIX = 'action';

    /* @const string Name of the Default Module */
    const DEFAULT_MODULE = 'news';

    /* @const string Name of the Default Action */
    const DEFAULT_ACTION = 'index';

    /**
     * Maps the controller and subcontroller (optional) to filename
     *
     * @param  string $module_path Path to Module
     * @param  string $controller  Name of Controller
     * @return string filename
     */
    public static function mapControllerToFilename($module_path, $controller = null)
    {
        // append module_path, e.g. "/clansuite/modules/news/" + "Controller/"
        $module_path .= 'Controller/';

        // Mapping Example:
        // "/clansuite/modules/news/" + "Controller/" + "Index" + "Controller.php"
        // "/clansuite/modules/news/" + "Controller/" + "Sub" + "Controller.php"
        return $module_path . ucfirst($controller) . self::MODULE_CLASS_SUFFIX;
    }

    /**
     * Maps Controller to Classname
     *
     * @param  string $module     Name of Module
     * @param  string $controller Name of Controller (optional)
     * @return string classname
     */
    public static function mapControllerToClassname($module, $controller = null)
    {
        $classname = '\\';

        if ($controller === null) {
            // the default controller of a module is named like the module
            // The module "News"  has a controller named "News"Controller.
            $controller = $module;
        }

        /**
         * (<Modulename|DefaultController>\Controller\<Controller>Controller)
         * e.g. "News\Controller\NewsController"
         */
        $classname .= $module . '\Controller\\' . $controller . 'Controller';

        // "Clansuite\Application\Modules + \News\Controller\NewsController"
        return self::MODULE_NAMESPACE . $classname;
    }

    /**
     * Maps the action to it's method name taking controller into account.
     *
     * The prefix 'action_' (pseudo-namespace) is used for all actions.
     * Example: A action named "show" will be mapped to "action_show()"
     * This is also a way to ensure some kind of whitelisting via namespacing.
     *
     * The convention is action_<action> !
     *
     *
     * @param  string $action the action
     * @return string the mapped method name
     */
    public static function mapActionToMethodname($action)
    {
        // set default value for action, when not set by URL
        if (false === isset($action)) {
            $action = self::DEFAULT_ACTION;
        }

        // all clansuite actions are prefixed with 'action_'
        // e.g. action_<login>
        return self::ACTION_PREFIX . '_' . $action;
    }
}
