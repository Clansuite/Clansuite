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

namespace Koch\View;

/**
 * Koch Framework - Class is a Renderer Factory.
 *
 * The static method getRenderer() returns the included and instantiated
 * Rendering Engine Object - which is the View in MVC!
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Renderer
 */
class Factory
{
    /**
     * getRenderer
     *
     * @param $adapter String (A Renderer Name like "smarty", "phptal", "native")
     * @return Renderer Object
     */
    public static function getRenderer($adapter = 'smarty', $injector)
    {
        $file = KOCH_FRAMEWORK . 'View/Renderer/' . ucfirst($adapter) . '.php';

        if (is_file($file) === true) {
            $class = 'Koch\View\Renderer\\' . $adapter;

            if (false === class_exists($class, false)) {
                include $file;
            }

            if (true === class_exists($class, false)) {
                // instantiate and return the renderer and pass Config and Response objects to it
                $view = new $class($injector->instantiate('Koch\Config\Config'));

                return $view;
            } else {
                throw new \Exception('Renderer_Factory -> Class not found: ' . $class, 61);
            }
        } else {
            throw new \Exception('Renderer_Factory -> File not found: ' . $file, 61);
        }
    }
}
