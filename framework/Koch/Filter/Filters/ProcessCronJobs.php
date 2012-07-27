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

namespace Koch\Filter;

use Koch\Filter\FilterInterface;
use Koch\Mvc\HttpRequestInterface;
use Koch\Mvc\HttpResponseInterface;

/**
 * Koch Framework - Filter for triggering the processing of cronjobs.
 *
 * Purpose: processes regular jobs (cron-daemon like).
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class processcronjobs implements FilterInterface
{
    private $config     = null;
    private $cronjobs    = null;

    public function __construct(Koch\Config $config, Koch\Cronjobs $cronjobs)
    {
        $this->config   = $config;
        $this->cronjobs = $cronjobs;
    }

    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        // take the initiative, if cronjob processing is enabled in configuration
        if ($this->config['cronjobs']['enabled'] == 1) {
            $this->cronjobs->execute();
        }
    }
}
