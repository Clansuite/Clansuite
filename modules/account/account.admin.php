<?php
   /**
	* Clansuite - just an eSports CMS
	* Jens-André Koch © 2005 - onwards
	* http://www.clansuite.com/
	*
	* This file is part of "Clansuite - just an eSports CMS".
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
	* @copyright  Jens-André Koch (2005 - onwards)
	*
	* @link       http://www.clansuite.com
	* @link       http://gna.org/projects/clansuite
	*
	* @version    SVN: $Id: account.module.php 2741 2009-01-20 16:35:21Z vain $
	*/

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

 /**
 * Clansuite Module - Account
 *
 * Purpose:
 *
 * @todo registration and usage conditions agreement
 */
class Module_Account_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
	/**
	 * Module_Admin -> Execute
	 */
	public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
	{
		# read module config
		$this->getModuleConfig();
	}

	/**
	 *  Edits the user's Avatar Image
	 */
	function action_admin_editavatar()
	{
		#var_dump($_REQUEST);

		# Set Pagetitle and Breadcrumbs
		Clansuite_Trail::addStep( _('Add Avatar'), '/index.php?mod=users&amp;sub=admin&amp;action=addavatar');

		# Get Render Engine
		$smarty = $this->getView();

		if( is_file( ROOT_UPLOAD . 'images/avatars/avatar'.$_SESSION['user']['email'].'png') )
		{
			$avatar_image = ROOT_UPLOAD . 'images/avatars/avatar'.$_SESSION['user']['email'].'png';
			$smarty->assign('avatar_image', $avatar_image);
		}

		# Set Admin Layout Template
		$smarty->setLayoutTemplate('index.tpl');

		# Prepare the Output
		$this->prepareOutput();
	}

	/**
	 *
	 */
	function action_admin_deleteavatar()
	{
		/**
		 * @todo 1. in general:    permissions to delete
		 * @todo 2. in particular: sessio.id = avatar.id to delete are matching
		 */
		# $user::hasAccess(deleteAvatar);
		# and session.user_id = deleteavatar.id

		#var_dump($_REQUEST);
	}

	function action_admin_userpicture_edit()
	{

	}

	function action_admin_userpicture_remove()
	{

	}

	/**
	 * Usercenter
	 *
	 * Shows own Profil, Messages, Personal Geustbooks, Abonnenments from the Form, Next Events and Matches, Votes etc.
	 */
	function action_admin_usercenter()
	{
		# Set Pagetitle and Breadcrumbs
		Clansuite_Trail::addStep( _('Usercenter'), '/index.php?mod=users&amp;sub=admin&amp;action=usercenter');

		# Get Render Engine
		$smarty = $this->getView();

		/**
		 * Get the user data
		 */
		#$stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
		#$stmt->execute( array( $_SESSION['user']['user_id'] ) );
		#$data = $stmt->fetch(PDO::FETCH_ASSOC);

		#if ( is_array( $data ) )
		#{
			#$smarty->assign( 'usercenterdata', $data );
		#}
		#else
		#{
		   # $functions->redirect( 'index.php?mod=users&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The user could not be found.' ), 'admin' );
		#}

		# Set Admin Layout Template
		$smarty->setLayoutTemplate('index.tpl');

		# Specifiy the template manually
		#$this->setTemplate('admin/users/usercenter.tpl');

		# Prepare the Output
		$this->prepareOutput();
	}

	public function action_admin_usercenter_edit()
	{

	}

	public function action_admin_usercenter_update()
	{

	}
	
    /**
     * Action for displaying the Settings of a Module Account
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=account&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'account_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=account&amp;sub=admin&amp;action=settings_update');
                                        
        #$settings['account'][] = array( 'id' => 'resultsPerPage_show',
        #                                'name' => 'resultsPerPage_show',
        #                                'description' => _('Newsitems to show in Newsmodule'),
        #                                'formfieldtype' => 'text',
        #                                'value' => $this->getConfigValue('resultsPerPage_show', '3'));
       
        require ROOT_CORE . '/viewhelper/formgenerator.core.php';
        $form = new Clansuite_Array_Formgenerator($settings);

        # display formgenerator object
        #clansuite_xdebug::printR($form); 
        
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');
        
        # display form html
        #clansuite_xdebug::printR($form->render());
        
        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();       
    }
    
    function action_admin_settings_update()
    { 
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->getHttpRequest()->getParameter('account_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'account/account.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=account&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>