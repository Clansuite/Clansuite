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

namespace Koch\Cache;

/**
 * Koch Framework - Abstract Base Class for Cache Adapters.
 */
abstract class AbstractCache
{
    /**
     * Prefix for the cache key.
     *
     * @var mixed Defaults to 'cs'.
     */
    protected $prefix = 'cs';

    /**
     * Set Prefix for the cache key.
     *
     * @param  string                   $prefix The prefix for all cache keys.
     * @throws InvalidArgumentException if prefix is empty
     */
    public function setPrefix($prefix)
    {
        if (empty($prefix)) {
            throw new InvalidArgumentException('Prefix must not be empty.');
        }

        $this->prefix = $prefix;
    }

    /**
     * Get Prefix for the cache key.
     *
     * @return string The cache prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}
