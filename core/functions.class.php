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
    function redirect($url = '', $type = '', $time = 0 )
    {
        global $session;
        
        $type = split('[|]', $type);
        switch ($type[0])
        {
            case 'header':
                if( $type[1] != 'relative' OR isset($_COOKIE[$session->session_name]) )
                {
                    session_write_close();
                    header('Location: ' . $url );
                    exit();
                }
                else
                {
                    session_write_close(); 
                    if( preg_match( '/(.*)?(.*)=(.*)/', $url ) )
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
                
            default:
                header('Location: ' . $url );
                break;
        }
    }
}
?>