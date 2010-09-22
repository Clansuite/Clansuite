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
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Functions
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Functions
 */
class Clansuite_Functions
{
    /**
     * @var array This array contains the names of the loaded functions from directory /core/functions.
     */
    static $already_loaded = array();

    /**
     * @brief Generates a Universally Unique IDentifier, version 4.
     *
     * This function generates a truly random UUID.
     *
     * @author: sean at seancolombo dot com; 06.01.2009; http://www.php.net/uniqid
     * @see http://tools.ietf.org/html/rfc4122#section-4.4
     * @see http://en.wikipedia.org/wiki/UUID
     * @return string A UUID, made up of 32 hex digits and 4 hyphens.
     */
    public static function generateSecureUUID()
    {

        $pr_bits = null;
        $fp = fopen('/dev/urandom', 'rb');
        if($fp !== false)
        {
            $pr_bits .= @ fread($fp, 16);
            fclose($fp);
        }
        else
        {
            # If /dev/urandom isn't available (eg: in non-unix systems), use mt_rand().
            $pr_bits = '';
            for($cnt = 0; $cnt < 16; $cnt++)
            {
                $pr_bits .= chr(mt_rand(0, 255));
            }
        }

        $time_low = bin2hex(substr($pr_bits, 0, 4));
        $time_mid = bin2hex(substr($pr_bits, 4, 2));
        $time_hi_and_version = bin2hex(substr($pr_bits, 6, 2));
        $clock_seq_hi_and_reserved = bin2hex(substr($pr_bits, 8, 2));
        $node = bin2hex(substr($pr_bits, 10, 6));

        /**
         * Set the four most significant bits (bits 12 through 15) of the
         * time_hi_and_version field to the 4-bit version number from
         * Section 4.1.3.
         * @see http://tools.ietf.org/html/rfc4122#section-4.1.3
         */
        $time_hi_and_version = hexdec($time_hi_and_version);
        $time_hi_and_version = $time_hi_and_version >> 4;
        $time_hi_and_version = $time_hi_and_version | 0x4000;

        /**
         * Set the two most significant bits (bits 6 and 7) of the
         * clock_seq_hi_and_reserved to zero and one, respectively.
         */
        $clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

        return sprintf('%08s-%04s-%04x-%04x-%012s', $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node);
    }

    /**
     * Calculates the size of a directory (recursiv)
     *
     * Hint: use return getsize() on the return value of this function transform it to a readable format.
     * @see getsize()
     * @example
     * $readable_dirsize = Clansuite_Functions::getsize(Clansuite_Functions::dirsize($dir));
     *
     * @param $dir Directory Path
     * @return $size size of directory in bytes
     */
    public static function dirsize($dir)
    {
        if(is_dir($dir) == false)
        {
            return false;
        }

        $size = 0;
        $dh = opendir($dir);
        while(($entry = readdir($dh)) !== false)
        {
            # exclude ./..
            if($entry == '.' or $entry == '..')
            {
                continue;
            }

            $direntry = $dir . '/' . $entry;

            if(is_dir($direntry))
            {
                # recursion
                $size += dirsize($direntry);
            }
            else
            {
                $size += filesize($direntry);
            }

            unset($direntry);
        }

        closedir($dh);
        return $size;
    }

    /**
     * Converts an Object to an Array
     *
     * @param $object object to convert
     * @return array
     */
    public static function object2array($object)
    {
        $array = null;
        if(is_object($object))
        {
            $array = array();
            foreach(get_object_vars($object) as $key => $value)
            {
                if(is_object($value))
                {
                    $array[$key] = self::object2Array($value);
                }
                else
                {
                    $array[$key] = $value;
                }
            }
        }
        return $array;
    }

    /**
     * Converts an Array to an Object
     *
     * @param $array array to convert to an object
     * @return array
     */
    public function array2object($array)
    {
        if(is_array($array) == false)
        {
            return $array;
        }

        $object = new stdClass();

        if(is_array($array) and count($array) > 0)
        {
            foreach($array as $name => $value)
            {
                $name = mb_strtolower(trim($name));

                if(empty($name) == false)
                {
                    $object->$name = arrayToObject($value);
                }
            }

            return $object;
        }
        else
        {
            return false;
        }
    }

    /**
     * cut_string_backwards
     *
     * haystack = abc_def
     * needle = _def
     * result = abc
     *
     * In PHP6
     * abc = $string = mb_strstr('abc_def', '_def');
     *
     * @param $haystack string
     * @param $needle string
     * @return string
     */
    public static function cut_string_backwards($haystack, $needle)
    {
        $needle_length = mb_strlen($needle);

        if(($i = mb_strpos($haystack, $needle) !== false))
        {
            return mb_substr($haystack, 0, -$needle_length);
        }
        return $haystack;
    }

