<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: menueditor.module.php 2095 2008-06-11 23:44:20Z vain $
    */

//Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Users_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Users
*/
class Clansuite_Module_Users_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        parent::initModel('users');
    }

    public function action_admin_banuser()
    {
    }

    public function action_admin_removebanuser()
    {

    }

    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=users&amp;sub=admin&amp;action=show');

        # Get Render Engine
        $view = $this->getView();

        $currentPage = $this->getHttpRequest()->getParameterFromGet('page');
        $resultsPerPage = 25;

        $searchletter = $this->getHttpRequest()->getParameter('searchletter');

        // SmartyColumnSort -- Easy sorting of html table columns.
        include ROOT_LIBRARIES . '/smarty/libs/SmartyColumnSort.class.php');
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

        $this->prepareOutput();
    }

    /**
     * Create a new user
     *
     */
    public function action_admin_create()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create New Useraccount'), '/index.php?mod=users&amp;sub=admin&amp;action=create');

        // specifiy the template manually
        $this->setTemplate('admin_create.tpl');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        // Prepare the Output
        $this->prepareOutput();
    }

    public function action_admin_edit_standard()
    {
        $view = $this->getView();

        $this->prepareOutput();
    }

    /**
     * Advanced User-Search
     *
     */
    public function action_admin_search()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Search'), '/index.php?mod=users&amp;sub=admin&amp;action=search');

        $view = $this->getView();

        $this->prepareOutput();
    }

    public function action_admin_delete()
    {

        if ( count($delete) < 1 )
        {
            $this->redirect( 'index.php?mod=users&sub=admin', 3, _( 'No users selected to delete! Aborted... ' ));
        }

        /**
         * Abort
         */
        if ( empty( $abort ) == false )
        {
            $this->redirect( 'index.php?mod=users&sub=admin' );
        }


        # Delete User Query

        $this->redirect( 'index.php?mod=users&sub=admin&action=show_all', 3, _( 'The selected user(s) were deleted.' ));
    }
    
    /**
     * Action for displaying the Settings of a Module Users
     */
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=users&amp;sub=admin&amp;action=settings');
        
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
       
        include ROOT_CORE . '/viewhelper/formgenerator.core.php';
        $form = new Clansuite_Array_Formgenerator($settings);
        
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');
        
        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();       
    }
    
    public function action_admin_settings_update()
    { 
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Update'), '/index.php?mod=users&amp;sub=settings&amp;action=update');

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