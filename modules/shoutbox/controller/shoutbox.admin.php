<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Shoutbox_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Shoutbox
 */
class Clansuite_Module_Shoutbox_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
    }

    public function action_admin_show()
    {
        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->display();
    }

    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), 'index.php?mod=shoutbox&amp;sub=admin&amp;action=settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'shoutbox_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT . 'index.php?mod=shoutbox&amp;sub=admin&amp;action=settings_update');

        $settings['shoutbox'][] = array(
                                        'id' => 'widget_shoutbox',
                                        'name' => 'widget_shoutbox',
                                        'description' => _('Shoutbox Items'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('widget_shoutbox', '12'));

        $form = new Clansuite_Form($settings);

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
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Update'), 'index.php?mod=shoutbox&amp;sub=settings&amp;action=update');

        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->request->getParameter('shoutbox_settings');

        # Get Configuration from Injector and write Config
        $this->getInjector()->instantiate('Clansuite_Config')->writeModuleConfig($data);

        # clear the cache / compiled tpls
        $this->getView()->clearCache();

        # Redirect
        $this->response->redirectNoCache('/shoutbox/admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>