    /**
     * Converts a SimpleXML String recursivly to an Array
     *
     * @author Jason Sheets <jsheets at shadonet dot com>
     * @param string $xml SimpleXML String
     * @return Array
     */
    public static function SimpleXMLToArray($simplexml)
    {
        $array = array();

        if($simplexml)
        {
            foreach($simplexml as $k => $v)
            {
                if($simplexml['list'])
                {
                    $array[] = self::SimpleXMLToArray($v);
                }
                else
                {
                    $array[$k] = self::SimpleXMLToArray($v);
                }
            }
        }

        if(count($array) > 0)
        {
            return $array;
        }
        else
        {
            return (string) $simplexml;
        }
    }

    /**
     * @param string $haystack
     * @param string $replace
     * @param string $needle
     * @param int $times
     * @return $needle
     */
    public static function str_replace_count($haystack, $replace, $needle, $times)
    {
        $subject_original = $needle;
        $length = mb_strlen($haystack);
        $pos = 0;

        for($i = 1; $i<=$times; $i++)
        {
            $pos = mb_strpos($needle, $haystack, $pos);

            if($pos !== false)
            {
                $needle = mb_substr($subject_original, 0, $pos);
                $needle .= $replace;
                $needle .= mb_substr($subject_original, $pos + $length);
                $subject_original = $needle;
            }
            else
            {
                break;
            }
        }

        return $needle;
    }

    /**
     * Applies a slashfix to the path.
     *
     * @param $path
     * @return $string slashfixed path
     */
    public static function slashfix($path)
    {
        # DS on win is "\"
        if(DS == '\\')
        {
            # correct slashes
            return str_replace('/', '\\', $path);
        }
    }

    /**
     * Takes a needle and multi-dimensional haystack array and does a search on it's values.
     *
     * @param string $needle Needle to find
     * @param array $haystack Haystack to look through
     * @result array Returns the elements that the $string was found in
     *
     * array_values_recursive
     */
    static function array_find_element_by_key($needle, $haystack)
    {
        # take a look for the needle
        if(array_key_exists($needle, $haystack))
        {
            # if found, return it
            return $haystack[$needle];
        }

        # dig a little bit deeper in the array structure
        foreach($haystack as $k => $v)
        {
            if(is_array($v))
            {
                # recursion
                return self::array_find_element_by_key($needle, $v);
            }
        }

        return false;
    }

    /**
     * array_compare
     *
     * @author  55 dot php at imars dot com
     * @author  dwarven dot co dot uk
     * @link    http://www.php.net/manual/de/function.array-diff-assoc.php#89635
     * @param $array1
     * @param $array2
     */
    public static function array_compare($array1, $array2)
    {
        $diff = false;

        # Left-to-right
        foreach($array1 as $key => $value)
        {
            if(array_key_exists($key, $array2) == false)
            {
                $diff[0][$key] = $value;
            }
            elseif(is_array($value))
            {
                if(is_array($array2[$key]) == false)
                {
                    $diff[0][$key] = $value;
                    $diff[1][$key] = $array2[$key];
                }
                else
                {
                    $new = self::array_compare($value, $array2[$key]);

                    if($new !== false)
                    {
                        if(isset($new[0]))
                            $diff[0][$key] = $new[0];
                        if(isset($new[1]))
                            $diff[1][$key] = $new[1];
                    }
                }
            }
            elseif($array2[$key] !== $value)
            {
                $diff[0][$key] = $value;
                $diff[1][$key] = $array2[$key];
            }
        }

        # Right-to-left
        foreach($array2 as $key => $value)
        {
            if(array_key_exists($key, $array1) == false)
            {
                $diff[1][$key] = $value;
            }

            /**
             * No direct comparsion because matching keys were compared in the
             * left-to-right loop earlier, recursively.
             */
        }

        return $diff;
    }

    /**
     * Combines two arrays by using $keyArray as key providing array
     * an $valueArray as value providing array.
     * In case the valueArray is greater than the keyArray,
     * the keyArray determines the maximum number of values returned.
     * In case the valueArray is smaller than the keyArray,
     * those keys are returned for which values exist.
     *
     * @example
     * $keys = array('mod', 'sub', 'action', 'id');
     * $values = array('news', 'admin');
     * $combined = self::array_unequal_combine($keys, $values);
     * Results in: array('mod'=>'news', 'sub'=>'admin');
     *
     * @param array $keyArray
     * @param array $valueArray
     * @return array Combined Array
     */
    public static function array_unequal_combine($keyArray, $valueArray)
    {
        $returnArray = array();
        $key = '';
        $i = 0;

        foreach($keyArray as $key)
        {
            if(isset($valueArray[$i]))
            {
                $returnArray[$key] = $valueArray[$i++];
            }
        }

        return $returnArray;
    }

