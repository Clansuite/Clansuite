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

namespace Koch\Filter;

use Koch\MVC\HttpRequestInterface;
use Koch\MVC\HttpResponseInterface;

/**
 * Koch Framework - Filter for Permissions / RBACL Checks.
 *
 * Purpose: Perform an Permissions / RBACL Check
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class permissions implements FilterInterface
{
    private $user    = null;
    private $rbacl   = null;

    public function __construct(Koch\User\User $user)
    {
        $this->user = $user;
        // @todo RBACL class
        $rbacl = Koch_RBACL::getInstance();
    }

    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        if (false === $rbacl->isAuthorized($actionname, $this->user->getUserId())) {
            // @todo errorpage, no permission to perform this action. access denied.
            $response->redirect();
        }
    }
}
