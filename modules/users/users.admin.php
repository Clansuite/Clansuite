<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: menueditor.module.php 2095 2008-06-11 23:44:20Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Admin Module - Config Class
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Users
*/
class Module_Users_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        parent::initRecords('users');
    }

    function action_admin_banuser()
    {
    }

    function action_admin_removebanuser()
    {

    }

    /**
     * Show all users
     */
    function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        #Clansuite_Trail::addStep( _('Show'), '/index.php?mod=users&amp;sub=admin&amp;action=show');

        # Get Render Engine
        $view = $this->getView();

        // Permissions
        #$perms->check( 'cc_show_users' );

        // Defining initial variables
        // Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('page');
        $resultsPerPage = 25;

        $searchletter = $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('searchletter');

        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/libs/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'u.user_id', 'u.nick', 'u.email', 'u.timestamp', 'u.joined');
        // Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('u.nick', 'asc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('u.user_id, u.nick, u.email, u.joined, u.timestamp')
                                    ->from('CsUsers u')
                                    #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->orderby($sortorder),
                                 # The following is Limit  ?,? =
                                 $currentPage, // Current page of request
                                 $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5  // Displays: [1][2][3][4][5]
                             )),
                             '?mod=users&sub=admin&action=show&page={%page}'
                             );

        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');
        #var_dump($pager_layout);

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Query users
        $users = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        // Get Number of Rows
        #$count = count($users);

        if ( is_array( $users ) )
        {
            $view->assign('users', $users);
            $view->assign('pager', $pager);
            $view->assign('pager_layout', $pager_layout);
        }
        else
        {
            $error['no_users'] = 1;
            $view->assign( 'error', $error );
        }

        # Set Admin Layout Template
        $view->setLayoutTemplate('index.tpl');

        # Specifiy the template manually
        #$this->setTemplate('admin/show.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Create a new user
     *
     */
    function action_admin_create()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create New Useraccount'), '/index.php?mod=users&amp;sub=admin&amp;action=create');

        /**
         * Init
         */
        $submit                     = $_POST['submit'];
        $info                       = $_POST['info'];
        $_POST['info']['groups']    = !isset($_POST['info']['groups']) ? array() : $_POST['info']['groups'];
        $info['activated']          = isset($_POST['info']['activated'])   ? $_POST['info']['activated']   : 0;
        $info['disabled']           = isset($_POST['info']['disabled'])    ? $_POST['info']['disabled']    : 0;
        $sets                       = '';
        $error                      = array();
        $groups                     = array();
        $all_groups                 = array();

        /**
         * Nick or eMail already in ?
         */
        $stmt = $db->prepare( 'SELECT nick,email FROM ' . DB_PREFIX . 'users WHERE nick = ? OR email = ?' );
        $stmt->execute( array( $info['nick'], $info['email'] ) );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if( is_array($user) )
        {
            if( $info['email'] == $user['email'] )
            {
                $error['email_already'] = 1;
            }

            if( $info['nick'] == $user['nick'] )
            {
                $error['nick_already'] = 1;
            }
        }

        /**
         * Get all Groups
         */
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups' );

        $stmt->execute( array () );
        $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /**
         * Checks
         */
        if ( !empty( $submit ) )
        {
            if ( $input->check($info['email'], 'is_email' ) == false )
            {
                $error['email_wrong'] = 1;
            }

            /**
             * Form filled?
             */
            if( ( empty($info['nick']) OR
                empty($info['password']) OR
                empty($info['email']) ) )
            {
                $error['fill_form'] = 1;
            }

            $groups = $info['groups'];
        }


        /**
         * DB Insert
         */
        if ( !empty($submit) AND count($error) == 0 )
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
            $functions->redirect( 'index.php?mod=users&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been created.' ), 'admin' );
        }

        /**
         * Give template and assign error
         */
        $view->assign( 'all_groups'    , $all_groups);
        $view->assign( 'groups'        , $groups );
        $view->assign( 'error'         , $error );

        // specifiy the template manually
        $this->setTemplate('admin_create.tpl');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        // Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Edit a user
     * AJAX
     *
     */
    function action_admin_edit_standard()
    {
        /**
        * Init
        */
        $id                 = isset($_GET['id'])                    ? (int) $_GET['id']             : (int) $_POST['info']['user_id'];
        $submit             = isset($_POST['submit'])               ? $_POST['submit']              : '';
        $info               = isset($_POST['info'])                 ? $_POST['info']                : array();
        $info['activated']  = isset($_POST['info']['activated'])    ? $_POST['info']['activated']   : 0;
        $info['disabled']   = isset($_POST['info']['disabled'])     ? $_POST['info']['disabled']    : 0;
        $profile            = isset($_POST['profile'])              ? $_POST['profile']             : array();
        $error              = array();
        $all_groups         = array();
        $groups             = array();

        // Check id
        if( empty( $id ) )
        {
            $error->show( 'No ID given', 'You have\'nt supplied an ID', 1, 'index.php?mod=controlcenter&amp;sub=users&amp;action=show' );
        }

        /**
        * Groups of the user
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
        * Get all Groups
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
        * Nick or eMail already in ?
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
                    $error->show( 'eMail', 'eMail already existing!', 1, 'index.php?mod=controlcenter&amp;sub=users&amp;action=show' );
                }

                if( $info['nick'] == $user['nick'] )
                {
                    $error->show( 'Nickname', 'Nickname already existing!', 1, 'index.php?mod=controlcenter&amp;sub=users&amp;action=show' );
                }
            }

            /**
            * Check email
            */
            if ( $input->check($info['email'], 'is_email' ) == false )
            {
                $error->show( 'eMail', 'eMail is not valid!', 1, 'index.php?mod=controlcenter&amp;sub=users&amp;action=show' );
            }

            /**
            * Form filled?
            */
            if( empty($info['nick']) OR
                empty($info['email']) )
            {
                $error->show( 'Form', 'Please fill the form!', 1, 'index.php?mod=controlcenter&amp;sub=users&amp;action=show' );
            }

            $groups = $info['groups'];
            $view->assign('user'     , $info);
        }
        else
        {
            /**
            * The user himself
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
                $view->assign('user', $user);
                $view->assign('profile', $profile);
            }
            else
            {
                $error->show( 'No user', 'The user could not be found!', 1, 'index.php?mod=controlcenter&amp;sub=users&amp;action=show' );
            }
        }

        /**
        * Update DB
        */
        if( !empty($submit) AND count($error) == 0 )
        {
            /**
            * Update users table
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
            * Update groups table
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
            $functions->redirect( 'index.php?mod=users&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The user has been edited.' ), 'admin' );
        }

        /**
        * Template output & assignments
        */
        $view->assign( 'all_groups'    , $all_groups);
        $view->assign( 'groups'        , $groups);
        $view->assign( 'error'         , $error );

        # Set Admin Layout Template
        $view->setLayoutTemplate('index.tpl');

        # Specifiy the template manually
        $this->setTemplate('admin_edit.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Advanced User-Search
     *
     */
    function action_admin_search()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Search'), '/index.php?mod=users&amp;sub=admin&amp;action=search');

        # Get Render Engine
        $view = $this->getView();

        // Permissions check
        #$perms->check( 'cc_search_users' );

        /**
         *  Get the users
         */
       /* $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users LEFT JOIN ' . DB_PREFIX . 'profiles ON ' . DB_PREFIX . 'users.user_id = ' . DB_PREFIX . 'profiles.user_id' );
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_NAMED);

        if ( is_array( $users ) )
        {
            $view->assign('users', $users);
        }
        else
        {
            $functions->redirect( 'index.php?mod=users&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'No users could be found.' ), 'admin' );
        }
*/
        # Set Admin Layout Template
        #$view->setLayoutTemplate('index.tpl');

        # Specifiy the template manually
        #$this->setTemplate('admin_search.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Deletes a user
     */
    function action_admin_delete()
    {
        /**
         * Init
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
            $this->redirect( 'index.php?mod=users&sub=admin', 3, _( 'No users selected to delete! Aborted... ' ));
        }

        /**
         * Abort
         */
        if ( !empty( $abort ) )
        {
            $functions->redirect( 'index.php?mod=users&sub=admin' );
        }

        /**
         * Create Select Statement
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
         * Delete the groups
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
                        $this->redirect( 'index.php?mod=users&sub=admin&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)), 'confirm', 3, $lang->t( 'You have selected the following user(s) to delete: ' . $names ), 'admin' );
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
         * Redirect on finish
         */
        $this->redirect( 'index.php?mod=users&sub=admin&action=show_all', 3, _( 'The selected user(s) were deleted.' ));
    }
    
    /**
     * Action for displaying the Settings of a Module Users
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=users&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'users_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=users&amp;sub=admin&amp;action=settings_update');
                                        
        $settings['users'][] = array(   'id' => 'items_lastregisteredusers',
                                        'name' => 'items_lastregisteredusers',
                                        'label' => 'Label',
                                        'description' => _('How many Last Users'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('items_lastregisteredusers', '4'));
       
        require ROOT_CORE . '/viewhelper/formgenerator.core.php';
        $form = new Clansuite_Array_Formgenerator($settings);
        
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');
        
        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();       
    }
    
    function action_admin_settings_update()
    { 
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Update'), '/index.php?mod=users&amp;sub=settings&amp;action=update');

        # Incomming Data
        $data = $this->getHttpRequest()->getParameter('users_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'users/users.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=users&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>