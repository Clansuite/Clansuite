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
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Downloads_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Downloads
 */
class Clansuite_Module_Downloads_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }
 
    public function action_admin_show()
    {
        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
        $this->display();
    }    
    
    /**
     * Action for displaying the Settings of a Module Downloads
     */
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=downloads&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'downloads_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=downloads&amp;sub=admin&amp;action=settings_update');
                                        
        $settings['downloads'][] = array(    'id' => 'widget_latestdownloads',
                                        'name' => 'widget_latestdownloads',
                                        'description' => _('Items in Latest Downloads Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('widget_latestdownloads', '5'));

        $settings['downloads'][] = array(    'id' => 'widget_topdownloads',
                                        'name' => 'widget_topdownloads',
                                        'description' => _('Items in Top Downloads Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('widget_topdownloads', '5'));

        $settings['downloads'][] = array(    'id' => 'items_resultsPerPage',
                                        'name' => 'items_resultsPerPage',
                                        'description' => _('Items in Top Downloads Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('items_resultsPerPage', '25'));
        
        include ROOT_CORE . '/viewhelper/formgenerator.core.php';
        $form = new Clansuite_Array_Formgenerator($settings);

        # display formgenerator object
        #clansuite_xdebug::printR($form); 
        
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');
        
        # display form html
        #clansuite_xdebug::printR($form->render());
        
        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();       
    }
    
    public function action_admin_settings_update()
    { 
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->getHttpRequest()->getParameter('downloads_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'downloads/downloads.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=downloads&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>