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
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module Gallery
 *
 * @license    GPL
 * @author     Florian Wolf
 * @link       http://www.clansuite.com
 * @version    SVN: $Id:$
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Gallery
 */
class Module_Gallery extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Gallery Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . '/gallery/gallery.config.php');
    }

    /**
     * The action_show method for the Gallery module
     * @param void
     * @return void
     */
    public function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('show'), '/index.php?mod=gallery&amp;action=show');


        # Prepare the Output
        $this->prepareOutput();
    }


    /**
     * The widget_gallery method for the Gallery module (widget!)
     * @param void
     * @return void
     */
    public function widget_gallery($item)
    {
    
    }

    /**
     * The widget_gallery method for the Gallery module (widget!)
     * @param void
     * @return void
     */
    public function widget_random_image($item)
    {
        
    }
}
?>