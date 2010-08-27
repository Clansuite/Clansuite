<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

class Clansuite_Logger_Factory
{

    /**
     * getLogger
     *
     * @param $adapter String (A Logger Name like "file", "db")
     * @return Logger Object
     */
    public static function getLogger($adapter)
    {
        $file = ROOT_CORE . 'logger' . DS . mb_strtolower($adapter) . '.logger.php';

        if(is_file($file) === true)
        {
            $class = 'logger_' . $adapter;
            if(false === class_exists($class, false))
            {
                include $file;
            }

            if(class_exists($class, false))
            {
                $logger = new $class();
                return $logger;
            }
            else
            {
                throw new Clansuite_Exception('Logger_Factory -> Class not found: ' . $class, 50);
            }
        }
        else
        {
            throw new Clansuite_Exception('Logger_Factory -> File not found: ' . $file, 51);
        }
    }
}

/**
 * Clansuite_Logger_Interface
 *
 * Purpose: All Loggers must implement the following functions.
 */
interface Clansuite_Logger_Interface
{
    function getInstance($injector);
    function writeLog($string);
}
?>