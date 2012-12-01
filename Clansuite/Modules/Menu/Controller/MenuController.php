<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-AndrÃ© Koch Â© 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
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

namespace Clansuite\Modules\Menu\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Menu
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Menu
 */
class MenuController extends ModuleController
{
    /**
     * Module_Menu -> Execute
     */
    public function _initializeModule()
    {
    }

    /**
     * widgetMenu
     *
     * Displayes the menu for the frontpage
     * {load_module name="news" action="widgetMenu"}
     *
     * @param void
     * @returns void
     */
    public function widgetMenu($item)
    {

    }
}