    /**
     * flatten multi-dimensional array
     *
     * @param array $array
     * @return array
     */
    public static function array_flatten(array $array)
    {
        $flatened_array = array();
        foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $value)
        {
            $flatened_array[] = $value;
        }
        return $flatened_array;
    }

    /**
     * distanceOfTimeInWords
     *
     * @author: anon
     * @link: http://www.php.net/manual/de/function.time.php#85481
     *
     * @param $fromTime starttime
     * @param $toTime endtime
     * @param $showLessThanAMinute boolean
     * @return string
     */
    public static function distanceOfTimeInWords($fromTime, $toTime = 0, $showLessThanAMinute = false)
    {
        $distanceInSeconds = round(abs($toTime - $fromTime));
        $distanceInMinutes = round($distanceInSeconds / 60);

        if($distanceInMinutes <= 1)
        {
            if($showLessThanAMinute == false)
            {
                return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
            }
            else
            {
                if($distanceInSeconds < 5)
                {
                    return 'less than 5 seconds';
                }
                if($distanceInSeconds < 10)
                {
                    return 'less than 10 seconds';
                }
                if($distanceInSeconds < 20)
                {
                    return 'less than 20 seconds';
                }
                if($distanceInSeconds < 40)
                {
                    return 'about half a minute';
                }
                if($distanceInSeconds < 60)
                {
                    return 'less than a minute';
                }
                return '1 minute';
            }
        }
        if($distanceInMinutes < 45)
        {
            return $distanceInMinutes . ' minutes';
        }
        if($distanceInMinutes < 90)
        {
            return 'about 1 hour';
        }
        if($distanceInMinutes < 1440)
        {
            return 'about ' . round(floatval($distanceInMinutes) / 60.0) . ' hours';
        }
        if($distanceInMinutes < 2880)
        {
            return '1 day';
        }
        if($distanceInMinutes < 43200)
        {
            return 'about ' . round(floatval($distanceInMinutes) / 1440) . ' days';
        }
        if($distanceInMinutes < 86400)
        {
            return 'about 1 month';
        }
        if($distanceInMinutes < 525600)
        {
            return round(floatval($distanceInMinutes) / 43200) . ' months';
        }
        if($distanceInMinutes < 1051199)
        {
            return 'about 1 year';
        }

        return 'over ' . round(floatval($distanceInMinutes) / 525600) . ' years';
    }

    /**
     * Performs a dateToWord transformation via gettext.
     * uses idate() to format a local time/date as integer and gettext functions _n(), _t()
     * @see http://www.php.net/idate
     *
     * @param string $from
     * @param string $now
     * @return string Word representation of
     */
    public static function dateToWord($from, $now = null)
    {
        if($now === null)
        {
            $now = time();
        }

        $between = $now - $from;

        if($between < 86400 and idate('d', $from) == idate('d', $now))
        {

            if($between < 3600 and idate('H', $from) == idate('H', $now))
            {

                if($between < 60 and idate('i', $from) == idate('i', $now))
                {
                    $second = idate('s', $now) - idate('s', $from);
                    return sprintf(_n('%d', '%d', $second), $second);
                }

                $min = idate('i', $now) - idate('i', $from);
                return sprintf(_n('%d', '%d', $min), $min);
            }

            $hour = idate('H', $now) - idate('H', $from);
            return sprintf(_n('%d', '%d', $hour), $hour);
        }

        if($between < 172800 and ( idate('z', $from) + 1 == idate('z', $now) or idate('z', $from) > 2 + idate('z', $now)))
        {
            return _t('.. %s', date('H:i', $from));
        }

        if($between < 604800 and idate('W', $from) == idate('W', $now))
        {
            $day = intval($between / (3600 * 24));
            return sprintf(_n('...', '...', $day), $day);
        }

        if($between < 31622400 and idate('Y', $from) == idate('Y', $now))
        {
            return date(_t('...'), $from);
        }

        return date(_t('...'), $from);
    }

    /**
     * Convert $size to readable format.
     * This determines prefixes for binary multiples according to IEC 60027-2,
     * Second edition, 2000-11, Letter symbols to be used in electrical technology - Part 2: Telecommunications and electronics.
     *
     * @author Mike Cochrane
     * @param $size bytes
     * @return string
     */
    public static function getsize($size)
    {
        $si = array('B', 'KB', 'MB', 'GB', 'TB'); #  'PB', 'EB', 'ZB', 'YB');
        $remainder = $i = 0;
        while($size >= 1024 && $i < 8)
        {
            $remainder = (($size & 0x3ff) + $remainder) / 1024;
            $size = $size >> 10;
            $i++;
        }
        return round($size + $remainder, 2) . ' ' . $si[$i];
    }

    /**
     * Get the variable name as string
     *
     * @author http://us2.php.net/manual/en/language.variables.php#76245
     * @param $var variable as reference
     * @param $scope scope
     * @return string
     */
    public static function vname($var, $scope = false, $prefix = 'unique', $suffix = 'value')
    {
        if($scope)
        {
            $values = $scope;
        }

        $old = $var;
        $var = $new = $prefix . rand() . $suffix;
        $vname = false;

        foreach($values as $key => $val)
        {
            if($val === $new)
            {
                $vname = $key;
            }
        }
        $var = $old;

        return $vname;
    }

    /**
     * format_seconds_to_shortstring
     *
     * @param $seconds int
     * @return string Ouput: 4D 10:12:20
     */
    public static function format_seconds_to_shortstring($seconds = 0)
    {
        $time = '';
        if(isset($seconds))
        {
            $time = sprintf('%dD %02d:%02d:%02dh', $seconds / 60 / 60 / 24, ($seconds / 60 / 60) % 24, ($seconds / 60) % 60, $seconds % 60);
        }
        else
        {
            return '00:00:00';
        }

        return $time;
    }

    /**
     * Performs a chmod operation
     *
     * @param $path
     * @param $chmod
     * @param $recursive
     */
    function chmod($path = '', $chmod = '755', $recursive = 0 )
    {
        if(is_dir($path) == false)
        {
            $file_mode = '0' . $chmod;
            $file_mode = octdec($file_mode);

            if(chmod($path, $file_mode) == false)
            {
                return false;
            }
        }
        else
        {
            $dir_mode_r = '0' . $chmod;
            $dir_mode_r = octdec($dir_mode_r);

            if(chmod($path, $dir_mode_r) == false)
            {
                return false;
            }

            if($recursive == true)
            {
                $dh = opendir($path);
                while($file = readdir($dh))
                {
                    if(mb_substr($file, 0, 1) != '.')
                    {
                        $fullpath = $path . '/' . $file;
                        if(!is_dir($fullpath))
                        {
                            $mode = '0' . $chmod;
                            $mode = octdec($mode);
                            if(chmod($fullpath, $mode) == false)
                            {
                                return false;
                            }
                        }
                        else
                        {
                            if($this->chmod($fullpath, $chmod, true) == false)
                            {
                                return false;
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }
        return true;
    }

    /**
     * Remove comments prefilter
     *
     * @param $html A String with HTML Comments.
     * @return string $html String without Comments.
     */
    function remove_tpl_comments($html )
    {
        return preg_replace('/<!--.*-->/U', '', $html);
    }

    /**
     * Copy a directory recursively
     *
     * @param $source
     * @param $destination
     * @param $overwrite boolean
     */
    function dir_copy($source, $destination, $overwrite = true )
    {
        if($handle = opendir($source))
        {
            while(false !== ( $file = readdir($handle)))
            {
                if(mb_substr($file, 0, 1) != '.')
                {
                    $source_path = $source . $file;
                    $target_path = $destination . $file;

                    if(is_file($target_path) === false or $overwrite)
                    {
                        if(array(mb_strstr($target_path, '.') == true))
                        {
                            $folder_path = dirname($target_path);
                        }
                        else
                        {
                            $folder_path = $target_path;
                        }

                        while(is_dir(dirname(end($folder_path)))
                        and dirname(end($folder_path)) != '/'
                        and dirname(end($folder_path)) != '.'
                        and dirname(end($folder_path)) != ''
                        and ! preg_match('#^[A-Za-z]+\:\\\$#', dirname(end($folder_path))))
                        {
                            array_push($folder_path, dirname(end($folder_path)));
                        }

                        while($parent_folder_path = array_pop($folder_path))
                        {
                            if(is_dir($parent_folder_path) == false and @ mkdir($parent_folder_path, fileperms($parent_folder_path)) == false)
                            {
                                die(_('Could not create the directory that should be copied (destination). Probably a permission problem.'));
                            }
                        }

                        $old = ini_set('error_reporting', 0);
                        if(copy($source_path, $target_path) == false)
                        {
                            die(_('Could not copy the directory. Probably a permission problem.'));
                        }
                        ini_set('error_reporting', $old);
                    }
                    elseif(is_dir($source_path))
                    {
                        if(is_dir($target_path) == false)
                        {
                            if(@mkdir($target_path, fileperms($source_path)) == false
                                );
                        }
                        $this->dir_copy($source_path, $target_path, $overwrite);
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * Delete a directory or it's content recursively
     *
     * @param $directory directory string Name / Path of the Directory to delete.
     * @param $subdirectory boolean subdirectory
     */
    public static function delete_dir_content($directory, $subdirectory = false)
    {
        if(mb_substr($directory, -1) == '/')
        {
            $directory = mb_substr($directory, 0, -1);
        }

        if((is_file($directory) == false) or ( is_dir($directory) == false))
        {
            return false;
        }
        elseif(is_readable($directory))
        {
            # loop over all elements in that directory
            $handle = opendir($directory);
            while(false !== ( $item = readdir($handle)))
            {
                if($item != '.' && $item != '..')
                {
                    # path of that element (dir/file)
                    $path = $directory . '/' . $item;

                    # delete dir
                    if(is_dir($path))
                    {
                        # remove all subdirectries via recursive call
                        $this->delete_dir_content($path, true);
                    }
                    else # delete file
                    {
                        unlink($path);
                    }
                }
            }
            closedir($handle);

            # remove that subdir
            if($subdirectory == true)
            {
                if(rmdir($directory) == false)
                {
                    return false;
                }
            }
        }
        return true;
    }

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
    public static function UTF8_to_HTML($utf8, $encodeTags = false)
    {
        # check if this function was aleady loaded
        if(isset(self::$already_loaded[__FUNCTION__]) === false)
        {
            # if not, load function
            require ROOT_CORE . 'functions' . DS . mb_strtolower(__FUNCTION__) . '.function.php';

            # function loaded successfully
            self::$already_loaded[__FUNCTION__] = true;
        }

        # calling the loaded function
        return UTF8_to_HTML($utf8, $encodeTags);
    }

    /**
     * The Magic Call __callStatic() is triggered when invoking inaccessible methods in a static context.
     * Method overloading.
     * Available from PHP 5.3 onwards.
     *
     * @param $name string The $name argument is the name of the method being called.
     * @param $arguments arra The $arguments argument is an enumerated array containing the parameters passed to the $name'ed method.
     */
    public static function __callStatic($method, $arguments)
    {
        # Because value of $name is case sensitive, its forced to be lowercase.
        $method = mb_strtolower($method);

        # Debug message for Method Overloading
        # Making it easier to see which static method is called magically
        #Clansuite_Debug::fbg('DEBUG (Overloading): Calling static method "'.$method.'" '. implode(', ', $arguments). "\n");
        # construct the filename of the command
        $filename = ROOT_CORE . 'functions' . DS . $method . '.function.php';

        # check if name is valid
        if(is_file($filename) === true and is_readable($filename))
        {
            # dynamically include the command
            include_once $filename;

            return call_user_func_array($method, $arguments);
        }
        else
        {
            trigger_error('Clansuite Function not found: "' . $filename . '".');
        }
    }

    /**
     * The Magic Call __call() is triggered when invoking inaccessible methods in an object context.
     * Method overloading.
     *
     * This method takes care of loading the function command files.
     *
     * This means that a currently non-existing methods or properties of this class are dynamically "created".
     * Overloading methods are always in the "public" scope.
     *
     * @param $name string The $name argument is the name of the method being called.
     * @param $arguments array The $arguments  argument is an enumerated array containing the parameters passed to the $name'ed method.
     */
    public function __call($method, $arguments)
    {
        # Because value of $name is case sensitive, its forced to be lowercase.
        $method = mb_strtolower($method);

        # Debug message for Method Overloading
        # Making it easier to see which method is called magically
        # Clansuite_Debug::fbg('DEBUG (Overloading): Calling object method "'.$method.'" '. implode(', ', $arguments). "\n");
        # construct the filename of the command
        $filename = ROOT_CORE . 'functions' . DS . $method . '.function.php';

        # check if name is valid
        if(is_file($filename) === true and is_readable($filename))
        {
            # dynamically include the command
            include_once $filename;

            return call_user_func_array($method, $arguments);
        }
        else
        {
            trigger_error('Clansuite Function not found: "' . $filename . '".');
        }
    }

}
?>