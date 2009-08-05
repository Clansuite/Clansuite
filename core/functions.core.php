<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Functions
 *
 * Module:      Core
 * Submodule:   Common Functions
 *
 * @author      Florian Wolf <xsign.dll@clansuite.com>
 * @copyright   Florian Wolf, (2005 - 2007)
 * @author      Jens-Andr� Koch  <vain@clansuite.com>
 * @copyright   Jens-Andr� Koch, (2005 - onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Functions
 */
class Clansuite_Functions
{
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
     public static function generateSecureUUID() {

        $pr_bits = null;
        $fp = @fopen('/dev/urandom','rb');
        if ($fp !== false)
        {
            $pr_bits .= @fread($fp, 16);
            @fclose($fp);
        }
        else
        {
            # If /dev/urandom isn't available (eg: in non-unix systems), use mt_rand().
            $pr_bits = "";
            for($cnt=0; $cnt < 16; $cnt++)
            {
                $pr_bits .= chr(mt_rand(0, 255));
            }
        }

        $time_low = bin2hex(substr($pr_bits,0, 4));
        $time_mid = bin2hex(substr($pr_bits,4, 2));
        $time_hi_and_version = bin2hex(substr($pr_bits,6, 2));
        $clock_seq_hi_and_reserved = bin2hex(substr($pr_bits,8, 2));
        $node = bin2hex(substr($pr_bits,10, 6));

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
     * Calculates the size of an Directory (recursiv)
     */
    public static function dirsize($dir)
    {
       if (!is_dir($dir))
       {
           return false;
       }

       $size = 0;
       $dh = opendir($dir);
       while(($entry = readdir($dh)) !== false)
       {
          # exclude ./..
          if($entry == "." or $entry == "..")
          {
             continue;
          }

          if(is_dir( $dir . "/" . $entry))
          {
             $size += dirsize($dir . "/" . $entry);
          }
          else
          {
            $size += filesize($dir . "/" . $entry);
          }
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
        if (is_object($object))
        {
            $array = array();
            foreach (get_object_vars($object) as $key => $value)
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
     * cut_string_backwards
     *
     * haystack = abc_def
     * needle = _def
     * result = abc
     *
     * Strip String ModuleName at "_admin", example: guestbook_admin
     * $moduleName = strstr($moduleName, '_Admin', true);     # php6
     */
    public static function cut_string_backwards($haystack, $needle)
    {
        $needle_length = strlen($needle);

        if(($i = strpos($haystack,$needle) !== false))
        {
            return substr($haystack, 0, -$needle_length);
        }
        return $haystack;
    }

    /**
     * Converts a SimpleXML String recursivly to an Array
     *
	 * @author : Jason Sheets <jsheets at shadonet dot com>
	 * @param unknown_type $xml
	 * @return Array
	 */
    public static function SimpleXMLToArray($simplexml)
    {
    	$array = array();

    	if ($simplexml)
    	{
    		foreach ($simplexml as $k => $v)
    		{
    			if ($simplexml['list'])
    			{
    				$array[] = self::SimpleXMLToArray($v);
    			}
    			else
    			{
    				$array[$k] = self::SimpleXMLToArray($v);
    			}
    		}
    	}

    	if (sizeof($array) > 0)
    	{
    		return $array;
    	}
    	else
    	{
            #@todo this returns a string? should it be an array?
    		return (string)$simplexml;
    	}
    }

    public static function str_replace_count($search,$replace,$subject,$times)
    {
        $subject_original=$subject;
        $len=strlen($search);
        $pos=0;
        for ($i=1;$i<=$times;$i++) {
        $pos=strpos($subject,$search,$pos);
        if($pos!==false)
        {
            $subject=substr($subject_original,0,$pos);
            $subject.=$replace;
            $subject.=substr($subject_original,$pos+$len);
            $subject_original=$subject;
        }
        else
        {
            break;
        }
        }
        return($subject);
    }

   /**
     * Takes a needle and multi-dimensional haystack array and does a search on it's values.
     *
     * @param    string        $string        Needle to find
     * @param    array        $array        Haystack to look through
     * @result    array                    Returns the elements that the $string was found in
     *
     * array_values_recursive
     */
    static function array_find_element_by_key($key, $array)
    {
        if (array_key_exists($key, $array))
        {
            $result= $array[$key];
            return $result;
        }

        foreach ($array as $k => $v)
        {
            if (is_array($v))
            {
                $result = self::array_find_element_by_key($key, $array[$k]);

                if ($result)
                {
                    return $result;
                }
            }
        }
        return false;
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

        if ( $distanceInMinutes <= 1 )
        {
            if ( !$showLessThanAMinute )
            {
                return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
            }
            else
            {
                if ( $distanceInSeconds < 5  ) { return 'less than 5 seconds';  }
                if ( $distanceInSeconds < 10 ) { return 'less than 10 seconds'; }
                if ( $distanceInSeconds < 20 ) { return 'less than 20 seconds'; }
                if ( $distanceInSeconds < 40 ) { return 'about half a minute';  }
                if ( $distanceInSeconds < 60 ) { return 'less than a minute';   }
                return '1 minute';
            }
        }
        if ( $distanceInMinutes < 45 )      { return $distanceInMinutes . ' minutes';  }
        if ( $distanceInMinutes < 90 )      { return 'about 1 hour'; }
        if ( $distanceInMinutes < 1440 )    { return 'about ' . round(floatval($distanceInMinutes) / 60.0) . ' hours'; }
        if ( $distanceInMinutes < 2880 )    { return '1 day'; }
        if ( $distanceInMinutes < 43200 )   { return 'about ' . round(floatval($distanceInMinutes) / 1440) . ' days';  }
        if ( $distanceInMinutes < 86400 )   { return 'about 1 month';    }
        if ( $distanceInMinutes < 525600 )  { return round(floatval($distanceInMinutes) / 43200) . ' months';}
        if ( $distanceInMinutes < 1051199 ) { return 'about 1 year'; }

        return 'over ' . round(floatval($distanceInMinutes) / 525600) . ' years';
    }

    /**
     * Convert $size to readable format
     *
     * @author Mike Cochrane
     * @param $size bytes
     *
     * @return string
     */
    public static function getsize($size)
    {
        $si = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $remainder = $i = 0;
        while ($size >= 1024 && $i < 8)
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
     *
     * @return string
     */
    public static function vname(&$var, $scope=false, $prefix='unique', $suffix='value')
      {
        if($scope) $vals = $scope;
        else      $vals = $GLOBALS;
        $old = $var;
        $var = $new = $prefix.rand().$suffix;
        $vname = FALSE;
        foreach($vals as $key => $val) {
          if($val === $new) $vname = $key;
        }
        $var = $old;
        return $vname;
      }

    /**
     * format_seconds_to_shortstring
     *
     * Ouput: 4D 10:12:20
     */
    public static function format_seconds_to_shortstring($seconds = 0)
    {
      if(isset($seconds))
      {
        $time = sprintf("%dD %02d:%02d:%02dh", $seconds/60/60/24, ($seconds/60/60)%24, ($seconds/60)%60, $seconds%60);
      }
      else
      {
        return "00:00:00";
      }
      return $time;
    }

    /**
     *  Redirection modes
     */
    function redirect($url = '', $type = '', $time = 0, $message = '', $use_tpl = 'user', $heading = '' )
    {
        global $session, $tpl, $cfg;

        switch ($type)
        {
            case 'header':
                $c = parse_url($url);
                if( array_key_exists('host', $c) OR isset($_COOKIE[$session->session_name]) )
                {
                    session_write_close();
                    header('Location: ' . $url );
                }
                else
                {
                    session_write_close();
                    if( preg_match( '/(.*)?(.+)=(.+)/', $url ) )
                    {
                        header('Location: ' . $url.'&'.$session->session_name.'='.session_id() );
                    }
                    else
                    {
                        header('Location: ' . $url.'?'.$session->session_name.'='.session_id() );
                    }
                }
                exit();
                break;

            case 'metatag':
                $c = parse_url($url);
                if( array_key_exists('host', $c) )
                {
                    $this->redirect = '<meta http-equiv="refresh" content="' . $time . '; URL=' . $url . '" />';
                }
                else
                {
                    if( !preg_match( '#^\/(.*)$#', $url ) )
                    {
                        $url = '/'. $url;
                    }

                    if ( !isset($_COOKIE[$session->session_name]) )
                    {
                        if( preg_match( '/(.*)?(.+)=(.+)/', $url ) )
                        {
                            $url = $url.'&'.$session->session_name.'='.session_id();
                        }
                        else
                        {
                            $url = $url.'?'.$session->session_name.'='.session_id();
                        }
                    }

                    $this->redirect = '<meta http-equiv="refresh" content="' . $time . '; URL=' . WWW_ROOT . $url . '" />';
                }
                break;

            case 'metatag|newsite':
                $c = parse_url($url);
                if( !array_key_exists('host', $c) )
                {
                    if( !preg_match( '#^\/(.*)$#', $url ) )
                    {
                        $url = '/'. $url;
                    }
                    $url = WWW_ROOT . $url;

                    if ( !isset($_COOKIE[$session->session_name]) )
                    {
                        if( preg_match( '/(.*)?(.+)=(.+)/', $url ) )
                        {
                            $url = $url.'&'.$session->session_name.'='.session_id();
                        }
                        else
                        {
                            $url = $url.'?'.$session->session_name.'='.session_id();
                        }
                    }
                }
                $redirect = '<meta http-equiv="refresh" content="' . $time . '; URL=' . $url . '" />';
                $tpl->assign( 'redirect', $redirect );

                $tpl->assign( 'message', $message );
                session_write_close();
                if ( $use_tpl == 'admin' )
                {
                    $tpl->assign( 'css', WWW_ROOT . '/templates/core/admin/admin.css');
                    $tpl->display( 'admin/tools/redirect.tpl' );
                }
                else
                {
                    $tpl->assign( 'css', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . $_SESSION['user']['theme'] . '/' . $cfg->std_css);
                    $tpl->display( 'tools/redirect.tpl' );
                }
                exit;
                break;

            case 'confirm':
                $c = parse_url($url);
                if( !array_key_exists('host', $c) )
                {
                    if( !preg_match( '#^\/(.*)$#', $url ) )
                    {
                        $url = '/'. $url;
                    }
                    $url = WWW_ROOT . $url;

                    if ( !isset($_COOKIE[$session->session_name]) )
                    {
                        if( preg_match( '/(.*)?(.+)=(.+)/', $url ) )
                        {
                            $url = $url.'&'.$session->session_name.'='.session_id();
                        }
                        else
                        {
                            $url = $url.'?'.$session->session_name.'='.session_id();
                        }
                    }
                }
                $tpl->assign( 'link', $url );
                $tpl->assign( 'message', $message );
                $tpl->assign( 'heading', $heading );
                session_write_close();
                if ( $use_tpl == 'admin' )
                {
                    $tpl->assign( 'css', WWW_ROOT . '/templates/core/admin/admin.css');
                    $tpl->display( 'admin/tools/confirm.tpl' );
                }
                else
                {
                    $tpl->assign( 'css', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . $_SESSION['user']['theme'] . '/' . $cfg->std_css);
                    $tpl->display( 'tools/confirm.tpl' );
                }
                exit;
                break;



            default:
                $c = parse_url($url);
                if( array_key_exists('host', $c) OR isset($_COOKIE[$session->session_name]) )
                {
                    session_write_close();
                    header('Location: ' . $url );
                    exit();
                }
                else
                {
                    session_write_close();
                    if( preg_match( '/(.*)?(.+)=(.+)/', $url ) )
                    {
                        header('Location: ' . $url.'&'.$session->session_name.'='.session_id() );
                    }
                    else
                    {
                        header('Location: ' . $url.'?'.$session->session_name.'='.session_id() );
                    }
                }
                break;
        }
    }

    /**
    * @desc Try a chmod
    */

    function chmod( $path = '', $chmod = '755', $recursive = 0 )
    {
        if (!is_dir($path))
        {
            $file_mode = '0'.$chmod;
            $file_mode = octdec($file_mode);
            if( !chmod($path, $file_mode ) )
            {
                return false;
            }
        }
        else
        {
            $dir_mode_r = '0'.$chmod;
            $dir_mode_r = octdec($dir_mode_r);
            if (!chmod($path, $dir_mode_r))
            {
                return false;
            }

            if ( $recursive == 1 )
            {
                $dh = opendir($path);
                while ($file = readdir($dh))
                {
                    if (substr($file,0,1) != '.')
                    {
                        $fullpath = $path.'/'.$file;
                        if(!is_dir($fullpath))
                        {
                            $mode = '0'.$chmod;
                            $mode = octdec($mode);
                            if (!chmod($fullpath, $mode))
                            {
                                return false;
                            }
                        }
                        else
                        {
                            if ( !$this->chmod($fullpath, $chmod, 1) )
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
    * @desc Remove comments prefilter
    */

    function remove_tpl_comments( $tpl_source, &$tpl )
    {
        return preg_replace("/<!--.*-->/U",'',$tpl_source);
    }

    /**
    * @desc Copy a directory recursively
    */

    function dir_copy( $source, $dest, $overwrite = true, $redirect_url )
    {
        global $lang;

        if($handle = opendir($source))
        {
            while( false !== ($file = readdir($handle)) )
            {
                if (substr($file,0,1) != '.')
                {
                    $path = $source . $file;

                    if(!is_file($dest . $file) || $overwrite)
                    {
						$folder_path = array( strstr($dest.$file, '.') ? dirname($dest.$file) : $dest.$file );

						while(is_dir(dirname(end($folder_path)))
						       && dirname(end($folder_path)) != '/'
						       && dirname(end($folder_path)) != '.'
						       && dirname(end($folder_path)) != ''
						       && !preg_match( '#^[A-Za-z]+\:\\\$#', dirname(end($folder_path)) ) )
						{
							array_push($folder_path, dirname(end($folder_path)));
						}

						while($parent_folder_path = array_pop($folder_path))
						{
						    if(!is_dir($parent_folder_path) && !@mkdir($parent_folder_path))
						    	$this->redirect( $redirect_url, 'metatag|newsite', 3, $lang->t( 'Could not create the directory that should be copied (destination). Probably a permission problem.' ) );
						}

                        $old = ini_set("error_reporting", 0);
                        if(!copy($path, $dest . $file))
                        {
                            $this->redirect( $redirect_url, 'metatag|newsite', 3, $lang->t( 'Could not copy the directory. Probably a permission problem.' ) );
                        }
                        ini_set("error_reporting", $old);
                    }
                    elseif (is_dir($path))
                    {
                        if(!is_dir($dest . $file))
                        {
                            if(!@mkdir($dest . $file));
                        }
                        $this->dir_copy($path, $dest . $file, $overwrite);
                    }

                }
            }
            closedir($handle);
        }
    }

    /**
     * Delete a directory or it's content recursively
     *
     * @param directory string Name / Path of the Directory to delete.
     * @param sub
     */
    public static function delete_dir_content($directory, $subdirectory = false)
    {
    	if(substr($directory,-1) == '/')
    	{
    		$directory = substr($directory,0,-1);
    	}

    	if( (is_file($directory) == false) or (is_dir($directory) == false) )
    	{
    		return false;
    	}
        elseif (is_readable($directory))
    	{
    		# loop over all elements in that directory
    		$handle = opendir($directory);
    		while (false !== ($item = readdir($handle)))
    		{
    			if($item != '.' && $item != '..')
    			{
    			    # path of that element (dir/file)
    				$path = $directory.'/'.$item;

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
     * The Magic Call __call() is triggered when invoking inaccessible methods in an object context.
     * Method overloading.
     *
     * @param $name string The $name argument is the name of the method being called.
     * @param $arguments array The $arguments  argument is an enumerated array containing the parameters passed to the $name'ed method.
     */
    public function __call($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        # Clansuite_xdebug::printr("Debug Display: Calling object method '$name' ". implode(', ', $arguments). "\n");
    }

    /**
     * The Magic Call __callStatic() is triggered when invoking inaccessible methods in a static context.
     * Method overloading.
     * Available from PHP 5.3 onwards.
     *
     * @param $name string The $name argument is the name of the method being called.
     * @param $arguments arra The $arguments argument is an enumerated array containing the parameters passed to the $name'ed method.
     */
    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        # Clansuite_xdebug::printr("Debug Display: Calling static method '$name' ". implode(', ', $arguments). "\n");
    }
}
?>