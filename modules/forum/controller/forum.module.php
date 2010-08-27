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
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Forum
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Forum
 */
class Clansuite_Module_Forum extends Clansuite_Module_Controller
{
    public function initializeModule()
    {  
        $this->getModuleConfig();
    }     

    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/index.php?mod=forum&amp;action=show');

        
        # Prepare the Output
        $this->display();
    }

    public function action_show_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Thread'), '/index.php?mod=forum&amp;action=show_thread');

        $this->display();
    }

    public function action_show_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Post'), '/index.php?mod=forum&amp;action=show_post');

        
        # Prepare the Output
        $this->display();
    }

    public function action_create_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create Post'), '/index.php?mod=forum&amp;action=create_post');

        
        # Prepare the Output
        $this->display();
    }

    public function action_create_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create Thread'), '/index.php?mod=forum&amp;action=create_thread');

        $this->display();
    }

    public function action_delete_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Delete Post'), '/index.php?mod=forum&amp;action=delete_post');
        
        $this->display();
    }

    public function action_delete_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Delete Thread'), '/index.php?mod=forum&amp;action=delete_thread');

        $this->display();
    }

    public function action_edit_post()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Post'), '/index.php?mod=forum&amp;action=edit_post');

        $this->display();
    }

    public function action_edit_thread()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Thread'), '/index.php?mod=forum&amp;action=edit_thread');

        $this->display();
    }

    public function action_show_category()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Category'), '/index.php?mod=forum&amp;action=show_category');

        $this->display();
    }

    public function action_show_board()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show Board'), '/index.php?mod=forum&amp;action=show_board');

        $this->display();
    }

    public function widget_forum($item, &$smarty)
    {
        echo $smarty->fetch('forum/widget_forum.tpl');
    }
}
?>