<?php

/**
 * Dokuwiki - UTF8 helper functions
 *
 * @license    LGPL 2.1 (http://www.gnu.org/copyleft/lesser.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 * @link       http://github.com/splitbrain/dokuwiki/raw/master/inc/utf8.php
 */

/**
 * check for mb_string support
 */
if(false === defined('UTF8_MBSTRING'))
{
    if(function_exists('mb_substr') && !defined('UTF8_NOMBSTRING'))
    {
        define('UTF8_MBSTRING', 1);
    }
    else
    {
        define('UTF8_MBSTRING', 0);
    }
}

if(UTF8_MBSTRING)
{
    mb_internal_encoding('UTF-8');
}

if(false === function_exists('utf8_isASCII'))
{

    /**
     * Checks if a string contains 7bit ASCII only
     *
     * @author Andreas Haerter <andreas.haerter@dev.mail-node.com>
     */
    function utf8_isASCII($str)
    {
        return (preg_match('/(?:[^\x00-\x7F])/', $str) !== 1);
    }

}

if(false === function_exists('utf8_strip'))
{

    /**
     * Strips all highbyte chars
     *
     * Returns a pure ASCII7 string
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function utf8_strip($str)
    {
        $ascii = '';
        $len = strlen($str);
        for($i = 0; $i < $len; $i++)
        {
            if(ord($str{$i}) < 128)
            {
                $ascii .= $str{$i};
            }
        }
        return $ascii;
    }

}

if(false === function_exists('utf8_check'))
{

    /**
     * Tries to detect if a string is in Unicode encoding
     *
     * @author <bmorel@ssi.fr>
     * @link   http://www.php.net/manual/en/function.utf8-encode.php
     */
    function utf8_check($Str)
    {
        $len = strlen($Str);
        for($i = 0; $i < $len; $i++)
        {
            $b = ord($Str[$i]);
            if($b < 0x80)
                continue;# 0bbbbbbb
            elseif(($b & 0xE0) == 0xC0)
                $n = 1;# 110bbbbb
            elseif(($b & 0xF0) == 0xE0)
                $n = 2;# 1110bbbb
            elseif(($b & 0xF8) == 0xF0)
                $n = 3;# 11110bbb
            elseif(($b & 0xFC) == 0xF8)
                $n = 4;# 111110bb
            elseif(($b & 0xFE) == 0xFC)
                $n = 5;# 1111110b
            else
                return false;# Does not match any model

            for($j = 0; $j < $n; $j++)
            { # n bytes matching 10bbbbbb follow ?
                if((++$i == $len) || ((ord($Str[$i]) & 0xC0) != 0x80))
                    return false;
            }
        }
        return true;
    }

}

if(false === function_exists('utf8_strlen'))
{

    /**
     * Unicode aware replacement for strlen()
     *
     * utf8_decode() converts characters that are not in ISO-8859-1
     * to '?', which, for the purpose of counting, is alright - It's
     * even faster than mb_strlen.
     *
     * @author <chernyshevsky at hotmail dot com>
     * @see    strlen()
     * @see    utf8_decode()
     */
    function utf8_strlen($string)
    {
        return strlen(utf8_decode($string));
    }

}

