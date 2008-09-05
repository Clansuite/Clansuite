<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch (c) 2005 - onwards
    * Florian Wolf (c) 2006 - onwards
    *
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: $
    */

/**
 * Rights module for ClanSuite Admin Module (Backend)
 * (rights)
 *
 * @license    GPL v2 or later
 * @author     Florian Wolf
 * @link       http://www.clansuite.com
 */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * @package Clansuite
 * @subpackage module_admin_rights
 */
class Module_Rights_Admin extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Rights Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response)
    {  
        # read module config
        $this->config->readConfig( ROOT_MOD . '/rights/rights.config.php');

        # proceed to the requested action
        $this->processActionController($request);
    }     

    /**
     * The action_admin_show method for the Rights module
     * @param void
     * @return void 
     */
    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=rights&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_create method for the Rights module
     * @param void
     * @return void 
     */
    public function action_admin_create()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Create'), '/index.php?mod=rights&amp;action=create');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_update method for the Rights module
     * @param void
     * @return void 
     */
    public function action_admin_update()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Update'), '/index.php?mod=rights&amp;action=update');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_delete method for the Rights module
     * @param void
     * @return void 
     */
    public function action_admin_delete()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Delete'), '/index.php?mod=rights&amp;action=delete');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }


}

?>