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
}
?>