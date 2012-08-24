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

namespace Koch\Logger\Adapter;

/**
 * Koch Framework - Log to /dev/null.
 *
 * This class is a service wrapper for logging messages to /dev/null.
 * It's a dummy logger - doing nothing.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Logger
 */
class Devnull implements LoggerInterface
{
    public function __construct(Koch\Config $config)
    {

    }

    /**
     * writeLog
     *
     * writes a string to /dev/null nirvana.
     *
     * @param $string The string to append to the logfile.
     */
    public function writeLog($string)
    {
        unset($string);
    }
}
