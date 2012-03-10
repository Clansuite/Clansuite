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

/**
 * Koch_Logger
 *
 * This class represents a compositum for all registered loggers.
 * Another name for this class would be MultiLog.
 * A new logger object is added with addLogger(), removed with removeLogger().
 * The composition is fired via method writeLog().
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Logger
 */
class Compositum implements Logger
{
    /**
     * @var array Array constains a object composition of all loggers
     */
    public $loggers = array();

    /**
     * Iterates over all registered loggers and writes the log entry.
     *
     * @param mixed|array|string $data array('message', 'label', 'priority') or message
     * @param string $label label
     * @param string $level priority level (LOG, INFO, WARNING, ERROR...)
     */
    public function writeLog($data_or_msg, $label = null, $level = null)
    {
        $data = array();

        # first parameter might be an array or an string
        if(is_array($data_or_msg))
        {
            $data = $data_or_msg;
            $data['message'] = $data['0'];
            $data['label'] = $data['1'];
            $data['level'] = $data['2'];
        }
        # first parameter is string
        else
        {
            $data['message'] = $data_or_msg;
            $data['label'] = $label;
            $data['level'] = $level;
        }

        foreach($this->loggers as $logger)
        {
            $logger->writeLog($data);
        }
    }

    /**
     * Registers new logger(s) as composite element(s)
     *
     * @param array $logger One or several loggers to add
     */
    public function addLogger($loggers)
    {
        # loggers might be an object, so it's typecasted to array, because of foreach
        $loggers = array($loggers);

        foreach($loggers as $logger)
        {
            if((in_array($logger, $this->loggers) == false) and ($logger instanceof Koch_Logger_Interface))
            {
                $this->loggers[] = $logger;
            }
        }
    }

    /**
     * Removes logger(s) from the compositum
     *
     * @param array $logger One or several loggers to remove
     */
    public function removeLogger($loggers)
    {
        foreach($loggers as $logger)
        {
            if((in_array($logger, $this->loggers) == true))
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