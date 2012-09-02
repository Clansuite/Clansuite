<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
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

namespace Clansuite\Application\Modules\Cronjobs\Controller;

use Clansuite\Application\Core\Mvc\ModuleController;

/**
 * Clansuite Module CronjobsAdminController
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Cronjobs
 */
class CronjobsAdminController extends ModuleController
{
    public function _initializeModule()
    {

    }

    public function action_admin_list()
    {
        // Applying a Layout Template
        $view = $this->getView()->setLayoutTemplate('index.tpl');

        $cronjobs = array();

        $this->getView()->assign('cronjobs', $cronjobs);

        $this->display();
    }
}
