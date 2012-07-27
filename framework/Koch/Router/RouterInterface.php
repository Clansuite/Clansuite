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

namespace Koch\Router;

/**
 * Interface for Router(s)
 *
 * A router has to implement the following methods to resolve the Request to a Module and the Action/Command.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Router
 */
interface routerinterface
{
    public function addRoute($url_pattern, array $route_options = null);
    public function addRoutes(array $routes);
    public function getRoutes();
    public function delRoute($name);
    public function generateURL($url_pattern, array $params = null, $absolute = false);
    public function route();
}
