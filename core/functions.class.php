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
                echo $tpl->display( 'redirect/redirect.tpl' );
                exit;
                //die( $tpl->display( 'redirect/redirect.tpl' ) );
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
            if (!in_array($random, $exclude_chars) &&
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
}
?>