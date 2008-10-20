<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * Template Handler Class
    *
    * PHP versions 5.1.4
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
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  2006 Clansuite Group
    * @license    see COPYING.txt
    * @version    SVN: $Id$
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Functions
 *
 * Module:      Core
 * Submodule:   Common Functions
 *
 * @author      Jens-Andre Koch  <vain@clansuite.com>
 * @copyright   Jens-Andre Koch, (2005 - onwards)
 *
 * @package     clansuite
 * @category    core
 * @subpackage  functions
 */
class functions
{
    /**
     * Converts an Object to an Array
     *
     * @param $object object to convert
     * @return array
     */
    static public function object2array($object)
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
    static public function distanceOfTimeInWords($fromTime, $toTime = 0, $showLessThanAMinute = false)
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
    * @desc Delete a directory or it's content recursively
    */
    function delete_dir_content($directory, $sub=false)
    {
    	if(substr($directory,-1) == '/')
    	{
    		$directory = substr($directory,0,-1);
    	}

    	if(!is_file($directory) || !is_dir($directory))
    	{
    		return false;
    	}
        elseif (is_readable($directory))
    	{
    		$handle = opendir($directory);
    		while (false !== ($item = readdir($handle)))
    		{
    			if($item != '.' && $item != '..')
    			{
    				$path = $directory.'/'.$item;
    				if(is_dir($path))
    				{
    					$this->delete_dir_content($path, true);
    				}
                    else
                    {
    					unlink($path);
    				}
    			}
    		}
    		closedir($handle);
    		if($sub == true)
    		{
    			if(!rmdir($directory))
    			{
    				return false;
    			}
    		}
    	}
    	return true;
    }
}
?>