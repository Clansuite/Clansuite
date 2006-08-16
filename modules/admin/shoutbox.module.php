<?php
/**
* Admin Configs Module Handler Class
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

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Admin Module - Config Class
*/
class module_admin_users
{
    public $output          = '';
    public $mod_page_title  = '';
    public $additional_head = '';
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang;
        
        $this->mod_page_title = $lang->t('Control Center - Usermanagement' );
        
        switch ($_REQUEST['action'])
        {
            case 'usercenter':
                $this->mod_page_title = $lang->t( 'Show usercenter' );
                $this->show_usercenter();
                break;

	        case 'show_all_users':
                $this->mod_page_title = $lang->t( 'Show all users' );
                $this->show_all_users();
                break;
     
            case 'search':
                $this->mod_page_title = $lang->t( 'Advanced User-Search' );
                $this->search();
                break;
     
            default:
                $this->mod_page_title = $lang->t( 'Show all users' );
                $this->show_all_users();
            break;
        }
        
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head );
    }
    
    /**
    * @desc Show all users
    */

    function show_all_users()
    {
        global $db, $tpl, $error, $lang;

       
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users' );
        $stmt->execute( );
        $users = $stmt->fetchAll(PDO::FETCH_NAMED);
                    
        if ( is_array( $users ) )
        {
            $tpl->assign('users', $users);
        }
        else
        {
        $this->output .= 'No Users could be found.';
        }
       
        $this->output .= $tpl->fetch('admin/users/listusers.tpl');
    }
              
    /**
    * @desc Show all users
    */

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
       
        $this->output .= $tpl->fetch('admin/users/usercenter.tpl');
    }
    
    /**
    * @desc Advanced User-Search
    */

    function search()
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
        $this->output .= 'There was an error while acquiring the user-search-data.';
        }
       
        $this->output .= $tpl->fetch('admin/users/search.tpl');
    }
    
    
    
    
}
?>