if(false === function_exists('utf8_substr'))
{

    /**
     * UTF-8 aware alternative to substr
     *
     * Return part of a string given character offset (and optionally length)
     *
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @author Chris Smith <chris@jalakai.co.uk>
     * @param string
     * @param integer number of UTF-8 characters offset (from left)
     * @param integer (optional) length in UTF-8 characters from offset
     * @return mixed string or false if failure
     */
    function utf8_substr($str, $offset, $length = null)
    {
        if(UTF8_MBSTRING)
        {
            if($length === null)
            {
                return mb_substr($str, $offset);
            }
            else
            {
                return mb_substr($str, $offset, $length);
            }
        }

        /*
         * Notes:
         *
         * no mb string support, so we'll use pcre regex's with 'u' flag
         * pcre only supports repetitions of less than 65536, in order to accept up to MAXINT values for
         * offset and length, we'll repeat a group of 65535 characters when needed (ok, up to MAXINT-65536)
         *
         * substr documentation states false can be returned in some cases (e.g. offset > string length)
         * mb_substr never returns false, it will return an empty string instead.
         *
         * calculating the number of characters in the string is a relatively expensive operation, so
         * we only carry it out when necessary. It isn't necessary for +ve offsets and no specified length
         */

        // cast parameters to appropriate types to avoid multiple notices/warnings
        $str = (string) $str;                          // generates E_NOTICE for PHP4 objects, but not PHP5 objects
        $offset = (int) $offset;
        if(false === is_null($length))
            $length = (int) $length;

        // handle trivial cases
        if($length === 0)
            return '';
        if($offset < 0 && $length < 0 && $length < $offset)
            return '';

        $offset_pattern = '';
        $length_pattern = '';

        // normalise -ve offsets (we could use a tail anchored pattern, but they are horribly slow!)
        if($offset < 0)
        {
            $strlen = strlen(utf8_decode($str));        // see notes
            $offset = $strlen + $offset;
            if($offset < 0)
                $offset = 0;
        }

        // establish a pattern for offset, a non-captured group equal in length to offset
        if($offset > 0)
        {
            $Ox = (int) ($offset / 65535);
            $Oy = $offset % 65535;

            if($Ox)
                $offset_pattern = '(?:.{65535}){' . $Ox . '}';
            $offset_pattern = '^(?:' . $offset_pattern . '.{' . $Oy . '})';
        } else
        {
            $offset_pattern = '^';                      // offset == 0; just anchor the pattern
        }

        // establish a pattern for length
        if(is_null($length))
        {
            $length_pattern = '(.*)$';                  // the rest of the string
        }
        else
        {

            if(false === isset($strlen))
                $strlen = strlen(utf8_decode($str));    // see notes
            if($offset > $strlen)
                return '';           // another trivial case

                if($length > 0)
            {

                $length = min($strlen - $offset, $length);  // reduce any length that would go passed the end of the string

                $Lx = (int) ($length / 65535);
                $Ly = $length % 65535;

                // +ve length requires ... a captured group of length characters
                if($Lx)
                    $length_pattern = '(?:.{65535}){' . $Lx . '}';
                $length_pattern = '(' . $length_pattern . '.{' . $Ly . '})';
            } else if($length < 0)
            {

                if($length < ($offset - $strlen))
                    return '';

                $Lx = (int) ((-$length) / 65535);
                $Ly = (-$length) % 65535;

                // -ve length requires ... capture everything except a group of -length characters
                //                         anchored at the tail-end of the string
                if($Lx)
                    $length_pattern = '(?:.{65535}){' . $Lx . '}';
                $length_pattern = '(.*)(?:' . $length_pattern . '.{' . $Ly . '})$';
            }
        }

        if(false === preg_match('#' . $offset_pattern . $length_pattern . '#us', $str, $match))
            return '';
        return $match[1];
    }

}

if(false === function_exists('utf8_substr_replace'))
{

    /**
     * Unicode aware replacement for substr_replace()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    substr_replace()
     */
    function utf8_substr_replace($string, $replacement, $start, $length=0)
    {
        $ret = '';
        if($start > 0)
            $ret .= utf8_substr($string, 0, $start);
        $ret .= $replacement;
        $ret .= utf8_substr($string, $start + $length);
        return $ret;
    }

}

if(false === function_exists('utf8_ltrim'))
{

    /**
     * Unicode aware replacement for ltrim()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    ltrim()
     * @return string
     */
    function utf8_ltrim($str, $charlist='')
    {
        if($charlist == '')
            return ltrim($str);

        //quote charlist for use in a characterclass
        $charlist = preg_replace('!([\\\\\\-\\]\\[/])!', '\\\${1}', $charlist);

        return preg_replace('/^[' . $charlist . ']+/u', '', $str);
    }

}

if(false === function_exists('utf8_rtrim'))
{

    /**
     * Unicode aware replacement for rtrim()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    rtrim()
     * @return string
     */
    function utf8_rtrim($str, $charlist='')
    {
        if($charlist == '')
            return rtrim($str);

        //quote charlist for use in a characterclass
        $charlist = preg_replace('!([\\\\\\-\\]\\[/])!', '\\\${1}', $charlist);

        return preg_replace('/[' . $charlist . ']+$/u', '', $str);
    }

}

