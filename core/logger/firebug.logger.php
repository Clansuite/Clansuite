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
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Core File - Clansuite_Logger_Firebug
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
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Jens-André Koch (2005 - onwards)
 * @license     GPLv2 any later license
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Logger
 */
class Clansuite_Logger_Firebug extends Clansuite_Logger implements Clansuite_Logger_Interface
{
    private static $instance = null;

    private $config;

    private function __construct(Clansuite_Config $config)
    {
        # load firebug only if enabled
        if ( $config['logs']['firephp_enabled'] == true)
        {
            include ROOT_LIBRARIES.'firephp/FirePHP.class.php';
        }
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
            self::$instance = FirePHP::getInstance(true);
        }
        return self::$instance;
    }

    /**
     * geFirePHPLoglevel
     * translates the system errorlevel to the loglevel known by firephp
     *
     * @param string $level (comming from $data['level'] of the $data array to log)
     */
    public function getFirePHPLoglevel($level)
    {
        switch ($level)
        {
            case LOG:
                    return FirePHP::LOG;
            case INFO:
                    return FirePHP::INFO;
            case WARNING:
                    return FirePHP::WARN;
            case ERROR:
                    return FirePHP::ERROR;
            case NOTICE:
                    return FirePHP::NOTICE;
            case DEBUG:
                    return FirePHP::DEBUG;
            case TABLE:
                    return FirePHP::TABLE;
            # backtracing
            case TRACE:
                    return FirePHP::TRACE;
            # variable dumps
            case DUMP:
                    return FirePHP::DUMP;
            default:
                    return FirePHP::ERROR;
        }
    }

    /**
     * writeLog
     * writes a log to the firephp/firebug console
     *
     * this method utilizes the firephp's procedural api
     * fb($var, 'Label', FirePHP::*)
     *
     * @param $data
     */
    public function writeLog($data)
    {
        $firephp->fb($data['message'], $data['label'], $this->getFirePHPLoglevel($data['level']) );
    }
}
?>