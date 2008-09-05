<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * Florian Wolf © 2006 - onwards
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
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-onwards)
    * 
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Module:       Module_Creator
 * Submodule:    Admin
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-onwards)
 *
 * @package clansuite
 * @subpackage modulemanager
 * @category modules
 */
class Module_Modulemanager_Admin extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Module_Manager -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # read module config
        $this->config->readConfig( ROOT_MOD . '/Modulemanager/Modulemanager.config.php');
        
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * Show the modulemanager
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=show');
        
        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        $view = $this->getView();
        
        // Init vars
        $modules = array();
        
        $mod_glob = glob( ROOT . 'modules' . DS . '*', GLOB_ONLYDIR );
        foreach( $mod_glob as $mod )
        {
            $modules[] = array(
                'name' => ucfirst(str_replace( ROOT . 'modules' . DS ,'', $mod))
            );
        }
        
        $view->assign('modules', $modules);
        

        // Prepare the Output
        $this->prepareOutput();
    }
    
    /**
     * Install new modules
     */
    public function action_admin_install()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=install_new');
        
        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
        

        // Prepare the Output
        $this->prepareOutput();
    }
    
    /**
     * Export a module
     */
    public function action_admin_export()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Export'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=export');
        
        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        
        

        // Prepare the Output
        $this->prepareOutput();
    }

    
    //
    // Module creator + methods below
    //
    
    
    /**
     * Shows the mod creator
     */
    public function action_admin_creator()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        trail::addStep( _('Creator'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=creator');

        $smarty = $this->getView();
        
        // Get all modules in the directory
        // I know... MVC -.- but we need to get going...
        $existing_modules_js = '[';
        $existing_glob = glob( ROOT_MOD . '[a-zA-Z]*', GLOB_ONLYDIR);
        foreach( $existing_glob as $key => $value )
        {
            $existing_modules_js .= '"' . str_replace(strtolower(ROOT_MOD), '', strtolower($value)) . '",';
        }
        $existing_modules_js = preg_replace( '#,$#', ']', $existing_modules_js);
        $smarty->assign('existing_modules_js', $existing_modules_js);
        
        // Set Layout Template
        $smarty->setLayoutTemplate('admin/index.tpl');

        // Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Preview the new mod
     * AJAX
     */
    public function action_admin_preview()
    {
        # Permission check
        #$perms::check('cc_update_menueditor');

        # Set Pagetitle and Breadcrumbs
        # trail::addStep( _('Create'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=create');

        $request = $this->injector->instantiate('httprequest');
        $mod = $request->getParameter('m');
        
        $smarty = $this->getView();
        
        
        
        
        $mod['data'] = base64_encode(serialize($mod));
        $smarty->assign( 'mod', $mod );

        #$smarty->autoload_filters = array();
        #$smarty->unregister_prefilter('smarty_prefilter_inserttplnames');
        error_reporting(0);
        
        // Include & Instantiate GeSHi
        require_once( ROOT_LIBRARIES . 'geshi/geshi.php' );
        
        // FRONTEND
        if( isset($mod['frontend']['checked']) && $mod['frontend']['checked'] == 1)
        {
            // WIDGETS
            if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
            {
                $widget_methods = $smarty->fetch('module_widget_method.tpl');
                $smarty->assign( 'widget_methods',  $widget_methods);
            }
            
            $frontend_methods = $smarty->fetch('module_frontend_method.tpl');
            $smarty->assign( 'frontend_methods',  $frontend_methods);
       
            $frontend = $smarty->fetch('module_frontend.tpl');
            $smarty->assign( 'frontend', geshi_highlight($frontend,'php-brief', '',true ) );
        }
        
        // BACKEND
        if( isset($mod['backend']['checked']) && $mod['backend']['checked'] == 1)
        {
            $backend_methods = $smarty->fetch('module_backend_method.tpl');
            $smarty->assign( 'backend_methods',  $backend_methods );
            
            $backend = $smarty->fetch('module_backend.tpl');
            $smarty->assign( 'backend', geshi_highlight( $backend ,'php-brief', '',true ) );
        }
        
        // CONFIG
        if( isset($mod['config']['checked']) && $mod['config']['checked'] == 1)
        {
            $config = $smarty->fetch('module_config.tpl');
            $smarty->assign( 'config', geshi_highlight($config,'php-brief', '',true ) );
        }
        
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
        //$this->getView()->setLayoutTemplate('admin/index.tpl');
        $this->getView()->setRenderMode('NOT_WRAPPED');
                
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
        trail::addStep( _('Create'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=create');

        $request = $this->injector->instantiate('httprequest');
        $mod = unserialize(base64_decode($request->getParameter('mod_data')));
        
        $smarty = $this->getView();
        $smarty->assign( 'mod', $mod );
        
        /**
        * @desc Folder's writeable?
        */
        if ( !is_writeable( ROOT_MOD ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }
        
        // CREATE DIRECTORIES
        mkdir( ROOT_MOD .  $mod['module_name'] );
        mkdir( ROOT_MOD .  $mod['module_name'] . DS . 'templates' );
        
        // FRONTEND
        if( isset($mod['frontend']['checked']) && $mod['frontend']['checked'] == 1)
        {
            // WIDGETS
            if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
            {
                $widget_methods = $smarty->fetch('module_widget_method.tpl');
                $smarty->assign( 'widget_methods',  $widget_methods);
            }
            
            $frontend_methods = $smarty->fetch('module_frontend_method.tpl');
            $smarty->assign( 'frontend_methods',  $frontend_methods);       
            $frontend = $smarty->fetch('module_frontend.tpl');
            file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . $mod['module_name'] . '.module.php', $frontend );
        }
        
        // BACKEND
        if( isset($mod['backend']['checked']) && $mod['backend']['checked'] == 1)
        {
            $backend_methods = $smarty->fetch('module_backend_method.tpl');
            $smarty->assign( 'backend_methods',  $backend_methods );
            $backend = $smarty->fetch('module_backend.tpl');
            file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . $mod['module_name'] . '.admin.php', $backend );
        }
        
        // CONFIG
        // config is always needed
        $config = $smarty->fetch('module_config.tpl');
        file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . $mod['module_name'] . '.config.php' , $config);

        // Templates
        foreach( $mod['frontend']['frontend_methods'] as $key => $value )
        {
            if( isset($mod['frontend']['frontend_tpls'][$key]) )
            {
                file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . 'templates' . DS . $value . '.tpl', '');
            }
        }

        foreach( $mod['backend']['backend_methods'] as $key => $value )
        {
            if( isset($mod['backend']['backend_tpls'][$key]) )
            {
                file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . 'templates' . DS . $value . '.tpl', '');
            }
        }
        
        foreach( $mod['widget']['widget_methods'] as $key => $value )
        {
            if( isset($mod['widget']['widget_tpls'][$key]) )
            {
                file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . 'templates' . DS . $value . '.tpl', '');
            }
        }        
        
        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
                
        $this->prepareOutput();
    }
}
?>