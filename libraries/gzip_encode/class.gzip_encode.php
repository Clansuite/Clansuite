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
 * Clansuite_Response_Encode
 * formerly known as gzip_encode - a class to gzip encode php output.
 *
 * @author      Sandy McArthur, Jr. <Leknor@Leknor.com>
 * @author      Jens-André Koch <vain@clansuite.com>
 * @copyright   Copyright 2001 (c) McArthur. Copyright 2009 (c) Koch.
 * @license     GNU LGPL 2.1 and above (http://www.gnu.org/copyleft/lesser.html)
 *
 * Usage:
 * 1. Include the class file (Clansuite_Response_Encode).
 * 2. Start Output buffering by calling
 *    Clansuite_Response_Encode::start_outputbuffering();
 * 3. At the very end of your script you have to end the outputbuffering by calling
 *    Clansuite_Response_Encode::end_outputbuffering();
 *
 * Example:
 *  ------------Start of file----------
 *  |<?php
 *  | include('class.gzip_encode.php');
 *  | Clansuite_Response_Encode::start_outputbuffering();
 *  |?>
 *  |<html>
 *  |... Content of Page ...
 *  |</html>
 *  |<?php
 *  | Clansuite_Response_Encode::end_outputbuffering();
 *  |?>
 *  -------------End of file-----------
 *
 * Resources:
 *  http://www.ietf.org/rfc/rfc2616.txt (Sections: 3.5, 14.3, 14.11)
 *  http://www.whatsmyip.org/http_compression/
 *
 * Requirments: PHP5 & PHP Extensions: zlib, crc
 *
 * Note by vain@clansuite.com:
 *  TYPO3 4.5 is now using "ob_gzhandler" for compression.
 *  That is suboptimal because using zlib.output_compression is preferred over ob_gzhandler().
 *
 * Change Log:
 *  0.7:    Nearly complete rewrite and clean-up. Commented.
 *          Changed methods to "private static".
 *          Compression info comment now passed via header.
 *          Removed second/third parameter.
 *          Removed unused class properties.
 *          Added start_outputbuffering() and end_outputbuffering methods.
 *          Renamed class from gzip_encode to Clansuite_Response_Encode.
 *          Relicensed under GNU/GPL v2 or (at your option) any later version.
 *  0.68:   Applied latest fixed from the Typo3 Team.
 *  0.67:   Added Vary header to aid in caching.
 *  0.66:   Big bug fix. It wouldn't compress when it should.
 *  0.65:   Fix for PHP-4.0.5 suddenly removing the connection_timeout() function.
 *  0.62:   Fixed a typo
 *  0.61:   Detect file types more like described in the magic number files, also
 *          added detection for gzip and pk zip files.
 *  0.6:    Detect common file types that shouldn't be compressed, mainly
 *          for images and swf (Shockwave Flash doesn't really accept gzip)
 *  0.53:   Made gzip_accepted() method so everyone can detect if a page
 *          will be gzip'ed with ease.
 *  0.52:   Detection and graceful handling of improper install/missing libs
 *  0.51:   Added FreeBSD load average detection.
 *  0.5:    Passing true as the first parameter will try to calculate the
 *          compression level from the server's load average. Passing true
 *          as the second parameter will turn on debugging.
 *  0.4:    No longer uses a temp file to compress the output. Should speed
 *          thing up a bit and reduce wear on your hard disk. Also test if
 *          the http headers have been sent.
 *  0.31:   Made a small change to the tempnam() line to hopefully be more
 *          portable.
 *  0.3:    Added code for the 'x-gzip'. This is untested, I don't know of
 *          any browser that uses it but the RFC said to look out for it.
 *  0.2:    Checks for 'gzip' in the Accept-Encoding header
 *  0.1:    First working version.
 */
class Clansuite_Response_Encode
{
    # Version of the Clansuite_Response_Encode class
    public static $version = 0.7;

    public static function start_outputbuffering($level = -1)
    {
        # both methods depend on the zlib extension
        if (extension_loaded('zlib'))
        {
            # Method 1: zlib.output_compression
            # @todo do not uncomment, else strange things will happen
            #ini_set('zlib.output_compression'       , true);
            #ini_set('zlib.output_compression_level' , $level);

            # Method 2: Fallback to ob_start('gz_handler') = output buffering with gzip handling
            # because: ob_start() [ref.outcontrol]: output handler 'ob_gzhandler' conflicts with 'zlib output compression'
            /*if((bool)ini_get('zlib.output_compression') === false)
            {
                ob_start("ob_gzhandler");
                define('OB_GZHANDLER', true);
            }*/

                ob_start("ob_gzhandler");
                #ob_implicit_flush(0);
                define('OB_START', true);
        }
        else
        {
            trigger_error('Function gzcompress() not found. PHP Extension Zlib needs to be installed for Clansuite_Response_Encode.', E_USER_WARNING);
            return;
        }

        if (function_exists('crc32') == false)
        {
            trigger_error('Function crc32() not found. Needed for Clansuite_Response_Encode', E_USER_WARNING);
            return;
        }
    }

    /**
     * Convenience/Proxy method for gzip_encode
     */
    public static function end_outputbuffering($level)
    {
        Clansuite_Response_Encode::gzip_encode($level);
    }

    /**
     * gzip_encode
     * Purpose: gzip encodes the current output buffer, if the browser supports it.
     *
     * This method takes care of the output compression.
     * Because you cannot use both ob_gzhandler() and zlib.output_compression.
     * Therefore zlib.output_compression is preferred over ob_gzhandler().
     * So two methods are used:
     * Method 1: zlib.output_compression
     * Method 2: Fallback to ob_start('gz_handler') = output buffering with gzip handling
     * The way this method works is determined by start_encoding!
     * @see start_encoding();
     *
     * You can specify one of the following for the first argument:
     *  0:      No compression
     *  1:      Min compression
     *  1-9:    Range of compression levels
     *  9:      Max compression
     *  true:   Determin the compression level from the system load.
     *          The higher the load the less the compression.
     *
     * @param integer level
     * @param boolean debug
     * @param integer outputCompressedSizes
     */
    public static function gzip_encode($level = 7)
    {
        if (headers_sent())
        {
            return;
        }

        if (connection_status() !== 0)
        {
            return;
        }

        $encoding = Clansuite_Response_Encode::gzip_accepted();

        if ($encoding == false)
        {
             return;
        }

        if ($level === true)
        {
            $level = Clansuite_Response_Encode::get_compression_level();
        }

        $content = ob_get_contents();

        # if no content is given, exit
        if ($content === false)
        {
            return;
        }

        # determine the content siz
        $original_content_size = strlen($content);

        # if size of content is small, do not waste resources in compressing very little data, exit
        if ($original_content_size < 2048)
        {
            return;
        }

        /**
         * The Content Compression
         */

        # gzip header
        $gzdata = "\x1f\x8b\x08\x00\x00\x00\x00\x00";

        # compress
        $gzdata .= gzcompress($content, $level);

        # determine size of compressed content
        $compressed_content_size = strlen($gzdata);

        # fix crc bug
        $gzdata = substr($gzdata, 0, $compressed_content_size - 4);

        # add pack infos
        $gzdata .= pack("V", crc32($content)) . pack("V", $original_content_size);

        # delete output-buffer and deactivate buffering
        ob_end_clean();

        # send Headers
        header('Content-Encoding: ' . $encoding);
        header('Vary: Accept-Encoding');
        header('Content-Length: ' . strlen($gzdata));
        header('X-Content-Encoded-By: Clansuite_Response_Encode v'.self::$version);

        /**
         * Note by Jens-André Koch:
         *
         * The Content Compression Info Comment was originally added by Kasper Skaarhoj for Typo3.
         * This had the problem of wasting resources by using gzcompress two times.
         * One time to determine the compressed_content_size and a second time for the compression of the content (gzdata).
         * This gets rid of the double gzcompression usage. The compression info message is now passed via header to the client.
         */

        # calculate compression ratio
        $compression_ratio = round((100/$original_content_size) * $compressed_content_size);

        # construct Content Compression Info Comment
        $msg = 'Compression Level '.$level.'. Ratio '.$compression_ratio.'%. Original size was '.$original_content_size.' bytes. New size is '.$compressed_content_size.' bytes.';

        # set compression-info header
        header('X-Content-Compression-Info: ' . $msg);

        # FLUSH THE COMPRESSED CONTENT
        echo $gzdata;
    }

    /**
     * gzip_accepted()
     * Purpose: test headers for Accept-Encoding: gzip/x-gzip
     *
     * Usage to test if output will be zipped:
     * if (Clansuite_Response_Encode::gzip_accepted()) { echo "Page will be gziped"; }
     *
     * @return mixed (string|boolean) $encoding Returns 'gzip' or 'x-gzip' if Accept-Encoding Header is found. False otherwise.
     */
    public static function gzip_accepted()
    {
        # init vars
        $encoding = null;
        $http_accept_encoding = $_SERVER["HTTP_ACCEPT_ENCODING"];

        # check Accept-Encoding for x-gzip
        if (strpos($http_accept_encoding, 'x-gzip') !== false)
        {
            $encoding = 'x-gzip';
        }

        # prefer x-gzip over gzip
        if($encoding !== 'x-gzip')
        {

            # check Accept-Encoding for gzip
            if (strpos($http_accept_encoding, 'gzip') !== false)
            {
                $encoding = 'gzip';
            }

            # Perform a "qvalue" check. The Accept-Encoding "gzip;q=0" means that gzip is NOT accepted.
            # preg_matches only, if first condition is true.
            if( (strpos($http_accept_encoding, 'gzip;q=') == true)
                and
                (preg_match('/(^|,\s*)(x-)?gzip(;q=(\d(\.\d+)?))?(,|$)/i', $http_accept_encoding, $match) and ($match[4] === '' or $match[4] > 0))
              )
            {
    		    $encoding = 'gzip';
    		}
        }

        /**
         * Determine file type by checking the first bytes of the content buffer.
         */
        $magic = substr(ob_get_contents(),0,4);
        if (substr($magic,0,2) === '^_')
        {
            # gzip data
            $encoding = false;
        }
        else if (substr($magic,0,3) === 'GIF')
        {
            # gif images
            $encoding = false;
        }
        else if (substr($magic,0,2) === "\xFF\xD8")
        {
            # jpeg images
            $encoding = false;
        }
        else if (substr($magic,0,4) === "\x89PNG")
        {
            # png images
            $encoding = false;
        }
        else if (substr($magic,0,3) === 'FWS')
        {
            # Don't gzip Shockwave Flash files. Flash on windows incorrectly claims it accepts gzip'd content.
            $encoding = false;
        }
        else if (substr($magic,0,2) === 'PK')
        {
            # pk zip file
            $encoding = false;
        }

        return $encoding;
    }

    /*
     * get_compression_level() - The level of compression we should use.
     *
     * Usage to determine the compression level, before compressing output:
     * $compression_level = Clansuite_Response_Encode::get_complevel();
     *
     * @return integer $level Returns an int between 0 and 9.
     */
    public static function get_compression_level()
    {
        $uname = posix_uname();

        switch ($uname['sysname'])
        {
            case 'Linux':
                $cl = (1 - Clansuite_Response_Encode::linux_loadavg()) * 10;
                $level = (int)max(min(9, $cl), 0);
            break;
            case 'FreeBSD':
                $cl = (1 - Clansuite_Response_Encode::freebsd_loadavg()) * 10;
                $level = (int)max(min(9, $cl), 0);
            break;
            default:
                $level = 3;
            break;
        }
        return $level;
    }

    /*
     * linux_loadavg() - Gets the max() system load average from /proc/loadavg
     *
     * The max() Load Average will be returned
     */
    private static function linux_loadavg()
    {
        $buffer = '0 0 0';
		$f = @fopen('/proc/loadavg', 'rb');
		if ($f)
		{
			if (!feof($f))
			{
				$buffer = fgets($f, 1024);
			}
			fclose($f);
		}
		$load = explode(' ', $buffer);
		return max((float)$load[0], (float)$load[1], (float)$load[2]);
    }

    /*
     * freebsd_loadavg() - Gets the max() system load average from uname(1)
     *
     * The max() Load Average will be returned
     *
     * I've been told the code below will work on solaris too, anyone wanna
     * test it?
     */
    private static function freebsd_loadavg()
    {
        $buffer= `uptime`;
        ereg("averag(es|e): ([0-9][.][0-9][0-9]), ([0-9][.][0-9][0-9]), ([0-9][.][0-9][0-9]*)", $buffer, $load);

        return max((float)$load[2], (float)$load[3], (float)$load[4]);
    }
}
?>