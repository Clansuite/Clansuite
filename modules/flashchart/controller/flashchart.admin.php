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
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Flashchart_Admin
 * Open Flash Chart Sample
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Flashchart
 */
class Clansuite_Module_Flashchart_Admin extends Clansuite_Module_Controller implements Clansuite_Module_Interface
{
    public function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        $this->getModuleConfig();
    }

    public function action_admin_show()
    {
        # initialize OFC
        require 'ofc/open_flash_chart_object.php';

        # get an OFC Object
        $flashchart = open_flash_chart_object_str( 500, 250, 'http://'. $_SERVER['SERVER_NAME'] .'/cache/chart-data.php', false );

        $this->getView()->assign('flashchart', $flashchart);
        unset($flashchart);

        $this->prepareOutput();
    }
}
?>