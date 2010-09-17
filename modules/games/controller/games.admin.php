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
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Games_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Games
 */
class Clansuite_Module_Games_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        parent::initModel('games');
    }

    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=games&amp;sub=admin&amp;action=show');
        
        # Fetch Data and Assign to View
        $this->getView()->assign('games', Doctrine::getTable('CsGames')->fetchAll());

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        $this->display();
    }
    
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=games&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'games_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=games&amp;sub=admin&amp;action=settings_update');
                                                                                                                     
        $settings['games'][] = array(    'id' => 'games_resultsPerPage',
                                        'name' => 'games_resultsPerPage',
                                        'description' => _('Games per Page'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('games_resultsPerPage', '25'));
        
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
        $data = $this->request->getParameter('games_settings');

        # Get Configuration from Injector
        $config = $this->getInjector()->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'games/games.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=games&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>