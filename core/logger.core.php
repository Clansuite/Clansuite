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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Interface for the Logger Object
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Logger
 */
interface Clansuite_Logger_Interface
{
    # each logger has to provide the method writeLog()
    public function writeLog($data);
}

/**
 * Clansuite_Logger $logger
 *
 * Purpose:  Clansuite Core Class for Logger Handling
 *
 * This class represents a compositum for all loggers.
 * A new logger object is added with addLogger(), removed with removeLogger().
 * The composition is fired via method writeLog().
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  HttpResponse
 */
class Clansuite_Logger implements Clansuite_Logger_Interface
{
    /**
     * Composition of all loggers
     *
     * @var array
     */
    public $loggers = array();

    /**
     * Iterates over all registered loggers and writes the logs
     */
    public function writeLog($data)
    {
        foreach ($this->loggers as $logger)
        {
            $logger->writeLog($data);
        }
    }

    /**
     * Registers new logger(s) as composite element(s)
     *
     * @param $logger array One or several loggers to add
     */
    public function addLogger($loggers)
    {
        foreach($loggers as $logger)
        {
            if ( (in_array($logger, $this->loggers) == false) and ($logger instanceof Clansuite_Logger_Interface) )
            {
                $this->loggers[] = $logger;
            }
        }
    }

    /**
     * Removes logger(s) from the compositum
     *
     * @param $logger array One or several loggers to remove
     */
    public function removeLogger($loggers)
    {
        foreach($loggers as $logger)
        {
            if ( (in_array($logger, $this->loggers) == true) and ($logger instanceof Clansuite_Logger_Interface) )
            {
                unset($this->loggers[$logger]);
                return true;
            }
            else
            {
                return false;
            }
        }
    }
}
?>