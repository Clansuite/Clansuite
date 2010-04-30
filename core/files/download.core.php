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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Download
 *
 * This is the Clansuite Core Class for the handling of downloads.
 * Sending of a file to the user may be limited in speed.
 * The class supports the HTTP_RANGE Attribute for parallel and resumed downloads.
 * The class depends on the fileinfo extension (default since php5.3).
 *
 * @link http://www.php.net/manual/en/book.fileinfo.php PHP Manual for the FileInfo Extension
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Download
 */
class Clansuite_Download
{
    /**
     * Constructor and convenience/proxy method for sending a file as a download to the browser
     *
     * @param string $file The filepath as string
     * @param int $rate The speedlimit in KB/s
     */
    public function __construct($file, $rate)
    {
        self::send($file, $rate);
    }

    /**
     * Sends a file as a download to the browser
     *
     * Uses php fileinfo extension to determine the mimetype etc.
     *
     *
     * @param string $filePath The filepath as string
     * @param int $rate The speedlimit in KB/s
     */
    private static function sendRated($filePath, $rate = 0)
    {
        # Check if file exists
        if (is_file($filePath) == false)
        {
            throw new Clansuite_Exception('File not found.');
        }

        # get more information about the file
        $filename = basename($filePath);
        $size = filesize($filePath);
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, realpath($filePath));
        finfo_close($finfo);

        # Create file handle
        $fp = fopen($filePath, 'rb');

        $seekStart = 0;
        $seekEnd = $size;

        /**
         * check if only a specific part of the file should be sent
         * multipart-download and resume-download
         */
        if(isset($_SERVER['HTTP_RANGE']))
        {
            # calculate the range to use
            $range = explode('-', substr($_SERVER['HTTP_RANGE'], 6));

            $seekStart = intval($range[0]);

            if ($range[1] > 0)
            {
                $seekEnd = intval($range[1]);
            }

            # Seek to the start
            fseek($fp, $seekStart);

            # Set headers including the range info
            header('HTTP/1.1 206 Partial Content');
            header(sprintf('Content-Range: bytes %d-%d/%d', $seekStart, $seekEnd, $size));
        }
        else
        {
            # Set headers for full file
            header('HTTP/1.1 200 OK');
        }

        # Output some headers
        header('Cache-Control: private');
        header('Content-Type: ' . $mimetype);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Description: File Transfer');
        header('Content-Length: ' . ($seekEnd - $seekStart));
        header('Accept-Ranges: bytes');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');

        $block = 1024;

        # limit download speed
        if($rate > 0)
        {
            $block *= $rate;
        }

        # disable timeout before download starts
        set_time_limit(0);

        # Send file until end is reached
        while(feof($fp) == false)
        {
            $timeStart = microtime(true);
            echo fread($fp, $block);
            flush();
            $wait = (microtime(true) - $timeStart) * 1000000;

            # if speedlimit is defined, make sure to only send specified bytes per second
            if($rate > 0)
            {
                usleep(1000000 - $wait);
            }
        }

        # Close handle
        fclose($fp);
    }

    /**
     * Send a file as a download to the browser
     *
     * @param string $filePath
     * @param int $rate speedlimit in KB/s
     */
    public static function send($filePath, $rate = 3)
    {
        try
        {
            self::sendRated($filePath, $rate);
        }
        catch (Clansuite_Exception $e)
        {
            header('HTTP/1.1 404 File Not Found');
            die('Sorry, an error occured.');

        }
    }

    /**
     * Send a file as a download to the browser
     *
     * @param string $filePath
     * @param int $rate speedlimit in KB/s
     */
    public function sendFile($filePath, $rate = 3)
    {
        try
        {
            self::sendRated($filePath, $rate);
        }
        catch (Clansuite_Exception $e)
        {
            header('HTTP/1.1 404 File Not Found');
            die('Sorry, an error occured.');
        }
    }
}
?>