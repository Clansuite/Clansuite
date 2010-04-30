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
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Converts a UTF8-string into HTML entities
 *
 * When using UTF-8 as a charset you want to convert multi-byte characters.
 * This function takes multi-byte characters up to level 4 into account.
 * Htmlentities will only convert 1-byte and 2-byte characters.
 * Use this function if you want to convert 3-byte and 4-byte characters also.
 *
 * @author silverbeat gmx  at
 * @link http://www.php.net/manual/de/function.htmlentities.php#96648
 *
 * @param $utf8 string The UTF8-string to convert
 * @param $encodeTags booloean TRUE will convert "<" to "&lt;", Default = false
 * @return returns the converted HTML-string
 */
function UTF8_to_HTML($utf8, $encodeTags = false)
{
    $result = '';
    $utf8_strlen = strlen($utf8);

    for ($i = 0; $i < $utf8_strlen; $i++)
    {
        $char = $utf8[$i];
        $ascii = ord($char);

        if ($ascii < 128)
        {
            # one-byte character
            $result .= ($encodeTags) ? htmlentities($char) : $char;
        }
        elseif ($ascii < 192)
        {
            # non-utf8 character or not a start byte
        }
        elseif ($ascii < 224)
        {
            # two-byte character
            $result .= htmlentities(substr($utf8, $i, 2), ENT_QUOTES, 'UTF-8');
            $i++;
        }
        elseif ($ascii < 240)
        {
            # three-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $unicode = (15 & $ascii)  * 4096 +
                       (63 & $ascii1) * 64 +
                       (63 & $ascii2);
            $result .= '&#'.$unicode;
            $i += 2;
        }
        elseif ($ascii < 248)
        {
            # four-byte character
            $ascii1 = ord($utf8[$i+1]);
            $ascii2 = ord($utf8[$i+2]);
            $ascii3 = ord($utf8[$i+3]);
            $unicode = (15 & $ascii)  * 262144 +
                       (63 & $ascii1) * 4096 +
                       (63 & $ascii2) * 64 +
                       (63 & $ascii3);
            $result .= '&#'.$unicode;
            $i += 3;
        }
    }

    return $result;
}
?>