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
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite MB_STRING Wrapper Methods
 *
 * Clansuite relies on mb_string functions.
 * If the mbstring extension is not loaded, the mb_string functions are not available.
 * Here we define some mbstring wrapper functions, which use custom utf8 methods internally
 * and rebuild the mbstring behaviour. This means that calls to mbstring functions throughout
 * the sourcecode are being replaced by our own UTF8 functions.
 * 
 * The UTF8 functions are stored in the class Clansuite_UTF8.
 *
 * The following functions are declared for global usage:
 *
 *  mb_convert_encoding
 *  mb_detect_encoding
 *  mb_stripos
 *  mb_stristr
 *  mb_strlen
 *  mb_strpos
 *  mb_strrchr
 *  mb_strrpos
 *  mb_strstr
 *  mb_strtolower
 *  mb_strtoupper
 *  mb_substr
 *  mb_substr_count
 */

/**
 * mb_convert_encoding
 */
function mb_convert_encoding($str, $to_encoding, $from_encoding = null)
{
    if(is_null($from_encoding))
    {
        return utf8_convert_encoding($str, $to_encoding);
    }
    else
    {
        return utf8_convert_encoding($str, $to_encoding, $from_encoding);
    }
}

/**
 * mb_detect_encoding
 */
function mb_detect_encoding($str)
{
    return utf8_detect_encoding($str);
}

/**
 * mb_stripos
 */
function mb_stripos($haystack, $needle, $offset=null)
{
    if(is_null($offset))
    {
        return stripos($haystack, $needle);
    }
    else
    {
        return stripos($haystack, $needle, $offset);
    }
}

/**
 * mb_stristr
 */
function mb_stristr($haystack, $needle)
{
    return stristr($haystack, $needle);
}

/**
 * mb_strlen
 */
function mb_strlen($str)
{
    return utf8_strlen($str);
}

/**
 * mb_strpos
 */
function mb_strpos($haystack, $needle, $offset=null)
{
    if(is_null($offset))
    {
        return utf8_strpos($haystack, $needle);
    }
    else
    {
        return utf8_strpos($haystack, $needle, $offset);
    }
}

/**
 * mb_strrchr
 */
function mb_strrchr($haystack, $needle)
{
    return utf8_strrchr($haystack, $needle);
}

/**
 * mb_strrpos
 */
function mb_strrpos($haystack, $needle)
{
    return utf8_strrpos($haystack, $needle);
}

/**
 * mb_strstr
 */
function mb_strstr($haystack, $needle)
{
    return utf8_strstr($haystack, $needle);
}

/**
 * mb_strtolower
 */
function mb_strtolower($str)
{
    return utf8_strtolower($str);
}

/**
 * mb_strtoupper
 */
function mb_strtoupper($str)
{
    return utf8_strtoupper($str);
}

/**
 * mb_substr
 */
function mb_substr($str, $start, $length=null)
{
    if(is_null($length))
    {
        return utf8_substr($str, $start);
    }
    else
    {
        return utf8_substr($str, $start, $length);
    }
}

/**
 * mb_substr_count
 */
function mb_substr_count($haystack, $needle, $offset=null)
{
    if(is_null($offset))
    {
        return substr_count($haystack, $needle);
    }
    else
    {
        return substr_count($haystack, $needle, $offset);
    }
}
?>