if(false === function_exists('utf8_trim'))
{

    /**
     * Unicode aware replacement for trim()
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @see    trim()
     * @return string
     */
    function utf8_trim($str, $charlist='')
    {
        if($charlist == '')
            return trim($str);

        return utf8_ltrim(utf8_rtrim($str, $charlist), $charlist);
    }

}

if(false === function_exists('utf8_strtolower'))
{

    /**
     * This is a unicode aware replacement for strtolower()
     *
     * Uses mb_string extension if available
     *
     * @author Leo Feyer <leo@typolight.org>
     * @see    strtolower()
     * @see    utf8_strtoupper()
     */
    function utf8_strtolower($string)
    {
        if(UTF8_MBSTRING)
            return mb_strtolower($string, 'utf-8');

        global $UTF8_UPPER_TO_LOWER;
        return strtr($string, $UTF8_UPPER_TO_LOWER);
    }

}

if(false === function_exists('utf8_strtoupper'))
{

    /**
     * This is a unicode aware replacement for strtoupper()
     *
     * Uses mb_string extension if available
     *
     * @author Leo Feyer <leo@typolight.org>
     * @see    strtoupper()
     * @see    utf8_strtoupper()
     */
    function utf8_strtoupper($string)
    {
        if(UTF8_MBSTRING)
            return mb_strtoupper($string, 'utf-8');

        global $UTF8_LOWER_TO_UPPER;
        return strtr($string, $UTF8_LOWER_TO_UPPER);
    }

}

if(false === function_exists('utf8_ucfirst'))
{

    /**
     * UTF-8 aware alternative to ucfirst
     * Make a string's first character uppercase
     *
     * @author Harry Fuecks
     * @param string
     * @return string with first character as upper case (if applicable)
     */
    function utf8_ucfirst($str)
    {
        switch(utf8_strlen($str))
        {
            case 0:
                return '';
            case 1:
                return utf8_strtoupper($str);
            default:
                preg_match('/^(.{1})(.*)$/us', $str, $matches);
                return utf8_strtoupper($matches[1]) . $matches[2];
        }
    }

}

if(false === function_exists('utf8_ucwords'))
{

    /**
     * UTF-8 aware alternative to ucwords
     * Uppercase the first character of each word in a string
     *
     * @author Harry Fuecks
     * @param string
     * @return string with first char of each word uppercase
     * @see http://www.php.net/ucwords
     */
    function utf8_ucwords($str)
    {
        // Note: [\x0c\x09\x0b\x0a\x0d\x20] matches;
        // form feeds, horizontal tabs, vertical tabs, linefeeds and carriage returns
        // This corresponds to the definition of a "word" defined at http://www.php.net/ucwords
        $pattern = '/(^|([\x0c\x09\x0b\x0a\x0d\x20]+))([^\x0c\x09\x0b\x0a\x0d\x20]{1})[^\x0c\x09\x0b\x0a\x0d\x20]*/u';

        return preg_replace_callback($pattern, 'utf8_ucwords_callback', $str);
    }

    /**
     * Callback function for preg_replace_callback call in utf8_ucwords
     * You don't need to call this yourself
     *
     * @author Harry Fuecks
     * @param array of matches corresponding to a single word
     * @return string with first char of the word in uppercase
     * @see utf8_ucwords
     * @see utf8_strtoupper
     */
    function utf8_ucwords_callback($matches)
    {
        $leadingws = $matches[2];
        $ucfirst = utf8_strtoupper($matches[3]);
        $ucword = utf8_substr_replace(ltrim($matches[0]), $ucfirst, 0, 1);
        return $leadingws . $ucword;
    }

}

