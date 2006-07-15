<?php
/**
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
* @version    SVN: $Id: functions.class.php 129 2006-06-09 12:09:03Z vain $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/


//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Start functions class
//----------------------------------------------------------------
class functions
{
    public $redirect = '';
    
    //----------------------------------------------------------------
    // Redirection modes
    //----------------------------------------------------------------
    function redirect($url = '', $type = '', $time = 0, $message = '' )
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
                    exit();
                }
                else
                {
                    session_write_close(); 
                    if( preg_match( '/(.*)?(.+)=(.+)/', $url ) )
                    {
                        header('Location: ' . $url.'&'.SID );
                    }
                    else
                    {        
                        header('Location: ' . $url.'?'.SID );
                    }
                }       
                break;
                
            case 'metatag':
                $this->redirect = '<meta http-equiv="refresh" content="' . $time . '; URL=' . $url . '">';
                break;
                
            case 'metatag|newsite':
                $redirect = '<meta http-equiv="refresh" content="' . $time . '; URL=' . $url . '">';
                $tpl->assign( 'redirect', $redirect );
                $tpl->assign( 'css', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_css);
                $tpl->assign( 'message', $message );
                session_write_close();
                echo $tpl->display( 'tools/redirect.tpl' );
                exit;
                break;
                
            case 'confirm':
                $tpl->assign( 'link', $url );
                $tpl->assign( 'css', WWW_ROOT . '/' . $cfg->tpl_folder . '/' . TPL_NAME . '/' . $cfg->std_css);
                $tpl->assign( 'message', $message );
                session_write_close();
                echo $tpl->display( 'tools/confirm.tpl' );                
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
                        header('Location: ' . $url.'&'.SID );
                    }
                    else
                    {        
                        header('Location: ' . $url.'?'.SID );
                    }
                }       
                break;
        }
    }
    
    //----------------------------------------------------------------
    // Get a random string by length and excluded chars
    //----------------------------------------------------------------
    function random_string($str_length, $excluded_chars = array())
    {        
        $string = '';
        while (strlen($string) < $str_length)
        {
            $random=rand(48,122);
            if (!in_array($random, $excluded_chars) &&
            ( ($random >= 50 && $random <= 57)   // ASCII 48->57: numbers 0-9
            | ($random >= 65 && $random <= 90))  // ASCII 65->90: A-Z
            | ($random >= 97 && $random <= 122)  // ASCII 97->122: a-z
            )
            {
                $string.=chr($random);
            }
        }
        return $string;
    }
    
    //----------------------------------------------------------------
    // Try a chmod
    //----------------------------------------------------------------
    function chmod( $path = '', $chmod = '0755', $redirect_url = '/index.php' )
    {
        global $lang;
        
        if ( chmod( $path, $chmod ) )
        {
            $this->redirect( $redirect_url, 'metatag|newsite', 5, $lang->t( 'The folder has now the right permissions (chmod)' ) );
        }
        else
        {
            $this->redirect( $redirect_url, 'metatag|newsite', 5, $lang->t( 'The permissions for the folder could not be set.' ) );
        }
    }
    
    //----------------------------------------------------------------
    // Remove comments prefilter
    //----------------------------------------------------------------
    function remove_tpl_comments( $tpl_source, &$tpl )
    {
        return preg_replace("/<!--.*-->/U",'',$tpl_source);
    }
    
    //----------------------------------------------------------------
    // Copy a directory recursively
    //----------------------------------------------------------------
    function dir_copy( $source, $dest, $overwrite = true, $redirect_url )
    {
        global $lang;

        if($handle = opendir($source))
        {
            while( false !== ($file = readdir($handle)) )
            {
                if($file != '.' && $file != '..' && $file != '.svn')
                {
                    $path = $source . $file;
                    if(is_file($path))
                    {
                        if(!is_file($dest . $file) || $overwrite)
                        {
                            if(!@copy($path, $dest . $file))
                            {
                                $this->redirect( $redirect_url, 'metatag|newsite', 5, $lang->t( 'Could not copy the directory. Probably a permission problem.' ) );
                            }
                        }
                        elseif (is_dir($path))
                        {
                            if(!is_dir($dest . $file))
                            {
                                mkdir($dest . $file);
                            }
                            $this->dir_copy($path, $dest . $file, $overwrite);
                        }
                    }
                }
            }
            closedir($handle);
        }
    }

    //----------------------------------------------------------------
    // Delete a directory or it's content recursively
    //----------------------------------------------------------------    
    function delete_dir_content($directory, $sub=false)
    {
    	if(substr($directory,-1) == '/')
    	{
    		$directory = substr($directory,0,-1);
    	}
    	if(!file_exists($directory) || !is_dir($directory))
    	{
    		return false;
    	}
        elseif (is_readable($directory))
    	{
    		$handle = opendir($directory);
    		while (false !== ($item = readdir($handle)))
    		{
    			if($item != '.' && $item != '..' && $item != '.svn')
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
    				return FALSE;
    			}
    		}
    	}
    	return TRUE;
    }
}
?>