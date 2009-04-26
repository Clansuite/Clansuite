<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * File:         input.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Input Handling
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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Inputfilter Class
 *
 * This class provides some kind of inputblocker, which filters and cleans up
 * the $_REQUEST, reverses magic quotes and gives the check method for several filter checks.
 *
 * @note by vain: check PHP 5 >= 5.2.0 FilterFunctions
 * @todo implement PHP5.2 Filterfunctions
 * @link http://www.php.net/manual/de/ref.filter.php
 *
 */
class Clansuite_Inputfilter
{
    /**
     * Modifies a given String
     *
     * @param string $string String to modify
     * @param string $modificators One OR Multiple Modificators to use on the String
     */
    public function modify($string='', $modificators='' )
    {
        $mods = array();
        $mods = split('[|]' ,$modificators);

        foreach ($mods as $key => $value)
        {
            switch ($value)
            {
                case 'add_slashes':
                    $string = addslashes($string);
                    break;

                case 'strip_slashes':
                    $string = stripslashes($string);
                    break;

                case 'strip_tags':
                    $string = striptags($string);
                    break;

                case 'urlencode':
                    $string = urlencode($string);
                    break;

                case 'urldecode':
                    $string = urldecode($string);
                    break;

                    // Replacement: ? instead of &#233
                    // todo: include replacement of & with amp -> menueditor.module.php line155
                case 'html_replace:numeric_entities':
                    $string = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
                    break;

                    // Replacement: zB &#8364 instead of &euro
                case 'html_replace:normal_to_numerical_entities':
                    $string = $this->modify( html_entity_decode($string),'html_numeric_entities' );
                    break;

                default:
                    break;
            }
        }

        return $string;
    }

