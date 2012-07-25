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

namespace Koch\Logger;

class Factory
{
    /**
     * Its a Logger Factory Method, which includeds, instantiates and returns a logger object.
     *
     * @param  string      $adapter Name of logger: file, firebug (default), db.
     * @return Koch_Logger Object
     */
    public static function instantiate($adapter = 'firebug')
    {
        $file = ROOT_CORE . 'logger' . DIRECTORY_SEPARATOR . mb_strtolower($adapter) . '.logger.php';

        if (is_file($file) === true) {
            $class = 'Koch_Logger_' . $adapter;
            if (false === class_exists($class, false)) {
                include $file;
            }

            if (true === class_exists($class, false)) {
                $logger = new $class();

                return $logger;
            } else {
                throw new Koch_Exception('Logger_Factory -> Class not found: ' . $class, 50);
            }
        } else {
            throw new Koch_Exception('Logger_Factory -> File not found: ' . $file, 51);
        }
    }
}
