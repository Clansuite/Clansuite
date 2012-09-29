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

use Koch\Logger\LoggerInterface;

/**
 * Koch Framework - Log to File.
 *
 * This class is a service wrapper for logging messages to a logfile.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Logger
 */
class File implements LoggerInterface
{
    private $config;

    public function __construct(\Koch\Config\Config $config)
    {
        $this->config = $config;
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
        // append string to file
        file_put_contents($this->getErrorLogFilename(), $string, FILE_APPEND & LOCK_EX);
    }

    /**
     * readLog returns the content of a logfile
     *
     * @param $logfilename The name of the logfile to read.
     * @return $string Content of the logfile.
     */
    public static function readLog($logfilename = null)
    {
        // errorlog filename as set bei ini_set('error_log')
        #$logfilename = ini_get('error_log');

        if ($logfilename == null) {
            // hardcoded errorlog filename
            $logfilename = 'logs/clansuite_errorlog.txt';
        }

        // determine size of file
        $logfilesize = filesize($logfilename);

        // size greater zero, means we have entries in that file
        if ($logfilesize > 0) {
            // so open and read till eof
            $logfile = fopen($logfilename, 'r');
            $logfile_content = fread($logfile, $logfilesize);

            // @todo: split or explode logfile_content into an array
            // to select a certain number of entries to display

            // returns the complete logfile
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
        $filename = ROOT_LOGS . 'error';

        // if rotation is active we add a date to the filename
        if ($this->config['log']['rotation'] == true) {
            // construct name of the log file ( FILENAME_log_DATE.txt )
            $filename =  $filename . '_log_' . date('m-d-y') . '.txt';
        } else {
            // construct name of the log file ( FILENAME_log.txt )
            $filename = $filename . '_log.txt';
        }

        return $filename;
    }

    /**
     * Returns a specific number of logfile entries (last ones first)
     *
     * @param  int    $entriesToFetch
     * @param  string $logfilename
     * @return string HTML representation of logfile entries
     */
    public static function returnEntriesFromLogfile($entriesToFetch = 5, $logfilename = null)
    {
        // setup default logfilename
        if ($logfilename == null) {
            $logfilename = ROOT_LOGS . 'clansuite_errorlog.txt.php';
        }

         $logEntries = '';

        if (true === is_file($logfilename)) {
            // get logfile as array
            $logfile_array = file($logfilename);
            $logfile_cnt = count($logfile_array);

            if ($logfile_cnt > 0) {
                // count array elements = total number of logfile entries
                $i = $logfile_cnt - 1;

                // subtract from total number of logfile entries the number to fetch
                $max_entries = max(0, $i - $entriesToFetch);

                // reverse for loop over the logfile_array
                for ($i; $i > $max_entries; $i--) {
                    // remove linebreaks
                    $entry = str_replace(array('\r', '\n'), '', $logfile_array[$i]);

                    $logEntries .= '<b>Entry ' . $i . '</b>';
                    $logEntries .= '<br />' . htmlentities($entry) . '<br />';
                }

                // cleanup
                unset($logfilename, $logfile_array, $i, $max_entries, $entry);
            } else {
                $logEntries .= '<b>No Entries</b>';
            }
        } else {
            $logEntries .= '<b>No Logfile found. No entries yet.</b>';
        }

        return $logEntries;
    }
}
