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
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Filter\Filters;

use Koch\Filter\FilterInterface;
use Koch\MVC\HttpRequestInterface;
use Koch\MVC\HttpResponseInterface;
use Koch\User\User;

/**
 * Koch Framework - Filter for Instantiation of the User Object.
 *
 * Purpose: Sets up the user session and user object.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class GetUser implements FilterInterface
{
    private $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        unset($request, $response);

        // Create a user (Guest)
        $this->user->createUserSession();

        // Check for login cookie (Guest/Member)
        $this->user->checkLoginCookie();
        unset($this->user);
    }
}
