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
    * @link       http://gna.org/projects/
    *
    * @version    SVN: $Id: news.admin.php 3815 2009-12-09 19:11:23Z vain $
    */

# Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_Module_Statistics_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Statistics
 */
class Clansuite_Module_Statistics_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }
    
    public function action_admin_show()
    {
        $view = $this->getView();

        $view->assign('stuff', '123');

        $this->prepareOutput();
    }

    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=statistics&amp;sub=admin&amp;action=settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'statistic_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=statistics&amp;sub=admin&amp;action=settings_update');

        $settings['statistics'][] = array(    	'id' => 'deleteTimeWho',
                                        	  	'name' => 'deleteTimeWho',
                                        		'label' => 'delete Time',
                                        		'description' => _('Delete time of old database entries for the WhoIsOnline table. Value in days. After x days delete old entries !'),
		                                        'formfieldtype' => 'text',
        		                                'value' => $this->getConfigValue('deleteTimeWho', '1'));

        $settings['statistics'][] = array(    	'id' => 'timoutWho',
                		                        'name' => 'timoutWho',
                        		                'label' => 'Online Timeout',
                                		        'description' => _('Defines the timeout for a user or guest. Value in minutes.'),
                                        		'formfieldtype' => 'text',
                                        		'value' => $this->getConfigValue('timoutWho', '5'));


        # fetch the formgenerator
        require ROOT_CORE . '/viewhelper/formgenerator.core.php';

        # fill the settings array into the formgenerator
        $form = new Clansuite_Array_Formgenerator($settings);
        # add additional buttons to the form
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    public function action_admin_settings_update()
    {
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->getHttpRequest()->getParameter('statistic_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');

        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'statistics'.DS.'statistics.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        #$this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache(WWW_ROOT . '/index.php?mod=statistics&amp;sub=admin&amp;action=settings', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>