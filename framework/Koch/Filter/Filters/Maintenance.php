<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Filter;

use Koch\MVC\HttpRequestInterface;
use Koch\MVC\HttpResponseInterface;

/**
 * Koch Framework - Filter for displaying a maintenace mode screen.
 *
 * Purpose: Display Maintenace Template
 * When config parameter 'maintenance' is set, the maintenance template will be displayed
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class maintenance implements FilterInterface
{
    private $config = null;     // holds instance of config

    public function __construct(Koch\Config $config)
    {
        $this->config = $config;      // set instance of config to class
    }

    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        /**
         * maintenance mode must be enabled in configuration
         */
        if ($this->config['maintenance']['maintenance'] == 1) {
            return;
        }

        /**
         * @todo b) create override of maintenance mode, in case it's an admin user?
         */

        // fetch renderer
        $smarty = Koch_Renderer_Factory::getRenderer('smarty', Clansuite_CMS::getInjector());

        // fetch maintenance template
        $html = $smarty->fetch(ROOT_THEMES . 'core/view/smarty/maintenance.tpl', true);

        // output
        $response->setContent($html);
        $response->flush();

        exit();
    }
}
