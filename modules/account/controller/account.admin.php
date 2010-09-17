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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: account.module.php 2741 2009-01-20 16:35:21Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Account_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Account
 */
class Clansuite_Module_Account_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        $this->getModuleConfig();
    }

    public function action_admin_avatar_edit()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Add Avatar'), '/index.php?mod=users&sub=admin&action=addavatar');

        # Get Render Engine
        $view = $this->getView();

        $md5_email = md5($_SESSION['user']['email']);
        $avatar_image = '';

        if( is_file( ROOT_UPLOAD . 'images/avatars/avatar'.$md5_email.'png') )
        {
            $avatar_image = ROOT_UPLOAD . 'images/avatars/avatar'.$md5_email.'png';
        }
        else
        {
            $avatar_image = ROOT_UPLOAD . 'images/avatars/no_avatar.png';
        }

        $view->assign('avatar_image', $avatar_image);

        $this->display();
    }

    public function action_admin_avatar_delete()
    {

    }

    public function action_admin_userpicture_edit()
    {

    }

    public function action_admin_userpicture_remove()
    {

    }

    /**
     * Usercenter
     *
     * Shows own Profil, Messages, Personal Geustbooks, Abonnenments from the Form, Next Events and Matches, Votes etc.
     */
    public function action_admin_usercenter()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Usercenter'), '/index.php?mod=users&amp;sub=admin&amp;action=usercenter');

        # Get Render Engine
        $view = $this->getView();

        # Get the user data
        #SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
        #$_SESSION['user']['user_id']

        #$view->assign( 'usercenterdata', $data );

        # Set Admin Layout Template
        $view->setLayoutTemplate('index.tpl');

        $this->display();
    }

    public function action_admin_usercenter_edit()
    {

    }

    public function action_admin_usercenter_update()
    {

    }

    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=account&amp;sub=admin&amp;action=settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'account_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=account&amp;sub=admin&amp;action=settings_update');

        #$settings['account'][] = array( 'id' => 'resultsPerPage_show',
        #                                'name' => 'resultsPerPage_show',
        #                                'description' => _('Newsitems to show in Newsmodule'),
        #                                'formfieldtype' => 'text',
        #                                'value' => $this->getConfigValue('resultsPerPage_show', '3'));

        $form = new Clansuite_Array_Formgenerator($settings);

        # display formgenerator object
        #Clansuite_Debug::printR($form);

        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        # display form html
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function action_admin_settings_update()
    {
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->getHttpRequest()->getParameter('account_settings');

        # get configuration object and write config
        $this->getClansuiteConfig()->confighandler->writeConfig( ROOT_MOD . 'account/account.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=account&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>