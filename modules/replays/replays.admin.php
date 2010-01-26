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
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:     Replays
 *
 * @since      File available since Release 0.2
 */
class Module_Replays_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Replays -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }
 
    public function action_admin_show()
    {
        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        
        # Prepare the Output
        $this->prepareOutput();
    }    
	
    /**
     * Action for displaying the Settings of a Module Replays
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=replays&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'replays_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=replays&amp;sub=admin&amp;action=settings_update');
                                        
        $settings['replays'][] = array(    'id' => 'items_resultsPerPage',
                                        'name' => 'items_resultsPerPage',
                                        'description' => _('Replays per Page'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('items_resultsPerPage', '25'));

        $settings['replays'][] = array(    'id' => 'widget_latestreplays',
                                        'name' => 'widget_latestreplays',
                                        'description' => _('Items in Latest Replays Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('widget_latestreplays', '5'));


        
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
        $data = $this->getHttpRequest()->getParameter('replays_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'replays/replays.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=replays&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>