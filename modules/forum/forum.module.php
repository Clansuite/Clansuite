<?php
/**
 * ClanSuite Forum Module (Frontend)
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
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Forum
 */
class Clansuite_Module_Forum extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    /**
     * Main Method of Forum Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {  
        # read module config
        $this->getModuleConfig();
    }     

    /**
     * The action_show method for the Forum module
     * @param void
     * @return void 
     */
    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=forum&amp;action=show');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_show_thread method for the Forum module
     * @param void
     * @return void 
     */
    public function action_show_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show Thread'), '/index.php?mod=forum&amp;action=show_thread');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_show_post method for the Forum module
     * @param void
     * @return void 
     */
    public function action_show_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show Post'), '/index.php?mod=forum&amp;action=show_post');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_create_post method for the Forum module
     * @param void
     * @return void 
     */
    public function action_create_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create Post'), '/index.php?mod=forum&amp;action=create_post');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_create_thread method for the Forum module
     * @param void
     * @return void 
     */
    public function action_create_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create Thread'), '/index.php?mod=forum&amp;action=create_thread');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_delete_post method for the Forum module
     * @param void
     * @return void 
     */
    public function action_delete_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Delete Post'), '/index.php?mod=forum&amp;action=delete_post');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_delete_thread method for the Forum module
     * @param void
     * @return void 
     */
    public function action_delete_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Delete Thread'), '/index.php?mod=forum&amp;action=delete_thread');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_edit_post method for the Forum module
     * @param void
     * @return void 
     */
    public function action_edit_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Edit Post'), '/index.php?mod=forum&amp;action=edit_post');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_edit_thread method for the Forum module
     * @param void
     * @return void 
     */
    public function action_edit_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Edit Thread'), '/index.php?mod=forum&amp;action=edit_thread');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_show_category method for the Forum module
     * @param void
     * @return void 
     */
    public function action_show_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show Category'), '/index.php?mod=forum&amp;action=show_category');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * The action_show_board method for the Forum module
     * @param void
     * @return void 
     */
    public function action_show_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show Board'), '/index.php?mod=forum&amp;action=show_board');

        
        # Prepare the Output
        $this->prepareOutput();
    }


    /**
     * The widget_forum method for the Forum module (widget!)
     * @param void
     * @return void 
     */
    public function widget_forum($item, &$smarty)
    {
                echo $smarty->fetch('forum/widget_forum.tpl');
    }


}

?>