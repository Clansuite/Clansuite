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

namespace Koch\cache;

/**
 * Interface for all Cache Adapters to implement.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Cache
 */
interface cacheinterface
{
    // Checks cache for a stored variable
    public function contains($key);

    // Fetch a stored variable from the cache
    public function fetch($key);

    // Cache a variable in the data store
    public function store($key, $data, $cache_lifetime = 0);

    // Removes a stored variable from the cache
    public function delete($key);

    // Clears the cache
    public function clear();

    // Fetches cache adapter statistics
    public function stats();
}
