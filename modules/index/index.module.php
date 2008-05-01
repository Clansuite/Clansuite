<?php
/**
 * Index Module
 *
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
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @license    GNU/GPL, see COPYING.txt
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$Date$)
 *
 * @link       http://www.clansuite.com
 * @link       http://gna.org/projects/clansuite
 * @since      File available since Release 0.2
 *
 * @version    SVN: $Id$
 */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      Index
 *
 * Purpose: This class is the PageController which has many pages to deal with.
 */
class Module_Index extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Main Method of Index Module
     *
     * Sets up module specific stuff, needed by all actions of the module
     * Calls the requested Action $_REQUEST['action']
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }   

    /**
     * Show the Index / Entrance -> welcome message etc.
     */
    function action_show()
    {
        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=index&amp;action=show');

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
        * would result in a template-search in /modules/something/templates/any.tpl
        *
        * Even an empty module function would result in an rendering - a good starting point i guess!
        *
        */
        # Direct Path Assignments
        # a) call the template in root_tpl (themefolder) + path
        # This is also automagically called, when no template was set!
        #$this->setTemplate('index/show.tpl');
        # OR
        # b) directly call template in module path
        #$this->setTemplate( ROOT_MOD . '/index/templates/show.tpl' );

        # Starting the View
        #$this->setView($this->getRenderEngine());

        # Applying a Layout Template
        #$this->getView()->setLayoutTemplate('admin/index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>