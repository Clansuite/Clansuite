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

namespace Clansuite\application\Modules\Testunit\Controller;

use Clansuite\Application\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Testunit
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Testunit
 */
class TestunitController extends ModuleController
{

    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), 'testunit/show');

        $view = $this->getView();
        $this->display();
    }

    /* -------------------------------------------------------------------------
     *    UNITS
     * ----------------------------------------------------------------------- */

    /**
     * swfUpload Test
     */
    public function action_ajaxswfupload()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('swfupload'), 'testunit/ajaxswfupload');

        $view = $this->getView();
        $view->assign('sess_name', session_name() );
        $view->assign('sess_id', session_id() );
        $this->display();
    }

    /**
     * uploadify Test
     */
    public function action_ajaxuploadify()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('uploadify'), 'testunit/ajaxuploadify');

        $view = $this->getView();
        $this->display();
    }

    /**
     * prettyPhoto Test Video Show (vimeo, youtube, ....)
     */
    public function action_ajaxprettyphoto()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('prettyphoto'), 'testunit/ajaxprettyphoto');

        $view = $this->getView();
        $this->display();
    }

    /**
     * Simple Demo for using XTemplate as Render Engine
     *
     * @todo No Layout, yet! Outputs module content only.
     */
    public function action_xtemplate()
    {
        // fetch xtemplate as renderengine
        $xtpl = $this->getView('xtemplate');

        // this renderengine is a block parser / frontloading one, so initalize it with template
        $xtpl->initializeEngine( $this->getTemplateName() );

        // simple placeholder replace
        $xtpl->assign('PLACEHOLDER_VARIABLE', 'TEST-123-TEST');

        // Debug XTemplate Engine
        #Clansuite_Debug::printR($xtpl);

        // parse the Block main
        $xtpl->parse('main');

        // direct content output = display
        // $xtpl->out('main');

        // indirect content output = fetch
        $content = $xtpl->text('main');

        // push content to the response object
        $this->response->setContent($content);
    }
}
