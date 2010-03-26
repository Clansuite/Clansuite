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

# Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_Module_Forum
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Forum
 */
class Clansuite_Module_Forum extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {  
        $this->getModuleConfig();
    }     

    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=forum&amp;action=show');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_show_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Thread'), '/index.php?mod=forum&amp;action=show_thread');

        $this->prepareOutput();
    }

    public function action_show_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Post'), '/index.php?mod=forum&amp;action=show_post');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_create_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create Post'), '/index.php?mod=forum&amp;action=create_post');

        
        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_create_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create Thread'), '/index.php?mod=forum&amp;action=create_thread');

        $this->prepareOutput();
    }

    public function action_delete_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Delete Post'), '/index.php?mod=forum&amp;action=delete_post');
        
        $this->prepareOutput();
    }

    public function action_delete_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Delete Thread'), '/index.php?mod=forum&amp;action=delete_thread');

        $this->prepareOutput();
    }

    public function action_edit_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Post'), '/index.php?mod=forum&amp;action=edit_post');

        $this->prepareOutput();
    }

    public function action_edit_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Thread'), '/index.php?mod=forum&amp;action=edit_thread');

        $this->prepareOutput();
    }

    public function action_show_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Category'), '/index.php?mod=forum&amp;action=show_category');

        $this->prepareOutput();
    }

    public function action_show_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Board'), '/index.php?mod=forum&amp;action=show_board');

        $this->prepareOutput();
    }

    public function widget_forum($item, &$smarty)
    {
        echo $smarty->fetch('forum/widget_forum.tpl');
    }
}
?>