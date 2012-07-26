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
use Koch\Exception\Exception;

/**
 * Koch Framework - Filter performing Startup Checks.
 *
 * Purpose: Perform Various Startup Check before running a Koch Framework Module.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class startupchecks implements FilterInterface
{
    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        // ensure smarty "tpl_compile" folder exists
        if(false === is_dir(ROOT_CACHE . 'tpl_compile') and
          (false === @mkdir(ROOT_CACHE .'tpl_compile', 0755, true)))
        {
            throw new Exception('Smarty Template Directories not existant.', 9);
        }

        // ensure smarty "cache" folder exists
        if(false === is_dir(ROOT_CACHE . 'tpl_cache') and
          (false === @mkdir(ROOT_CACHE .'tpl_cache', 0755, true)))
        {
            throw new Exception('Smarty Template Directories not existant.', 9);
        }

        // ensure smarty folders are writable
        if(false === is_writable(ROOT_CACHE . 'tpl_compile') or
          (false === is_writable(ROOT_CACHE . 'tpl_cache')))
        {
            // if not, try to set writeable permission on the folders
            if((false === chmod(ROOT_CACHE . 'tpl_compile', 0755)) and
               (false === chmod(ROOT_CACHE . 'tpl_cache', 0755)))
            {
                throw new Exception('Smarty Template Directories not writable.', 10);
            }
        }
    }
}
