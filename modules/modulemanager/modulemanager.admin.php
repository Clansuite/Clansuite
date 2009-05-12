<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
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
 * Clansuite Administration Module - Modulemanager
 *
 * Description: Administration for the Modules
 *
 * @version    0.1
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Florian Wolf (2006-2008), 
 * @author     Jens-André Koch <vain@clansuite.com>
 * @license    GPL v2 any later version
 * @link       -
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Modulemanager
 */
/**
 * Module:       Module_Creator
 * Submodule:    Admin
 *
 * @author     
 * @copyright  
 *

 */
class Module_Modulemanager_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Module_Manager -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # read module config
        $this->getModuleConfig( ROOT_MOD . '/modulemanager/modulemanager.config.php');
    }

    /**
     * Show the modulemanager
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=show');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        // Init vars
        $modules = array();

        // Scan all modules
        $module_glob = glob( ROOT . 'modules' . DS . '*', GLOB_ONLYDIR );
        foreach( $module_glob as $module_path )
        {
            echo $module_path;

            #$modulename_by_dirname = str_replace( ROOT . 'modules' . DS ,'', $mod);

            #$modules[$modulename_by_dirname][] = array();
            #getModuleInfo()
        }

        $this->getView()->assign('modules', $modules);

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
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=install_new');

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
        Clansuite_Trail::addStep( _('Export'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=export');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');



        // Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Import/Export of Modules
     */
    public function action_admin_imexport()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Import & Export'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=imexport');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        // Prepare the Output
        $this->prepareOutput();
    }


    //
    // Module creator + methods below
    //


    /**
     * Shows the module builder
     */
    public function action_admin_builder()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Builder'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=builder');

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
     *
     * @todo convention ajaxaction_
     */
    public function action_admin_preview()
    {
        $smarty = $this->getView();

        # request init
        $request = $this->injector->instantiate('Clansuite_HttpRequest');
        # get parameter for module data
        $mod = $request->getParameter('m');
        var_dump($mod);

        # serialize the module data
        $mod['data'] = base64_encode(serialize($mod));

        # assign the whole pack to smarty
        $smarty->assign( 'mod', $mod );

        #$smarty->autoload_filters = array();
        #$smarty->unregister_prefilter('smarty_prefilter_inserttplnames');
        #error_reporting(0);

        /**
         * Include & Instantiate GeSHi
         * for the formatting of the sourcecode with geshi_highlight()
         */
        require_once( ROOT_LIBRARIES . 'geshi/geshi.php' );

        /**
         * Frontend = class Module_Modulename
         */
        if( isset($mod['frontend']['checked']) && $mod['frontend']['checked'] == 1)
        {

            /**
             * Frontend Header
             */
            $frontend = $smarty->fetch( ROOT_MOD . 'scaffolding/module_frontend.tpl');
            $smarty->assign( 'frontend', geshi_highlight($frontend,'php-brief', '',true ) );

            /**
             * Frontend Method
             */
            $frontend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_frontend_method.tpl');
            $smarty->assign( 'frontend_methods',  $frontend_methods);

            /**
             * Widget Method (Module integrated)
             */
            if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
            {
                $widget_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_widget_method.tpl');
                $smarty->assign( 'widget_methods',  $widget_methods);
            }
        }

        /**
         * BACKEND - Module_Modulename_Admin
         */
        if( isset($mod['backend']['checked']) && $mod['backend']['checked'] == 1)
        {

            /**
             * Admin Module Header
             */
            $backend = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend.tpl');
            $smarty->assign( 'backend', geshi_highlight( $backend ,'php-brief', '',true ) );

            /**
             * Admin Module Method
             */
            $backend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend_method.tpl');
            $smarty->assign( 'backend_methods',  $backend_methods );
        }

        /**
         * CONFIG - Module Configuration File
         */
        if( isset($mod['config']['checked']) && $mod['config']['checked'] == 1)
        {
            $config = $smarty->fetch( ROOT_MOD . 'scaffolding/module_config.tpl');
            $smarty->assign( 'config', geshi_highlight($config,'php-brief', '',true ) );
        }

        #error_reporting( E_ALL || E_STRICT );
        #$smarty->register_prefilter('smarty_prefilter_inserttplnames');

        /**
         * Folder's writeable?
         */
        if ( !is_writeable( ROOT_MOD ) )
        {
            $err['mod_folder_not_writeable'] = 1;
        }

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
        Clansuite_Trail::addStep( _('Create'), '/index.php?mod=modulemanager&amp;sub=admin&amp;action=create');

        $request = $this->injector->instantiate('Clansuite_HttpRequest');
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

        if (!is_dir(ROOT_MOD .  $mod['module_name']))
        {
            // CREATE DIRECTORIES
            mkdir( ROOT_MOD .  $mod['module_name'] );
            mkdir( ROOT_MOD .  $mod['module_name'] . DS . 'templates' );

        }
        else
        {
            echo 'The Module folder already exists: '. ROOT_MOD .  $mod['module_name'];
            exit;
        }

        // FRONTEND
        if( isset($mod['frontend']['checked']) && $mod['frontend']['checked'] == 1)
        {
            // WIDGETS
            if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
            {
                $widget_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_widget_method.tpl');
                $smarty->assign( 'widget_methods',  $widget_methods);
            }

            $frontend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_frontend_method.tpl');
            $smarty->assign( 'frontend_methods',  $frontend_methods);
            $frontend = $smarty->fetch('module_frontend.tpl');
            file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . $mod['module_name'] . '.module.php', $frontend );
        }

        // BACKEND
        if( isset($mod['backend']['checked']) && $mod['backend']['checked'] == 1)
        {
            $backend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend_method.tpl');
            $smarty->assign( 'backend_methods',  $backend_methods );
            $backend = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend.tpl');
            file_put_contents(ROOT_MOD .  $mod['module_name'] . DS . $mod['module_name'] . '.admin.php', $backend );
        }

        # Config
        $this->createConfigFromTemplate($mod['module_name']);

        # Setup
        $this->createSetupFromTemplate($mod['module_name']);

        # Frontend Templates
        $this->createFrontendTemplatesFromTemplate($mod['module_name'], $mod['frontend']);

        # Backend Templates = Adminmodule Templates
        $this->createBackendTemplatesFromTemplate($mod['module_name'], $mod['backend']);

        # Widget Method and Templates (Standalone Widget?)
        $this->createWidgetFromTemplate($mod['module_name'], $mod['widget']);

        # Documentation
        #$this->createModuleDocumentationFromTemplate($mod['module_name']);

        # Unittest
        #$this->createUnitTestFromTemplate($mod['module_name']);

        # MODULE META INFORMATIONS

        // Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');

        $this->prepareOutput();
    }

     /**
      * This Method creates the Config for the Module
      *
      * @param $modulename string Name of the Module
      */
     public function createConfigFromTemplate($modulename)
     {
        # get smarty
        $smarty = $this->getView();

        # load scaffolding template
        $config = $smarty->fetch( ROOT_MOD . 'scaffolding/module_config.tpl');

        # inject modifications

        # save to file
        file_put_contents( ROOT_MOD . $modulename . DS . $modulename . '.config.php' , $config);
     }

    /**
     * This Method creates the Setup for the Module
     */
    public function createSetupFromTemplate($modulename)
    {
        # get smarty
        $smarty = $this->getView();

        # load scaffolding template
        $setup = $smarty->fetch( ROOT_MOD . 'scaffolding/module_setup.tpl');

        # inject modifications

        # save to file
        file_put_contents( ROOT_MOD . $modulename . DS . $modulename . '.setup.php' , $setup);
    }

    /**
     * This Method creates the Templates for the Frontend Methods
     */
    public function createFrontendTemplatesFromTemplate($module_name, $module_frontend_data)
    {
        if( isset( $module_frontend_data['checked']) && $module_frontend_data['checked'] == 1 )
        {
            foreach( $module_frontend_data['frontend_methods'] as $key => $value )
            {
                if( isset( $module_frontend_data['frontend_tpls'][$key] ) )
                {
                    file_put_contents(ROOT_MOD . $module_name . DS . 'templates' . DS . $value . '.tpl', '');
                }
            }
        }
    }

    /**
     * This Method creates the templates for the backend methods.
     *
     * @param
     */
    public function createBackendTemplatesFromTemplate($module_name, $module_backend_data)
    {
        if( isset( $module_backend_data['checked']) && $module_backend_data['checked'] == 1 )
        {
            foreach( $module_backend_data['backend_methods'] as $key => $value )
            {
                if( isset( $module_backend_data['backend_tpls'][$key] ) )
                {
                    file_put_contents(ROOT_MOD . $module_name . DS . 'templates' . DS . $value . '.tpl', '');
                }
            }
        }
    }

    /**
     * This Method creates a Widget for the Module
     *
     * @param string $module modulename
     * @param array $module_widget widget parameters array
     * @return void
     */
    public function createWidgetTemplateFromTemplate($module, $module_widget)
    {
        # Create the Widget Method
        # @todo


        # Create Widget Template
        if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
        {
            foreach( $mod['widget']['widget_methods'] as $key => $value )
            {
                if( isset($mod['widget']['widget_tpls'][$key]) )
                {
                    file_put_contents(ROOT_MOD .  $module . DS . 'templates' . DS . $value . '.tpl', '');
                }
            }
        }
    }

    /**
     * This Method creates a Documentation Template for the Module, if not existant yet.
     *
     * @param $module string Modulename
     * @return void
     */
    public function createModuleDocumentationTemplateFromTemplate($module)
    {
        # check, if the documentation file exists, if not create documentation from template
        if( is_file($module.'_docu.asc') == false )
        {
            # get some moduleinfos from module_info.xml

            # fill the documentation template with the moduleinfo data
            $documentation_template_content = $smarty->fetch( ROOT_MOD . 'scaffolding/module_documentation.tpl');

            # write the documentation file to the moduledir
            file_put_contents( ROOT_MOD .  $module . DS . 'doc' . DS . $module . '_documentation.asc', $documentation_template_content);
        }
    }

    /**
     * This Method is a loop over all Module Directories.
     * It uses DirectoryIterator.
     */
    private function runThroughAllModuleDirectories()
    {

    }

    public function activate()
    {
        #$module = new CsModule;
        #$module


        $this->module->id = (int) $_GET['id'];
        $this->module->getBy(array('id'));
        $this->module->active = 'Y';
        $this->module->save();

        $this->pageDetails->addMessage("Module has been activated. Don't forget to check the access levels to make sure every user has access to it!");
        $this->pageDetails->goTo('index.php?module=modules');

    }

    public function deactivate()
    {

        $this->_load('modules','module');
        $this->module->id = (int) $_GET['id'];
        $this->module->getBy(array('id'));
        $this->module->active = 'N';
        $this->module->save();

        $this->pageDetails->addMessage("Module has been de-activated.");
        $this->pageDetails->goTo('index.php?module=modules');

    }

    public function remove($id)
    {

        $disallowed_to_delete = array('account', 'controlcenter', 'modulemanager', 'permissions', 'users');

        if( in_array($module->module, $disallowed_to_delete) == false )
        {
            throw new Clansuite_Exception('You cannot delete the login, modules or accesslevels module.');
        }
        else
        {
            $module = new CsModule;
            $module->id = (int) $_GET['id'];
            $module->getby(array('id'));

            $module->remove();

            # report position to the eventdispatcher
            $this->addEvent('onAfterModuleRemove');

            # redirect back to the administration area of the modulemanager
            $this->redirect('index.php?mod=modulemanager&sub=admin');
        }
    }
}
?>