    /**
     * Check a string
     *
     * USAGE:
     *
     * -----------------------------------------------------------------------------
     * | Possible single checks:
     * | $input->check('thisisastring...asdf', 'is_int', 'optional reg.exp pattern', 'optional length')
     * -----------------------------------------------------------------------------
     *
     *  is_int: 0-9
     *  is_abc: a-z,A-Z
     *  is_pattern: Given pattern... $input->check('abasdfdf_', 'is_pattern', '/^[a-zA-Z_]$/');
     *
     * Will give true
     *
     * is_pass_length: $cfg->min_pass_length
     * is_icq: 123-123-123 or 123123123
     * is_sessionid: a-z, A-Z, 0-9
     * is_hostname: http://www.hostname.de
     * is_url: http://www.thisurlwithpath.de/folder1/folder2
     * is_steamid: 00:0:1231451
     * is_email: test@test.de
     * is_ip: 122.122.123.123
     * is_violent: "SELECT%20", "%00", "chr(", "eval(" is declared as violent
     *
     * Examples:
     * ---------
     * $input->check('2344vsladkf', 'is_int' );
     * Will return bool(false)
     * $input->check('2344vsladkf', 'is_pattern', '/^[a-z0-9]+$/' );
     * Will return bool(true)
     *
     * -----------------------------------------------------------------------------
     * | Possible multiple checks:
     * | $input->check('thisisastring...asdf', 'is_int|is_abc|is_custom', 'optional custom chars')
     * -----------------------------------------------------------------------------
     * is_int: 0-9
     * is_abc: a-z,A-Z
     * is_custom: all avaible chars
     *
     * Examples:
     * ---------
     * $input->check('2344avsdffs', 'is_int|is_abc' );
     * Will return bool(true)
     * $input->check('235432asdlfkj_;', 'is_int|is_abc|is_custom', '_;' );
     * Will return bool(true)
     *
     * ----------------------------------------
     *
     * @param string $string The String to perform the check or checks on, Default empty
     * @param string $types Check for a specific type, Default empty
     * @param string $pattern Check for a specific pattern, Default empty
     * @param int $length Check for a specific string length, Default (int) 0
     *
     * @return Returns boolean TRUE or FALSE.
     * @todo search for globals and replace!
     */
    public function check( $string = '', $types = '', $pattern = '', $length = 0 )
    {
        global $error, $cfg;

        $r_bool  = false;
        $bools   = array();
        $a_types = array();
        $a_types = split('[|]' ,$types);

        if (count($a_types) > 1 )
        {
            $reg_exp = '/^[';

            foreach ($a_types as $key => $type)
            {
                switch ($type)
                {
                    // SPECIAL : set reg_exp to specific searchpattern
                    // give-trough
                    // @input : $pattern
                    case 'is_custom':
                        $incoming = str_split( $pattern );
                        foreach ( $incoming as $key => $value )
                        {
                            $reg_exp .= '\\'.$value;
                        }
                        break;

                        // Normal RegExp Cases

                        // Is integer?
                        # @todo preg_match("![0-9]+!", $foo); SLOWER THAN ctype_digit($foo);
                    case 'is_int':
                        $reg_exp .= '0-9';
                        break;

                        // Is alphabetic?
                    case 'is_abc':
                        $reg_exp .= 'a-zA-Z';
                        break;
                }

            }

            if ($length == 0 )
            {
                $reg_exp .= ']+$/';
            }
            else
            {
                $reg_exp .= ']{1,'. $length .'}$/';
            }
            $r_bool = preg_match($reg_exp, $string) ? true : false;
        }
        else
        {
            switch ($a_types[0])
            {
                // Different Checkconditions: Watch out!
                // Does the password fits the minimum length?
                case 'is_pass_length':
                    $reg_exp = '/^.{'. $cfg->min_pass_length .',}$/';
                    break;

                    // SPECIAL : set reg_exp to specific searchpattern
                    // give-trough
                    // @input : $pattern
                case 'is_pattern':
                    $reg_exp = $pattern;
                    break;

                    // Normal RegExp Cases

                    // Is integer?
                case 'is_int':
                    $reg_exp = '/^[0-9]+$/';
                    break;

                    // Is alphabetic?
                case 'is_abc':
                    $reg_exp = '/^[a-zA-Z]+$/';
                    break;

                    // Is ICQ ?
                case 'is_icq':
                    $reg_exp = '/^[\d-]*$/i';
                    break;

                    // Is Sessionid ?
                case 'is_sessionid':
                    $reg_exp     = '/^[A-Za-z0-9]+$/';
                    break;

                    // Is hostname?
                case 'is_hostname':
                    $reg_exp = '/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/)*([a-z]{1,}[\w-.]{0,}).([a-z]{2,6})$/i';
                    break;

                    // Is url?
                case 'is_url':
                    $reg_exp  = "/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/)([a-z]{1,}[\w-.]{0,}).([a-z]{2,6})(\/{1}[\w_]{1}[\/\w-&?=_%]{0,}(.{1}[\/\w-&?=_%]{0,})*)*$/i";
                    break;

                    // Is Steam ID ?
                case 'is_steam_id':
                    $reg_exp     = '/^[0-9]+:[0-9]+:[0-9]+$/';
                    break;

                    // Check if mail is valid ?
                case 'is_email':
                    $reg_exp = '/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/';
                    break;

                    // Check if valid ip ?
                case 'is_ip':
                    $num = "(25[0-5]|2[0-4]\d|[01]?\d\d|\d)";
                    $reg_exp = "/^$num\\.$num\\.$num\\.$num$/";
                    break;

                    // Check for violent code
                case 'is_violent':
                    $reg_exp = '/SELECT\s|\x|chr\(|eval\(|password\s|\0|phpinfo\(/i';
                    break;
            }

            $r_bool = preg_match($reg_exp, $string) ? true : false;

            if ($length != 0 and strlen($string ) > (int) $length )
            {
                $r_bool = false;
            }

            if (strlen($string ) == 0 )
            {
                $r_bool = false;
            }
        }

        if ($r_bool == false and $a_types[0] != 'is_violent')
        {
            $error->error_log['security']['checked_false'] = _('A variable is checked as "false":').'Type: ' . $a_types[0];
        }
        return $r_bool;
    }
}
?>