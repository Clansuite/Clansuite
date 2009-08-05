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

class Clansuite_Logger_File implements Clansuite_Logger_Interface
{
    private static $instance = 0;
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
            self::$instance = new Clansuite_Logger_File();
        }
        return self::$instance;
    }

    /**
     * writeLog
     *
     * writes a string to the logfile.
     *
     * @param $logfilename The name of the Logfile to append to.
     * @param $string The string to append to the logfile.
     */
    public function writeLog($string)
    {
        # append string to file
        file_put_contents( $this->getErrorLogFilename(), $string, FILE_APPEND & LOCK_EX );
    }

    /**
     * writeErrorLog is a shortcut method
     * for writing a string to the error_log
     *
     * @param $string The string to append to the errorlog.
     */
    public function writeErrorLog($string)
    {
        $this->writeLog('error', $string);
    }

    /**
     * readLog
     *
     */
    public function readLog()
    {

    }

    /**
     * This method gives back the filename for logging
     * If rotation is active it will add a date,
     * if seperation is active it will
     *
     * @return $filename string
     */
    public function getErrorLogFilename()
    {
        $logfilename = 'error';

        if($config['log']['rotation'] == true)
        {
            # construct name of the log file ( FILENAME_log_DATE.txt )
            $filename =  ROOT_LOGS . $logfilename.'_log_' . date('m-d-y') . '.txt';
        }

        return $filename;
    }
}
?>