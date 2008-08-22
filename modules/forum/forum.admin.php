<?php
/**
 * ClanSuite Forum Admin Module (Backend)
 * (forum)
 *
 * @license    GPL
 * @author     Florian Wolf
 * @link       http://www.clansuite.com
 * @version    SVN: $Id: $
 */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * An example class, this is grouped with
 * other classes in the "sample" package and
 * is part of "classes" subpackage
 * @package Clansuite
 * @subpackage module_admin_forum
 */
class Module_Forum_Admin extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Forum Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response)
    {  
        # read module config
        $this->config->readConfig( ROOT_MOD . '/forum/forum.config.php');

        # proceed to the requested action
        $this->processActionController($request);
    }     

    /**
     * The action_admin_show method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('show'), '/index.php?mod=forum&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_create_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_create_category()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('create_category'), '/index.php?mod=forum&amp;action=create_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_create_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_create_board()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('create_board'), '/index.php?mod=forum&amp;action=create_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_edit_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_edit_category()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('edit_category'), '/index.php?mod=forum&amp;action=edit_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_edit_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_edit_board()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('edit_board'), '/index.php?mod=forum&amp;action=edit_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_delete_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_delete_category()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('delete_category'), '/index.php?mod=forum&amp;action=delete_category');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_admin_delete_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_admin_delete_board()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('delete_board'), '/index.php?mod=forum&amp;action=delete_board');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
                
        # Prepare the Output
        $this->prepareOutput();
    }


}

?>