<?php
/**
* Usercenter Modul
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
* @license    ???
* @version    SVN: $Id$
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
// Admin Module - Config Class
//----------------------------------------------------------------
class module_admin_usercenter
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    
    //----------------------------------------------------------------
    // First function to run - switches between $_REQUEST['action'] Vars to the functions
    // Loading necessary language files
    //----------------------------------------------------------------
    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t('Control Center - Usercenter' );
        
        switch ($_REQUEST['action'])
        {
            case 'usercenter':
                $this->mod_page_title = $lang->t( 'Show usercenter' );
                $this->show_usercenter();
                break;
         
            default:
                $this->mod_page_title = $lang->t( 'Show usercenter' );
                $this->show_usercenter();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    
    //----------------------------------------------------------------
    // Show all users
    //----------------------------------------------------------------
    function show_usercenter()
    {
        global $db, $tpl, $error, $lang;

                                                                                 // ?
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = 1' );
        //$stmt->execute( array ( $SESSION[user][user_id] ) );
        $stmt->execute();
        $usercenterdata = $stmt->fetchAll(PDO::FETCH_NAMED);
                    
        if ( is_array( $usercenterdata ) )
        {
            $tpl->assign('usercenterdata', $usercenterdata);
        }
        else
        {
        $this->output .= 'There was an error while acquiring the usercenter-data.';
        }
       
        $this->output .= $tpl->fetch('admin/usercenter/usercenter.tpl');
    }
   
    
}
?>