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
 * Koch Framework - Log to Firebug.
 *
 * This class is a service wrapper for logging messages to the firebug browser console
 * via the famous FirePHP Firefox extension.
 * In one sentence: Forget about echo, var_dump, print_r for debugging purposes.
 *
 * FirePHP is a Firebug Extension for AJAX Development written by Christoph Dorn.
 * With simple PHP method calls one is able to log to the Firebug Console.
 * The data does not interfere with the page content as it is sent via  X-FirePHP-Data response headers.
 * That makes FirePHP the ideal tool for AJAX development where clean JSON and XML responses are required.
 * Firebug is written by Joe Hewitt and Rob Campbell.
 *
 * @link http://getfirebug.com/
 * @link http://www.firephp.org/
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Logger
 */
class Firebug implements LoggerInterface
{
    private static $firephp = null;

    public function __construct()
    {
        include ROOT_LIBRARIES.'firephp/FirePHP.class.php';
        self::$firephp = FirePHP::getInstance(true);

        return self:$firephp;
    }

    /**
     * geFirePHPLoglevel
     * translates the system errorlevel to the loglevel known by firephp
     *
     * @param string $level (comming from $data['level'] of the $data array to log)
     */
    public function getFirePHPLoglevel($level)
    {
        switch (strtoupper($level)) {
            case 'LOG':
                return FirePHP::LOG;
            case 'INFO':
                return FirePHP::INFO;
            case 'WARNING':
                return FirePHP::WARN;
            case 'ERROR':
                return FirePHP::ERROR;
            case 'NOTICE':
                return FirePHP::NOTICE;
            case 'DEBUG':
                return FirePHP::DEBUG;
            case 'TABLE':
                return FirePHP::TABLE;
            case 'TRACE':
                return FirePHP::TRACE; // backtracing
            case 'DUMP':
                return FirePHP::DUMP; // variable dumps
            default:
                return FirePHP::ERROR;
        }
    }

    /**
     * This writes a log to the Firephp or Firebug console.
     *
     * It utilizes firephp's procedural API.
     * fb($var, 'Label', FirePHP::*)
     *
     * @param $data array date['message'], data['label'], data['level']
     */
    public function writeLog($data)
    {
        self::$firephp->fb($data['message'], $data['label'], $this->getFirePHPLoglevel($data['level']) );
    }
}