if(false === function_exists('utf8_deaccent'))
{

    /**
     * Replace accented UTF-8 characters by unaccented ASCII-7 equivalents
     *
     * Use the optional parameter to just deaccent lower ($case = -1) or upper ($case = 1)
     * letters. Default is to deaccent both cases ($case = 0)
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function utf8_deaccent($string, $case=0)
    {
        if($case <= 0)
        {
            global $UTF8_LOWER_ACCENTS;
            $string = strtr($string, $UTF8_LOWER_ACCENTS);
        }
        if($case >= 0)
        {
            global $UTF8_UPPER_ACCENTS;
            $string = strtr($string, $UTF8_UPPER_ACCENTS);
        }
        return $string;
    }

}

if(false === function_exists('utf8_romanize'))
{

    /**
     * Romanize a non-latin string
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function utf8_romanize($string)
    {
        if(utf8_isASCII($string))
            return $string; //nothing to do

            global $UTF8_ROMANIZATION;
        return strtr($string, $UTF8_ROMANIZATION);
    }

}

if(false === function_exists('utf8_stripspecials'))
{

    /**
     * Removes special characters (nonalphanumeric) from a UTF-8 string
     *
     * This function adds the controlchars 0x00 to 0x19 to the array of
     * stripped chars (they are not included in $UTF8_SPECIAL_CHARS)
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     * @param  string $string     The UTF8 string to strip of special chars
     * @param  string $repl       Replace special with this string
     * @param  string $additional Additional chars to strip (used in regexp char class)
     */
    function utf8_stripspecials($string, $repl='', $additional='')
    {
        global $UTF8_SPECIAL_CHARS;
        global $UTF8_SPECIAL_CHARS2;

        static $specials = null;
        if(is_null($specials))
        {
            #$specials = preg_quote(unicode_to_utf8($UTF8_SPECIAL_CHARS), '/');
            $specials = preg_quote($UTF8_SPECIAL_CHARS2, '/');
        }

        return preg_replace('/[' . $additional . '\x00-\x19' . $specials . ']/u', $repl, $string);
    }

}

if(false === function_exists('utf8_strpos'))
{

    /**
     * This is an Unicode aware replacement for strpos
     *
     * @author Leo Feyer <leo@typolight.org>
     * @see    strpos()
     * @param  string
     * @param  string
     * @param  integer
     * @return integer
     */
    function utf8_strpos($haystack, $needle, $offset=0)
    {
        $comp = 0;
        $length = null;

        while(is_null($length) || $length < $offset)
        {
            $pos = strpos($haystack, $needle, $offset + $comp);

            if($pos === false)
                return false;

            $length = utf8_strlen(substr($haystack, 0, $pos));

            if($length < $offset)
                $comp = $pos - $length;
        }

        return $length;
    }

}

if(false === function_exists('utf8_tohtml'))
{

    /**
     * Encodes UTF-8 characters to HTML entities
     *
     * @author Tom N Harris <tnharris@whoopdedo.org>
     * @author <vpribish at shopping dot com>
     * @link   http://www.php.net/manual/en/function.utf8-decode.php
     */
    function utf8_tohtml($str)
    {
        $ret = '';
        foreach(utf8_to_unicode($str) as $cp)
        {
            if($cp < 0x80)
                $ret .= chr($cp);
            elseif($cp < 0x100)
                $ret .= "&#$cp;";
            else
                $ret .= '&#x' . dechex($cp) . ';';
        }
        return $ret;
    }

}

if(false === function_exists('utf8_unhtml'))
{

    /**
     * Decodes HTML entities to UTF-8 characters
     *
     * Convert any &#..; entity to a codepoint,
     * The entities flag defaults to only decoding numeric entities.
     * Pass HTML_ENTITIES and named entities, including &amp; &lt; etc.
     * are handled as well. Avoids the problem that would occur if you
     * had to decode "&amp;#38;&#38;amp;#38;"
     *
     * unhtmlspecialchars(utf8_unhtml($s)) -> "&#38;&#38;"
     * utf8_unhtml(unhtmlspecialchars($s)) -> "&&amp#38;"
     * what it should be                   -> "&#38;&amp#38;"
     *
     * @author Tom N Harris <tnharris@whoopdedo.org>
     * @param  string  $str      UTF-8 encoded string
     * @param  boolean $entities Flag controlling decoding of named entities.
     * @return UTF-8 encoded string with numeric (and named) entities replaced.
     */
    function utf8_unhtml($str, $entities=null)
    {
        static $decoder = null;
        if(is_null($decoder))
            $decoder = new utf8_entity_decoder();
        if(is_null($entities))
            return preg_replace_callback('/(&#([Xx])?([0-9A-Za-z]+);)/m', 'utf8_decode_numeric', $str);
        else
            return preg_replace_callback('/&(#)?([Xx])?([0-9A-Za-z]+);/m', array(&$decoder, 'decode'), $str);
    }

}

