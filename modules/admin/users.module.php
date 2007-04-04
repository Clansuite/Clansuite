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
    public $additional_head = '';
    public $suppress_wrapper= '';

    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loading necessary language files
    */

    function auto_run()
    {
        global $lang, $trail;

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('Admin'), '/index.php?mod=admin');
        $trail->addStep($lang->t('Users'), '/index.php?mod=admin&amp;sub=users');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=admin&amp;sub=users&amp;action=show');
                $this->show();
                break;

            case 'usercenter':
                $trail->addStep($lang->t('Usercenter'), '/index.php?mod=admin&amp;sub=users&amp;action=usercenter');
                $this->show_usercenter();
                break;

	        case 'create':
                $trail->addStep($lang->t('Create New Useraccount'), '/index.php?mod=admin&amp;sub=users&amp;action=create');
                $this->create();
                break;

            case 'edit_standard':
                $this->edit_standard();
                break;

            case 'edit_general':
                $this->edit_general();
                break;

            case 'edit_computer':
                $this->edit_computer();
                break;

            case 'edit_guestbook':
                $this->edit_guestbook();
                break;

            case 'search':
                $trail->addStep($lang->t('Search'), '/index.php?mod=admin&amp;sub=users');
                $this->search();
                break;

            case 'delete':
               $this->delete();
                break;

        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
    }

    /**
    * @desc Show all users
    */

    function show()
    {
        global $db, $tpl, $error, $lang;

        // Smarty Pagination load and init
        require( ROOT_CORE . '/smarty/SmartyPaginate.class.php');

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=admin&sub=users&amp;action=show');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(20);


        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_CORE . '/smarty/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'user_id','email', 'nick', 'joined');
        // Create the columnsort object
        $columnsort = &new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('nick', 'asc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        // Query users
        $stmt = $db->prepare( 'SELECT user_id, nick, email, joined FROM ' . DB_PREFIX . 'users ORDER BY ' . $sortorder . ' LIMIT ?,?' );
        $stmt->bindParam(1, $SmartyPaginate->getCurrentIndex(), PDO::PARAM_INT );
        $stmt->bindParam(2, $SmartyPaginate->getLimit(), PDO::PARAM_INT );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'users');
        $rows->execute();
        $count = $rows->fetch(PDO::FETCH_NUM);

        if ( is_array( $users ) )
        {
            $tpl->assign('users', $users);
        }
        else
        {
            $err['no_users'] = 1;
        }

        // Finally: assign total number of rows to SmartyPaginate
        $SmartyPaginate->setTotal($count[0]);
        // assign the {$paginate} to $tpl (smarty var)
        $SmartyPaginate->assign($tpl);

        $tpl->assign( 'err', $err );
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
        $submit                     = $_POST['submit'];
        $info                       = $_POST['info'];
        $_POST['info']['groups']    = !isset($_POST['info']['groups']) ? array() : $_POST['info']['groups'];
        $info['activated']          = isset($_POST['info']['activated'])   ? $_POST['info']['activated']   : 0;
        $info['disabled']           = isset($_POST['info']['disabled'])    ? $_POST['info']['disabled']    : 0;
        $sets                       = '';
        $err                        = array();
        $groups                     = array();
        $all_groups                 = array();

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
        * @desc Get all Groups
        */
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups' );

        $stmt->execute( array () );
        $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /**
        * @desc Checks
        */
        if ( !empty( $submit ) )
        {
            if ( $input->check($info['email'], 'is_email' ) == false )
            {
                $err['email_wrong'] = 1;
            }

            /**
            * @desc Form filled?
            */
            if( ( empty($info['nick']) OR
                empty($info['password']) OR
                empty($info['email']) ) )
            {
                $err['fill_form'] = 1;
            }

            $groups = $info['groups'];
        }


        /**
        * @desc DB Insert
        */
        if ( !empty($submit) AND count($err) == 0 )
        {
            $hash = $security->db_salted_hash($info['password']);
            $sets =  'nick = ?, email = ?, activated = ?, disabled = ?, joined = ?, password = ?';
            $stmt = $db->prepare( 'INSERT ' . DB_PREFIX . 'users SET ' . $sets );
            $stmt->execute( array ( $info['nick'],
                                    $info['email'],
                                    $info['activated'],
                                    $info['disabled'],
                                    time(),
                                    $hash ) );

            $stmt2 = $db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'users WHERE nick = ?' );
            $stmt2->execute( array( $info['nick'] ) );
            $result = $stmt2->fetch(PDO::FETCH_ASSOC);
            $info['user_id'] = $result['user_id'];

            $stmt4 = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'user_groups WHERE user_id = ?' );
            $stmt4->execute( array( $info['user_id'] ) );

            if ( count( $info['groups'] ) > 0 )
            {
                $stmt3 = $db->prepare( 'INSERT ' . DB_PREFIX . 'user_groups SET user_id = ?, group_id = ?' );
                foreach( $info['groups'] as $id )
                {
                    $stmt3->execute( array ( $info['user_id'],
                                            $id ) );
                }
            }
            $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been created.' ), 'admin' );
        }

        /**
        * @desc Give template and assign error
        */
        $tpl->assign( 'all_groups'  , $all_groups);
        $tpl->assign( 'groups'      , $groups );
        $tpl->assign( 'err'         , $err );
        $this->output .= $tpl->fetch( 'admin/users/create.tpl' );
        $this->suppress_wrapper = 1;
    }

    /**
    * @desc Edit User
    */

    function edit_standard()
    {
        global $db, $tpl, $error, $lang, $input, $security, $functions;

        /**
        * @desc Init
        */
        $id                 = isset($_GET['id'])                    ? (int) $_GET['id']             : (int) $_POST['info']['user_id'];
        $submit             = isset($_POST['submit'])               ? $_POST['submit']              : '';
        $info               = isset($_POST['info'])                 ? $_POST['info']                : array();
        $info['activated']  = isset($_POST['info']['activated'])    ? $_POST['info']['activated']   : 0;
        $info['disabled']   = isset($_POST['info']['disabled'])     ? $_POST['info']['disabled']    : 0;
        $profile            = isset($_POST['profile'])              ? $_POST['profile']             : array();
        $err                = array();
        $all_groups         = array();
        $groups             = array();

        // Check id
        if( empty( $id ) )
        {
            $error->show( 'No ID given', 'You have\'nt supplied an ID', 1, 'index.php?mod=admin&amp;sub=users&amp;action=show' );
        }

        /**
        * @desc Groups of the user
        */
        $stmt = $db->prepare( 'SELECT ug.group_id
                               FROM ' . DB_PREFIX . 'user_groups cu,
                                    ' . DB_PREFIX . 'groups ug
                               WHERE ug.group_id = cu.group_id
                               AND cu.user_id = ?' );

        $stmt->execute( array ( $id ) );
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
        {
            $groups[] = $result['group_id'];
        }

        /**
        * @desc Get all Groups
        */
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups' );

        $stmt->execute();
        $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Clean out profile stuff (if not existing)
        $stmt = $db->prepare('SELECT user_id FROM ' . DB_PREFIX . 'profiles_general WHERE user_id = ?');
        $stmt->execute( array( $id ) );
        $profile_result = $stmt->fetch(PDO::FETCH_NUM);

        if( !is_array( $profile_result ) )
        {
            $stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'profiles_general SET user_id = ?, timestamp = ?');
            $stmt->execute( array( $id, time() ) );
        }

        /**
        * @desc Nick or eMail already in ?
        */
        if ( !empty( $submit ) )
        {
            $stmt = $db->prepare( 'SELECT nick,email,user_id FROM ' . DB_PREFIX . 'users WHERE ( user_id != ? ) AND ( nick = ? OR email = ? )' );
            $stmt->execute( array( $info['nick'], $info['email'], $info['user_id'] ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if( is_array($user) )
            {
                if( $info['email'] == $user['email'] )
                {
                    $error->show( 'eMail', 'eMail already existing!', 1, 'index.php?mod=admin&amp;sub=users&amp;action=show' );
                }

                if( $info['nick'] == $user['nick'] )
                {
                    $error->show( 'Nickname', 'Nickname already existing!', 1, 'index.php?mod=admin&amp;sub=users&amp;action=show' );
                }
            }

            /**
            * @desc Check email
            */
            if ( $input->check($info['email'], 'is_email' ) == false )
            {
                $error->show( 'eMail', 'eMail is not valid!', 1, 'index.php?mod=admin&amp;sub=users&amp;action=show' );
            }

            /**
            * @desc Form filled?
            */
            if( empty($info['nick']) OR
                empty($info['email']) )
            {
                $error->show( 'Form', 'Please fill the form!', 1, 'index.php?mod=admin&amp;sub=users&amp;action=show' );
            }

            $groups = $info['groups'];
            $tpl->assign('user'     , $info);
        }
        else
        {
            /**
            * @desc The user himself
            */
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array ( $id ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get thte profile
            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX .'profiles_general WHERE user_id = ?');
            $stmt->execute( array($id) );
            $profile = $stmt->fetch(PDO::FETCH_NAMED);
            unset($profile['profile_id']);
            unset($profile['user_id']);

            if ( is_array( $user ) )
            {
                $tpl->assign('user', $user);
                $tpl->assign('profile', $profile);
            }
            else
            {
                $error->show( 'No user', 'The user could not be found!', 1, 'index.php?mod=admin&amp;sub=users&amp;action=show' );
            }
        }

        /**
        * @desc Update DB
        */
        if( !empty($submit) AND count($err) == 0 )
        {
            /**
            * @desc Update users table
            */
            $sets =  'nick = ?, email = ?, activated = ?, disabled = ?';
            if( $info['password'] != '' )
            {
                $hash = $security->db_salted_hash($info['password']);
                $sets .=  ',password = ?';
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET ' . $sets . ' WHERE user_id = ?' );
                $stmt->execute( array ( $info['nick'],
                                        $info['email'],
                                        $info['activated'],
                                        $info['disabled'],
                                        $hash,
                                        $info['user_id'] ) );
            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'users SET ' . $sets . ' WHERE user_id = ?' );
                $stmt->execute( array ( $info['nick'],
                                        $info['email'],
                                        $info['activated'],
                                        $info['disabled'],
                                        $info['user_id'] ) );
            }

            /**
            * @desc Update groups table
            */
            $stmt2 = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'user_groups WHERE user_id = ?' );
            $stmt2->execute( array ( $info['user_id'] ) );

            if ( count( $info['groups'] ) > 0 )
            {
                $stmt3 = $db->prepare( 'INSERT ' . DB_PREFIX . 'user_groups SET user_id = ?, group_id = ?' );
                foreach( $info['groups'] as $id )
                {
                    $stmt3->execute( array ( $info['user_id'],
                                            $id ) );
                }
            }
            $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been edited.' ), 'admin' );
        }


        /**
        * @desc Template output & assignments
        */
        $tpl->assign( 'all_groups'  , $all_groups);
        $tpl->assign( 'groups'      , $groups);
        $tpl->assign( 'err'         , $err );
        $this->output .= $tpl->fetch( 'admin/users/edit.tpl' );
        $this->suppress_wrapper = 1;

    }

    /**
    * @desc Usercenter - Shows own Profil, Messages, Next Events, Votes etc.
    */

    function show_usercenter()
    {
        global $db, $tpl, $error, $lang, $functions;

        /**
        * @desc Get the user data
        */
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
        $stmt->execute( array( $_SESSION['user']['user_id'] ) );
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( is_array( $data ) )
        {
            $tpl->assign( 'usercenterdata', $data );
        }
        else
        {
            $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'The user could not be found.' ), 'admin' );
        }

        $this->output .= $tpl->fetch('admin/users/usercenter.tpl');
    }

    /**
    * @desc Advanced User-Search
    */
    function search()
    {
        global $db, $tpl, $error, $lang;

        /**
        * @desc Get the users
        */

        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users LEFT JOIN ' . DB_PREFIX . 'profiles ON ' . DB_PREFIX . 'users.user_id = ' . DB_PREFIX . 'profiles.user_id' );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_NAMED);

        if ( is_array( $users ) )
        {
            $tpl->assign('users', $users);
        }
        else
        {
            $functions->redirect( 'index.php?mod=admin&sub=users&action=show', 'metatag|newsite', 3, $lang->t( 'No users could be found.' ), 'admin' );
        }

        $this->output .= $tpl->fetch('admin/users/search.tpl');
    }

    /**
    * Deletes a user with questioning
    *
    * @global $db
    * @global $lang
    * @global $functions
    * @global $input
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
        // code by xsign
        // @todo explain reason for settings this: [OR user_id = -1000]
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
        $functions->redirect( 'index.php?mod=admin&sub=users&action=show_all', 'metatag|newsite', 3, $lang->t( 'The selected user(s) were deleted.' ), 'admin' );

    }

}
?>