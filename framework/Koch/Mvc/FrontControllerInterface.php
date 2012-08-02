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

use Koch\Filter\FilterInterface;

/**
 * Interface for FrontController
 *
 * The Frontcontroller has to implement the following methods.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  FrontController
 */
interface FrontControllerInterface
{
    public function __construct(HttpRequestInterface $request, HttpResponseInterface $response);
    public function processRequest();
    public function addPreFilter(FilterInterface $filter);
    public function addPostFilter(FilterInterface $filter);
}
