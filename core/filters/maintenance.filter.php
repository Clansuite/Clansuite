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
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Filter - Maintenace Mode
 *
 * Purpose: Display Maintenace Template
 * When config parameter 'maintenance' is set, the maintenance template will be displayed
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 */
class Clansuite_Filter_Maintenance implements Clansuite_Filter_Interface
{
    private $config = null;     # holds instance of config

    public function __construct(Clansuite_Config $config)
    {
        $this->config = $config;      # set instance of config to class
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        /**
         * maintenance mode must be enabled in configuration
         */
        if($this->config['maintenance']['maintenance'] == 1)
        {
            return;
        }

        /**
         * @todo b) create override of maintenance mode, in case it's an admin user?
         */

        # fetch renderer
        $smarty = Clansuite_Renderer_Factory::getRenderer('smarty', Clansuite_CMS::getInjector());

        # fetch maintenance template
        $html = $smarty->fetch(ROOT_THEMES . 'core/view/smarty/maintenance.tpl', true);

        # output
        $response->setContent($html);
        $response->flush();
        exit();
    }
}
?>