if(false === function_exists('utf8_decode_numeric'))
{

    function utf8_decode_numeric($ent)
    {
        switch($ent[2])
        {
            case 'X':
            case 'x':
                $cp = hexdec($ent[3]);
                break;
            default:
                $cp = intval($ent[3]);
                break;
        }
        return unicode_to_utf8(array($cp));
    }

}

if(false === class_exists('utf8_entity_decoder'))
{

    class utf8_entity_decoder
    {
        var $table;

        function utf8_entity_decoder()
        {
            $table = get_html_translation_table(HTML_ENTITIES);
            $table = array_flip($table);
            $this->table = array_map(array(&$this, 'makeutf8'), $table);
        }

        function makeutf8($c)
        {
            return unicode_to_utf8(array(ord($c)));
        }

        function decode($ent)
        {
            if($ent[1] == '#')
            {
                return utf8_decode_numeric($ent);
            }
            elseif(array_key_exists($ent[0], $this->table))
            {
                return $this->table[$ent[0]];
            }
            else
            {
                return $ent[0];
            }
        }

    }
}

if(false === function_exists('utf8_to_unicode'))
{

    /**
     * Takes an UTF-8 string and returns an array of ints representing the
     * Unicode characters. Astral planes are supported ie. the ints in the
     * output can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
     * are not allowed.
     *
     * If $strict is set to true the function returns false if the input
     * string isn't a valid UTF-8 octet sequence and raises a PHP error at
     * level E_USER_WARNING
     *
     * Note: this function has been modified slightly in this library to
     * trigger errors on encountering bad bytes
     *
     * @author <hsivonen@iki.fi>
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @param  string  UTF-8 encoded string
     * @param  boolean Check for invalid sequences?
     * @return mixed array of unicode code points or false if UTF-8 invalid
     * @see    unicode_to_utf8
     * @link   http://hsivonen.iki.fi/php-utf8/
     * @link   http://sourceforge.net/projects/phputf8/
     */
    function utf8_to_unicode($str, $strict=false)
    {
        $mState = 0;     // cached expected number of octets after the current octet
        // until the beginning of the next UTF8 character sequence
        $mUcs4 = 0;     // cached Unicode character
        $mBytes = 1;     // cached expected number of octets in the current sequence

        $out = array();

        $len = strlen($str);

        for($i = 0; $i < $len; $i++)
        {

            $in = ord($str{$i});

            if($mState == 0)
            {

                // When mState is zero we expect either a US-ASCII character or a
                // multi-octet sequence.
                if(0 == (0x80 & ($in)))
                {
                    // US-ASCII, pass straight through.
                    $out[] = $in;
                    $mBytes = 1;
                }
                else if(0xC0 == (0xE0 & ($in)))
                {
                    // First octet of 2 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x1F) << 6;
                    $mState = 1;
                    $mBytes = 2;
                }
                else if(0xE0 == (0xF0 & ($in)))
                {
                    // First octet of 3 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x0F) << 12;
                    $mState = 2;
                    $mBytes = 3;
                }
                else if(0xF0 == (0xF8 & ($in)))
                {
                    // First octet of 4 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x07) << 18;
                    $mState = 3;
                    $mBytes = 4;
                }
                else if(0xF8 == (0xFC & ($in)))
                {
                    /* First octet of 5 octet sequence.
                     *
                     * This is illegal because the encoded codepoint must be either
                     * (a) not the shortest form or
                     * (b) outside the Unicode range of 0-0x10FFFF.
                     * Rather than trying to resynchronize, we will carry on until the end
                     * of the sequence and let the later error handling code catch it.
                     */
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x03) << 24;
                    $mState = 4;
                    $mBytes = 5;
                }
                else if(0xFC == (0xFE & ($in)))
                {
                    // First octet of 6 octet sequence, see comments for 5 octet sequence.
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 1) << 30;
                    $mState = 5;
                    $mBytes = 6;
                }
                elseif($strict)
                {
                    /* Current octet is neither in the US-ASCII range nor a legal first
                     * octet of a multi-octet sequence.
                     */
                    trigger_error(
                            'utf8_to_unicode: Illegal sequence identifier ' .
                            'in UTF-8 at byte ' . $i, E_USER_WARNING
                    );
                    return false;
                }
            }
            else
            {

                // When mState is non-zero, we expect a continuation of the multi-octet
                // sequence
                if(0x80 == (0xC0 & ($in)))
                {

                    // Legal continuation.
                    $shift = ($mState - 1) * 6;
                    $tmp = $in;
                    $tmp = ($tmp & 0x0000003F) << $shift;
                    $mUcs4 |= $tmp;

                    /**
                     * End of the multi-octet sequence. mUcs4 now contains the final
                     * Unicode codepoint to be output
                     */
                    if(0 == --$mState)
                    {

                        /*
                         * Check for illegal sequences and codepoints.
                         */
                        // From Unicode 3.1, non-shortest form is illegal
                        if(((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
                                ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
                                ((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
                                (4 < $mBytes) ||
                                // From Unicode 3.2, surrogate characters are illegal
                                (($mUcs4 & 0xFFFFF800) == 0xD800) ||
                                // Codepoints outside the Unicode range are illegal
                                ($mUcs4 > 0x10FFFF))
                        {

                            if($strict)
                            {
                                trigger_error(
                                        'utf8_to_unicode: Illegal sequence or codepoint ' .
                                        'in UTF-8 at byte ' . $i, E_USER_WARNING
                                );

                                return false;
                            }
                        }

                        if(0xFEFF != $mUcs4)
                        {
                            // BOM is legal but we don't want to output it
                            $out[] = $mUcs4;
                        }

                        //initialize UTF8 cache
                        $mState = 0;
                        $mUcs4 = 0;
                        $mBytes = 1;
                    }
                }
                elseif($strict)
                {
                    /**
                     * ((0xC0 & (*in) != 0x80) && (mState != 0))
                     * Incomplete multi-octet sequence.
                     */
                    trigger_error(
                            'utf8_to_unicode: Incomplete multi-octet ' .
                            '   sequence in UTF-8 at byte ' . $i, E_USER_WARNING
                    );

                    return false;
                }
            }
        }
        return $out;
    }

}

if(false === function_exists('unicode_to_utf8'))
{

    /**
     * Takes an array of ints representing the Unicode characters and returns
     * a UTF-8 string. Astral planes are supported ie. the ints in the
     * input can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
     * are not allowed.
     *
     * If $strict is set to true the function returns false if the input
     * array contains ints that represent surrogates or are outside the
     * Unicode range and raises a PHP error at level E_USER_WARNING
     *
     * Note: this function has been modified slightly in this library to use
     * output buffering to concatenate the UTF-8 string (faster) as well as
     * reference the array by it's keys
     *
     * @param  array of unicode code points representing a string
     * @param  boolean Check for invalid sequences?
     * @return mixed UTF-8 string or false if array contains invalid code points
     * @author <hsivonen@iki.fi>
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @see    utf8_to_unicode
     * @link   http://hsivonen.iki.fi/php-utf8/
     * @link   http://sourceforge.net/projects/phputf8/
     */
    function unicode_to_utf8($arr, $strict=false)
    {
        if(false === is_array($arr))
            return '';
        ob_start();

        foreach(array_keys($arr) as $k)
        {

            if(($arr[$k] >= 0) && ($arr[$k] <= 0x007f))
            {
                # ASCII range (including control chars)

                echo chr($arr[$k]);
            }
            else if($arr[$k] <= 0x07ff)
            {
                # 2 byte sequence

                echo chr(0xc0 | ($arr[$k] >> 6));
                echo chr(0x80 | ($arr[$k] & 0x003f));
            }
            else if($arr[$k] == 0xFEFF)
            {
                # Byte order mark (skip)
                // nop -- zap the BOM
            }
            else if($arr[$k] >= 0xD800 && $arr[$k] <= 0xDFFF)
            {
                # Test for illegal surrogates
                // found a surrogate
                if($strict)
                {
                    trigger_error(
                            'unicode_to_utf8: Illegal surrogate ' .
                            'at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING
                    );
                    return false;
                }
            }
            else if($arr[$k] <= 0xffff)
            {
                # 3 byte sequence

                echo chr(0xe0 | ($arr[$k] >> 12));
                echo chr(0x80 | (($arr[$k] >> 6) & 0x003f));
                echo chr(0x80 | ($arr[$k] & 0x003f));
            }
            else if($arr[$k] <= 0x10ffff)
            {
                # 4 byte sequence

                echo chr(0xf0 | ($arr[$k] >> 18));
                echo chr(0x80 | (($arr[$k] >> 12) & 0x3f));
                echo chr(0x80 | (($arr[$k] >> 6) & 0x3f));
                echo chr(0x80 | ($arr[$k] & 0x3f));
            }
            elseif($strict)
            {

                trigger_error(
                        'unicode_to_utf8: Codepoint out of Unicode range ' .
                        'at index: ' . $k . ', value: ' . $arr[$k], E_USER_WARNING
                );

                // out of range
                return false;
            }
        }

        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

}

if(false === function_exists('utf8_to_utf16be'))
{

    /**
     * UTF-8 to UTF-16BE conversion.
     *
     * Maybe really UCS-2 without mb_string due to utf8_to_unicode limits
     */
    function utf8_to_utf16be(&$str, $bom = false)
    {
        $out = $bom ? "\xFE\xFF" : '';
        if(UTF8_MBSTRING)
            return $out . mb_convert_encoding($str, 'UTF-16BE', 'UTF-8');

        $uni = utf8_to_unicode($str);
        foreach($uni as $cp)
        {
            $out .= pack('n', $cp);
        }
        return $out;
    }

}

if(false === function_exists('utf16be_to_utf8'))
{

    /**
     * UTF-8 to UTF-16BE conversion.
     *
     * Maybe really UCS-2 without mb_string due to utf8_to_unicode limits
     */
    function utf16be_to_utf8(&$str)
    {
        $uni = unpack('n*', $str);
        return unicode_to_utf8($uni);
    }

}

if(false === function_exists('utf8_bad_replace'))
{

    /**
     * Replace bad bytes with an alternative character
     *
     * ASCII character is recommended for replacement char
     *
     * PCRE Pattern to locate bad bytes in a UTF-8 string
     * Comes from W3 FAQ: Multilingual Forms
     * Note: modified to include full ASCII range including control chars
     *
     * @author Harry Fuecks <hfuecks@gmail.com>
     * @see http://www.w3.org/International/questions/qa-forms-utf-8
     * @param string to search
     * @param string to replace bad bytes with (defaults to '?') - use ASCII
     * @return string
     */
    function utf8_bad_replace($str, $replace = '')
    {
        $UTF8_BAD =
                '([\x00-\x7F]' . # ASCII (including control chars)
                '|[\xC2-\xDF][\x80-\xBF]' . # non-overlong 2-byte
                '|\xE0[\xA0-\xBF][\x80-\xBF]' . # excluding overlongs
                '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}' . # straight 3-byte
                '|\xED[\x80-\x9F][\x80-\xBF]' . # excluding surrogates
                '|\xF0[\x90-\xBF][\x80-\xBF]{2}' . # planes 1-3
                '|[\xF1-\xF3][\x80-\xBF]{3}' . # planes 4-15
                '|\xF4[\x80-\x8F][\x80-\xBF]{2}' . # plane 16
                '|(.{1}))';                              # invalid byte
        ob_start();
        while(preg_match('/' . $UTF8_BAD . '/S', $str, $matches))
        {
            if(false === isset($matches[2]))
            {
                echo $matches[0];
            }
            else
            {
                echo $replace;
            }
            $str = substr($str, strlen($matches[0]));
        }
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

}

if(false === function_exists('utf8_correctIdx'))
{

    /**
     * adjust a byte index into a utf8 string to a utf8 character boundary
     *
     * @param $str   string   utf8 character string
     * @param $i     int      byte index into $str
     * @param $next  bool     direction to search for boundary,
     *                           false = up (current character)
     *                           true = down (next character)
     *
     * @return int            byte index into $str now pointing to a utf8 character boundary
     *
     * @author       chris smith <chris@jalakai.co.uk>
     */
    function utf8_correctIdx(&$str, $i, $next=false)
    {

        if($i <= 0)
            return 0;

        $limit = strlen($str);
        if($i >= $limit)
            return $limit;

        if($next)
        {
            while(($i < $limit) && ((ord($str[$i]) & 0xC0) == 0x80))
                $i++;
        }
        else
        {
            while($i && ((ord($str[$i]) & 0xC0) == 0x80))
                $i--;
        }

        return $i;
    }
}