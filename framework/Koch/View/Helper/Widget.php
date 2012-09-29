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

namespace Koch\View\Helper;

use Koch\Mvc\Mapper;

/**
 * Koch Framework - Class for handling of Widgets.
 * 
 */
class Widget
{
    /**
     * loadModul
     *
     * @return boolean
     */
    public static function loadModul($module, $controller)
    {
        $module_path = Mapper::getModulePath($module);
        #echo $module_path . '<br>';
        $classname = Mapper::mapControllerToClassname($module, $controller);
        #echo $classname . '<br>';

        return class_exists($classname);
    }

}
