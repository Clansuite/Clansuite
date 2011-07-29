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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Gettext_MOFile
 *
 * Handling for Gettext "Machine Object" (.mo) Files.
 *
 * Based on php-msgfmt written by 
 * @author Matthias Bauer
 * @copyright 2007 Matthias Bauer
 * @license GNU/LGPL 2.1
 * @link http://wordpress-soc-2007.googlecode.com/svn/trunk/moeffju/php-msgfmt/
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Gettext
 */
class Clansuite_Gettext_MOFile
{
    /**
     * Writes a GNU gettext style machine object (mo-file).
     *
     * @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files
     */
    function write($hash, $out)
    {
        # sort by msgid
        ksort($hash, SORT_STRING);

        # our mo file data
        $mo = '';

        # header data
        $offsets = array ();
        $ids = '';
        $strings = '';

        foreach ($hash as $entry)
        {
            $id = $entry['msgid'];

            if (isset ($entry['msgid_plural']))
            {
                $id .= "\x00" . $entry['msgid_plural'];
            }

            # context is merged into id, separated by EOT (\x04)
            if (array_key_exists('msgctxt', $entry))
            {
                $id= $entry['msgctxt'] . "\x04" . $id;
            }

            # plural msgstrs are NUL-separated
            $str= implode("\x00", $entry['msgstr']);

            # keep track of offsets
            $offsets[]= array ( mb_strlen($ids), mb_strlen($id), mb_strlen($strings), mb_strlen($str));

            # plural msgids are not stored (?)
            $ids .= $id . "\x00";

            $strings .= $str . "\x00";
        }

        # keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start= 7 * 4 + count($hash) * 4 * 4;

        # values start right after the keys
        $value_start= $key_start +mb_strlen($ids);

        # first all key offsets, then all value offsets
        $key_offsets= array ();
        $value_offsets= array ();

        # calculate
        foreach ($offsets as $v)
        {
            list ($o1, $l1, $o2, $l2)= $v;
            $key_offsets[]= $l1;
            $key_offsets[]= $o1 + $key_start;
            $value_offsets[]= $l2;
            $value_offsets[]= $o2 + $value_start;
        }

        $offsets= array_merge($key_offsets, $value_offsets);

        # write header
        $mo .= pack('Iiiiiii', 0x950412de,          # magic number
                    0,                              # version
                    count($hash),                   # number of entries in the catalog
                    7 * 4,                          # key index offset
                    7 * 4 + count($hash) * 8,       # value index offset,
                    0,                              # hashtable size (unused, thus 0)
                    $key_start                      # hashtable offset
        );

        # offsets
        foreach ($offsets as $offset)
        {
            $mo .= pack('i', $offset);
        }

        # ids
        $mo .= $ids;

        # strings
        $mo .= $strings;

        file_put_contents($out, $mo);
    }
}
?>