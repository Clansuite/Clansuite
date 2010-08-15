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
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

if(false === interface_exists('Clansuite_Logger_Interface', false))
{
    include ROOT_CORE . 'logger.core.php';
}

/**
 * Clansuite Core File - Clansuite_Logger_File
 *
 * This class is a service wrapper for logging messages to a logfile.
 *
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Jens-André Koch (2005 - onwards)
 * @license     GPLv2 any later license
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Logger
 */
class Clansuite_Logger_File implements Clansuite_Logger_Interface
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
        file_put_contents($this->getErrorLogFilename(), $string, FILE_APPEND & LOCK_EX);
    }

    /**
     * writeErrorLog is a shortcut method for writing a string to the error_log
     *
     * @param $string The string to append to the errorlog.
     */
    public function writeErrorLog($string)
    {
        $this->writeLog('error', $string);
    }

    /**
     * readLog returns the content of a logfile
     *
     * @param $logfilename The name of the logfile to read.
     * @return $string Content of the logfile.
     */
    public static function readLog($logfilename = null)
    {
        # errorlog filename as set bei ini_set('error_log')
        #$logfilename = ini_get('error_log');

        if($logfilename == null)
        {
            # hardcoded errorlog filename
            $logfilename = 'logs/clansuite_errorlog.txt';
        }

        # determine size of file
        $logfilesize = filesize($logfilename);

        # size greater zero, means we have entries in that file
        if($logfilesize > 0)
        {
            # so open and read till eof
            $logfile = fopen($logfilename, 'r');
            $logfile_content = fread($logfile, $logfilesize);

            # @todo: split or explode logfile_content into an array
            # to select a certain number of entries to display

            # returns the complete logfile
            #return printf("<pre>%s</pre>", $logfile_content);
            return $logfile_content;
        }
    }

    /**
     * This method gives back the filename for logging
     * If rotation is active it will add a date, if seperation is active it will
     *
     * @return $filename string
     */
    public function getErrorLogFilename()
    {
        # default hardcoded logfilename
        $logfilename = 'error';

        # if rotation is active we add a date to the filename
        if($config['log']['rotation'] == true)
        {
            # construct name of the log file ( FILENAME_log_DATE.txt )
            $filename = ROOT_LOGS . $logfilename . '_log_' . date('m-d-y') . '.txt';
        }
        else
        {
            # construct name of the log file ( FILENAME_log.txt )
            $filename = ROOT_LOGS . $logfilename . '_log.txt';
        }

        return $filename;
    }

    /**
     * Returns a specific number of logfile entries (last ones first)
     *
     * @param int $entriesToFetch
     * @param string $logfilename
     * @return string HTML representation of logfile entries
     */
    public static function returnEntriesFromLogfile($entriesToFetch = 5, $logfilename = null)
    {
        # setup default logfilename
        if($logfilename == null)
        {
            $logfilename = ROOT . 'logs/clansuite_errorlog.txt.php';
        }

        # get logfile as array
        $logfile_array = file($logfilename);
        $logfile_cnt = count($logfile_array);

        if($logfile_cnt > 0)
        {
            # count array elements = total number of logfile entries
            $i = $logfile_cnt - 1;

            # subtract from total number of logfile entries the number to fetch
            $max_entries = max(0, $i - $entriesToFetch);

            # reverse for loop over the logfile_array
            $logEntries = '';
            for($i; $i > $max_entries; $i--)
            {
                # remove linebreaks
                $entry = str_replace(array('\r', '\n'), '', $logfile_array[$i]);

                $logEntries .= '<b>Entry ' . $i . '</b>';
                $logEntries .= '<br />' . htmlentities($entry) . '<br />';
            }

            # cleanup
            unset($logfilename, $logfile_array, $i, $max_entries, $entry);
        }
        else
        {
            $logEntries .= '<b>No Entries</b>';
        }

        return $logEntries;
    }
}
?>