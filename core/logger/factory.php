<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @version    SVN: $Id$
    */

namespace Koch\Logger;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

class Factory
{
    /**
     * Its a Logger Factory Method, which includeds, instantiates and returns a logger object.
     *
     * @param string $adapter Name of logger: file, firebug (default), db.
     * @return Koch_Logger Object
     */
    public static function instantiate($adapter = 'firebug')
    {
        $file = ROOT_CORE . 'logger' . DS . mb_strtolower($adapter) . '.logger.php';

        if(is_file($file) === true)
        {
            $class = 'Koch_Logger_' . $adapter;
            if(false === class_exists($class, false))
            {
                include $file;
            }

            if(true === class_exists($class, false))
            {
                $logger = new $class();
                return $logger;
            }
            else
            {
                throw new Koch_Exception('Logger_Factory -> Class not found: ' . $class, 50);
            }
        }
        else
        {
            throw new Koch_Exception('Logger_Factory -> File not found: ' . $file, 51);
        }
    }
}
?>