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
    * @version    SVN: $Id: index.module.php 4552 2010-07-05 15:25:44Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Matches_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Matches
 */
class Clansuite_Module_Matches_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        return;
    }
    
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_view_matches');
        
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=gallery&amp;sub=admin&amp;action=show');
        
        #
        
        # Get Render Engine
        $view = $this->getView();        
        
        #$view->assign('news', $news->toArray());
        #$view->assign('newsarchiv', $newsarchiv);
        #$view->assign('newscategories', $newscategories);

        // Return true if it's necessary to paginate or false if not
        #$view->assign('pagination_needed',$pager->haveToPaginate());

        // Pagination
        #$view->assign('pager', $pager);
        #$view->assign('pager_layout', $pager_layout);
        
        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        # specifiy the template manually
        #$this->setTemplate('news/admin_show.tpl');

        $this->display();
       
    }
    
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/index.php?mod=matches&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'matches_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT . 'index.php?mod=matches&amp;sub=admin&amp;action=settings_update');
                                        
        $settings['matches'][] = array(    'id' => 'widget_latestmatches',
                                        'name' => 'widget_latestmatches',
                                        'description' => _('Items Latest Matches Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('widget_latestmatches', '5'));

        $settings['matches'][] = array(    'id' => 'widget_nextmatches',
                                        'name' => 'widget_nextmatches',
                                        'description' => _('Items Next Matches Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('widget_nextmatches', '5'));
        
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
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->request->getParameter('matches_settings');

        # Get Configuration from Injector
        $config = $this->getInjector()->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'matches/matches.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->utility->clearCompiledTemplate();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=matches&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>