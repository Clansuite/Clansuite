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

namespace Clansuite\Application\Modules\Search\Controller;

use Clansuite\Application\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_News
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Toolbox
 */
class SearchController extends ModuleController
{

    public function _initializeModule()
    {
        // read module config
        $this->getModuleConfig();
    }

    public function action_show()
    {
        $this->display();
    }

    public function action_multisearch()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/search/multisearch');

        #Clansuite_Debug::printR( $_POST );

        $qmod = $this->request->getParameterFromPost('qmod');
        $qmodOut = ucwords($qmod);
        $qfields = $this->request->getParameterFromPost('qfields');
        $qfieldsOut = str_replace( ',', ', ', $qfields);
        $qstring = trim($this->request->getParameterFromPost('q'));
        $qstringOut = $qstring;

        // Get Render Engine
        $view = $this->getView();

        $view->assign('qstring', $qstringOut);
        $view->assign('qmod', $qmodOut);
        $view->assign('qfields', $qfieldsOut);

        $this->display();
    }

    public function widget_multisearch()
    {
    }

}
