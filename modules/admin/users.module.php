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
    public $suppress_wrapper= '';
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang;
             
        // Titelzeile zusammensetzen
        $this->mod_page_title = $lang->t('Control Center - Usermanagement') . ' &raquo; ';     
             
        switch ($_REQUEST['action'])
        {
            case 'show_all_users':
                $this->mod_page_title .= $lang->t( 'Show all users' );
                $this->show_all_users();
                break;
            
            case 'usercenter':
                $this->mod_page_title .= $lang->t( 'User-Center' );
                $this->show_usercenter();
                break;

	        case 'create':
                $this->mod_page_title .= $lang->t( 'Create New Useraccount' );
                $this->create();
                break;
     
            case 'edit':
                $this->mod_page_title .= $lang->t( 'Edit User' );
                $this->edit();
                break;
     
            case 'search':
                $this->mod_page_title .= $lang->t( 'Search' );
                $this->search();
                break;

            case 'delete':
                $this->delete();
                break;
                     
            default:
                $this->mod_page_title .= $lang->t( 'Show all users' );
                $this->show_all_users();
            break;
        }
       
        return array( 'OUTPUT'          => $this->output,
                      'MOD_PAGE_TITLE'  => $this->mod_page_title,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
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
       
        $this->output .= $tpl->fetch('admin/users/show.tpl');
    }
    
    /**
    * @desc Create new User
    */

    function create()
    {
        global $db, $tpl, $error, $lang, $functions, $input, $security;
       
        /**
        * @desc Init
        */
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $sets       = '';      
        $err        = array();
               
        /**
        * @desc Nick or eMail already in ?
        */
        $stmt = $db->prepare( 'SELECT nick,email FROM ' . DB_PREFIX . 'users WHERE nick = ? OR email = ?' );
        $stmt->execute( array( $info['nick'], $info['email'] ) );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if( is_array($user) )
        {
            if( $info['email'] == $user['email'] )
            {
                $err['email_already'] = 1;
            }
            
            if( $info['nick'] == $user['nick'] )
            {
                $err['nick_already'] = 1;
            }
        }
        
        /**
        * @desc Form filled?
        */
        if( ( empty($info['nick']) OR 
            empty($info['password']) OR 
            empty($info['email']) ) AND !empty($submit) )
        {
            $err['fill_form'] = 1;   
        }
        
        
        /**
        * @desc DB Insert
        */
        if ( !empty($submit) AND count($err) == 0 )
        {
            $hash = $security->db_salted_hash($info['password']);
            $sets =  'nick = ?, first_name = ?, last_name = ?, email = ?, infotext = ?, activated = ?, disabled = ?,password = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'users SET ' . $sets );
            $stmt->execute( array ( $info['nick'],
                                    $info['first_name'],
                                    $info['last_name'], 
                                    $info['email'],
                                    $info['infotext'],
                                    $info['activated'],
                                    $info['disabled'],
                                    $hash ) );
            $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been created.' ), 'admin' );
        }     
        
        /**
        * @desc Give template and assign error
        */
        $tpl->assign( 'err', $err );
        $this->output .= $tpl->fetch( 'admin/users/create.tpl' );
    }
    
    /**
    * @desc Edit User
    */

    function edit()
    {
        global $db, $tpl, $error, $lang, $input, $security, $functions;
        
        /**
        * @desc Init
        */
        $user_id    = (int) $_GET['user_id'];
        $submit     = $_POST['submit'];
        $info       = $_POST['info'];
        $err        = array();
               
        /**
        * @desc Nick or eMail already in ?
        */
        $stmt = $db->prepare( 'SELECT nick,email FROM ' . DB_PREFIX . 'users WHERE ( nick = ? OR email = ? ) AND user_id != ?' );
        $stmt->execute( array( $info['nick'], $info['email'], $info['user_id'] ) );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if( is_array($user) )
        {
            if( $info['email'] == $user['email'] )
            {
                $err['email_already'] = 1;
            }
            
            if( $info['nick'] == $user['nick'] )
            {
                $err['nick_already'] = 1;
            }
        }
        
        /**
        * @desc Form filled?
        */
        if( empty($info['nick']) OR 
            empty($info['password']) OR 
            empty($info['email']) )
        {
            $err['fill_form'] = 1;   
        }
        
        /**
        * @desc Update DB
        */
        if( !empty($submit) AND count($err) == 0 )
        {
            $sets =  'nick = ?, first_name = ?, last_name = ?, email = ?, infotext = ?, activated = ?, disabled = ?';
            if( $info['password'] != '' )
            {
                $hash = $security->db_salted_hash($info['password']);
                $sets .=  ',password = ?';
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET ' . $sets . ' WHERE user_id = ?' );
                $stmt->execute( array ( $info['nick'],
                                        $info['first_name'],
                                        $info['last_name'], 
                                        $info['email'],
                                        $info['infotext'],
                                        $info['activated'],
                                        $info['disabled'],
                                        $hash,
                                        $info['user_id'] ) );
                $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been edited and a new password has been set.' ), 'admin' );
            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET ' . $sets . ' WHERE user_id = ?' );
                $stmt->execute( array ( $info['nick'],
                                        $info['first_name'],
                                        $info['last_name'], 
                                        $info['email'],
                                        $info['infotext'],
                                        $info['activated'],
                                        $info['disabled'],
                                        $info['user_id'] ) );                
                $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been edited.' ), 'admin' );
            }            
            

        }
              
        /**
        * @desc The user himself
        */
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
        $stmt->execute( array ( $user_id ) );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                      
        if ( is_array( $user ) ) 
        { 
            $tpl->assign('user', $user); 
        }
        else 
        { 
            $this->output .= $lang->t( 'The user could not be found.');  
        }
        
        
        /**
        * @desc Groups of the user
        */
        $stmt = $db->prepare( 'SELECT ug.* 
                               FROM ' . DB_PREFIX . 'user_group cu,
                                    ' . DB_PREFIX . 'groups ug
                               WHERE ug.group_id = cu.group_id
                               AND cu.user_id = ?' );
                            
        $stmt->execute( array ( $user_id ) );
        $groups = $stmt->fetchAll(PDO::FETCH_NAMED);
        
        if ( is_array( $groups ) ) 
        { 
            $tpl->assign('groups', $groups); 
        }
        else 
        { 
            $this->output .= 'The Userprofil of User #' . $user_id . ' could not be fetched.';  
        }

        /**
        * @desc Tempalte output & assignments
        */
        $tpl->assign( 'err', $err );
        $this->output .= $tpl->fetch( 'admin/users/edit.tpl' );  
       
    }
              
    /**
    * @desc Usercenter - Shows own Profil, Messages, Next Events, Votes etc.
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
       
        $this->output .= $tpl->fetch('admin/users/search.tpl');
    }
    
    /**
    * @desc Delete the users
    */
    function delete()
    {
        global $db, $functions, $input, $lang;
        
        /**
        * @desc Init     
        */
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $abort      = $_POST['abort'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
        
        if ( count($delete) < 1 )
        { 
            $functions->redirect( 'index.php?mod=admin&sub=users', 'metatag|newsite', 3, $lang->t( 'No users selected to delete! Aborted... ' ), 'admin' );
        }
        
        /**
        * @desc Abort
        */
        if ( !empty( $abort ) )
        {
            $functions->redirect( 'index.php?mod=admin&sub=users' );
        }
        
        /**
        * @desc Create Select Statement
        */
        $select = 'SELECT user_id, nick FROM ' . DB_PREFIX . 'users WHERE ';
        foreach ( $delete as $key => $id )
        {
            $select .= 'user_id = ' . $id . ' OR ';
        }
        $select .= 'user_id = -1000';
        $stmt = $db->prepare( $select );
        $stmt->execute();
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
        {
            if( in_array( $result['user_id'], $delete  ) )
            {
                $names .= '<br /><b>' .  $result['nick'] . '</b>';
            }
            $all_users[] = $result;
        }       
        
        /**
        * @desc Delete the groups
        */
        foreach( $all_users as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['user_id'], $ids ) )
                {
                    $d = in_array( $value['user_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=admin&sub=users&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following user(s) to delete: ' . $names ), 'admin' );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
                            $stmt->execute( array($value['user_id']) );
                        }
                    }
                }
            }
        }
        
        /**
        * @desc Redirect on finish
        */
        $functions->redirect( 'index.php?mod=admin&sub=groups&action=show_all', 'metatag|newsite', 3, $lang->t( 'The user(s) have been delete.' ), 'admin' );
        
    }
   
}
?>