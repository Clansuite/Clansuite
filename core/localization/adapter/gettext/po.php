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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Localization\Gettext;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch_Gettext_POFile
 *
 * Handling for Gettext (.po) Files.
 *
 * Based on php-msgfmt written by
 * @author Matthias Bauer
 * @copyright 2007 Matthias Bauer
 * @license GNU/LGPL 2.1
 * @link http://wordpress-soc-2007.googlecode.com/svn/trunk/moeffju/php-msgfmt/
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Gettext
 */
class POFile
{
    /**
     * Reads a Gettext .po file
     *
     * @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files
     *
     * @param string $file Path to PO file.
     * @return mixed|boolean|array
     */
    public static function read($file)
    {
        # read .po file
        $file_content = file_get_contents($file);

        # normalize newlines
        $file_content = str_replace(array("\r\n", "\r"), array("\n", "\n"), $file_content);

        # results array
        $hash = array();

        # temporary array
        $temp = array();

        # state
        $state = null;
        $fuzzy = false;

        $fc_array = explode("\n", $file_content);

        # iterate over lines
        foreach($fc_array as $line)
        {
            $line = trim($line);

            if($line === '')
            {
                continue;
            }

            /**
             * PHP Bug?
             * When the silencing operator is removed an
             * E_NOTICE Undefined offset: 1 will be thrown.
             * It's utter bullshit...
             *
             * Note: leave the silence op!
             */
            @list($key, $data) = explode(' ', $line, 2);

            switch($key)
            {
                case '#,': # flag...

                    $fuzzy = in_array('fuzzy', preg_split('/,\s*/', $data));

                case '#':  # translator-comments

                case '#.': # extracted-comments

                case '#:': # reference...

                case '#|': # msgid previous-untranslated-string
                    # start a new entry
                    if(count($temp) && array_key_exists('msgid', $temp) && array_key_exists('msgstr', $temp))
                    {
                        if(false === $fuzzy)
                        {
                            $hash[] = $temp;
                        }

                        $temp = array();
                        $state = null;
                        $fuzzy = false;
                    }
                    break;

                case 'msgctxt': # context

                case 'msgid': # untranslated-string

                case 'msgid_plural': # untranslated-string-plural

                    $state = $key;
                    $temp[$state] = $data;
                    break;

                case 'msgstr': # translated-string

                    $state = 'msgstr';
                    $temp[$state][] = $data;
                    break;

                default :

                    if(strpos($key, 'msgstr[') !== false)
                    {
                        # translated-string-case-n
                        $state = 'msgstr';
                        $temp[$state][] = $data;
                    }
                    else
                    {
                        # continued lines
                        switch($state)
                        {
                            case 'msgctxt':

                            case 'msgid':

                            case 'msgid_plural':

                                $temp[$state] .= "\n" . $line;
                                break;

                            case 'msgstr':

                                $temp[$state][count($temp[$state]) - 1] .= "\n" . $line;
                                break;

                            default :

                                # parse error
                                return false;
                        }
                    }
                    break;
            }
        }

        # add final entry
        if ($state === 'msgstr')
        {
            $hash[] = $temp;
        }

        # Cleanup data, merge multiline entries, reindex hash for ksort
        $temp = $hash;
        $hash = array();

        foreach ($temp as $entry)
        {
            foreach ($entry as & $v)
            {
                $v = self::po_clean_helper($v);

                if ($v === false)
                {
                    # parse error
                    return false;
                }
            }
            $hash[$entry['msgid']]= $entry;
        }

        return $hash;
    }

    private static function po_clean_helper($x)
    {
        if (true === is_array($x))
        {
            foreach ($x as $k => $v)
            {
                # WATCH IT! RECURSION!
                $x[$k]= self::po_clean_helper($v);
            }
        }
        else
        {
            if ($x[0] === '"')
            {
                $x = mb_substr($x, 1, -1);
            }

            $x = str_replace("\"\n\"", '', $x);
            $x = str_replace('$', '\\$', $x);

            # @todo eval to clean ???
            #Koch_Debug:firebug($x);
            $x = @ eval ("return \"$x\";");
        }

        return $x;
    }
}
?>