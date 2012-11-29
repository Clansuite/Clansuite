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

namespace Clansuite\Modules\Index\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Index
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Index
 */
class IndexController extends ModuleController //implements Koch\Module\ModuleInterface
{
    public function actionList()
    {
        // Set Pagetitle and Breadcrumbs
        $this->addBreadcrumb( _('List'), '/index/index');

        /***
         * You can set a Render Engine:
         *
         * Therefore you can choose a specific Render Engine (view_type) like
         * 1) Smarty 2) json 3) rss 4) php.
         *
         * If you don't set a Rendering Engine via this method,
         * then 'Smarty' is used as fallback!
         */
        #$this->setRenderEngine('smarty');

        /**
         * Directly assign something to the output
         */
        #$this->output   .= 'This writes directly to the OUTPUT. Action show() was called.';

       /**
        * Usage of method: setTemplate($templatename)
        *
        * 1. you can specify a complete template-filename (including its path)
        * 2. if you NOT use this method,
        * we try to automatically detect the template-file by using module and action as templatename.
        *
        * the template lookup will take place in the following paths:
        *    a) in the activated "layout theme" folder (according to the user-session)
        *        example: usertheme = "standard" and $this->template = 'modulename/filename.tpl';
        *         then lookup of template in /standard/modulename/filename.tpl
        *    b) the modul-directory/templatefolder/rendererfolder/actionname.tpl
        *
        * As a result of this direct connection of URL to TPL, it's possible to
        * code in a very straightforward way:  index.php?mod=something&action=any
        * would result in a template-search in /modules/something/view/any.tpl
        *
        * Even an empty module function would result in an rendering - a good starting point i guess!
        *
        */
        // Direct Path Assignments
        // a) call the template in root_tpl (themefolder) + path
        // This is also automagically called, when no template was set!
        #$view->setTemplate('index/show.tpl');
        // OR
        // b) directly call template in module path
        #$view->setTemplate( APPLICATION_MODULES_PATH . 'index/view/show.tpl' );

        // Starting the View
        #$this->setView($this->getRenderEngine());

        // Applying a Layout Template
        #$view = $this->getView()->setLayoutTemplate('admin/index.tpl');

        // direct output example
        //echo 'Hello World!';

        $this->display();
    }

    public function actionEdit()
    {
        // Set Pagetitle and Breadcrumbs
        $this->addBreadcrumb( _('Show'), '/index/show');

        $view->setTemplate( 'show.tpl' );
        $view = $this->getView();
        $view->addRawContent($smarty->fetch('action_edit.tpl'));

        $this->display();
    }

    public function actionAbout()
    {
        $this->setRenderMode('NOLAYOUT');
        $this->display();
    }

    /**
     * Widget for displaying pieces of information about clansuite
     */
    public function widgetAbout()
    {
        // nothing to assign, it a pure template widget
    }

    public function widgetToolbox()
    {
        // nothing to assign, it a pure template widget
    }
}
