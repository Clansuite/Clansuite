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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core File - Clansuite_Logger_Devnull
 *
 * This class is a service wrapper for logging messages to /dev/null.
 * It's a dummy logger - doing nothing.
 *
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Jens-André Koch (2005 - onwards)
 * @license     GPLv2 any later license
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Logger
 */
class Clansuite_Logger_Devnull implements Clansuite_Logger_Interface
{
    private static $instance = null;

    private $config;

    public function __construct(Clansuite_Config $config)
    {
        $this->config = $config;
    }

    /**
     * returns an instance / singleton
     *
     * @return an instance of the logger
     */
    public static function getInstance()
    {
        if (self::$instance == 0)
        {
            self::$instance = new Clansuite_Logger_Devnull();
        }
        return self::$instance;
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
?>