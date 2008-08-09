<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: menueditor.module.php 2248 2008-07-12 01:48:54Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Module_Creator
 * Submodule:    Admin
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package clansuite
 * @subpackage module_admin
 * @category modules
 */
class Module_Modulecreator_Admin extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Module_Creator -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . '/modulecreator/modulecreator.config.php');
        
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * Shows the Admin Module Editor
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=module_editor&amp;sub=admin&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        // Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Create the new mod
     */
    public function action_admin_create()
    {
        # Permission check
        #$perms::check('cc_update_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Create'), '/index.php?mod=modulecreator&amp;sub=admin&amp;action=create');

        $request = $this->injector->instantiate('httprequest');
        $mod = $request->getParameter('m');
        
        $smarty = $this->getView();
        
        $smarty->assign( 'm', $mod );

        #$smarty->autoload_filters = array();
        #$smarty->unregister_prefilter('smarty_prefilter_inserttplnames');
        error_reporting(0);
        
        // Include & Instantiate GeSHi
        require_once( ROOT_LIBRARIES . 'geshi/geshi.php' );
        
        $smarty->assign( 'frontend_methods', $smarty->fetch('module_frontend_method.tpl') );
        $smarty->assign( 'frontend', geshi_highlight($smarty->fetch('module_frontend.tpl'),'php-brief', '',true ) );
        
        $smarty->assign( 'backend_methods', $smarty->fetch('module_backend_method.tpl') );
        $smarty->assign( 'backend', geshi_highlight($smarty->fetch('module_backend.tpl'),'php-brief', '',true ) );
        
        
        $smarty->assign( 'config', geshi_highlight($smarty->fetch('module_config.tpl'),'php-brief', '',true ) );
        
        error_reporting( E_ALL || E_STRICT );
        #$smarty->register_prefilter('smarty_prefilter_inserttplnames');
        
        /**
        * @desc Folder's writeable?
        */
        if ( !is_writeable( ROOT_MOD ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
                
        $this->prepareOutput();
    }
}
?>