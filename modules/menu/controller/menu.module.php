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
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Menu
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Menu
 */
class Clansuite_Module_Menu extends Clansuite_Module_Controller
{
    /**
     * Module_Menu -> Execute
     */
    public function initializeModule()
    {
        parent::initModel('menu');
    }

    /**
     * module menu action_show()
     *
     */
    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), 'index.php?mod=menu&amp;action=show');

        $this->display();
    }

    /**
     * widget_menu
     *
     * Displayes the menu for the frontpage
     * {load_module name="news" action="widget_menu"}
     *
     * @param void
     * @returns void
     */
    public function widget_menu($item)
    {

    }
}
?>