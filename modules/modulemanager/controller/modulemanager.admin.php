<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id: news.admin.php 3747 2009-11-20 14:59:46Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Modulemanager_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Modulemanager
 */

class Clansuite_Module_Modulemanager_Admin extends Clansuite_Module_Controller
{

    public function initializeModule()
    {
        $this->getModuleConfig();
    }

    /**
     * Show the modulemanager
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_show_menueditor');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/modulemanager/admin/show');

        $moduleinfo = new Clansuite_ModuleInfoController();
        $modules_info_array = $moduleinfo->getModuleInformations();
        $modules_summary = $modules_info_array['yy_summary'];
        array_pop($modules_info_array);

        #Clansuite_Debug::printR( $modules_info_array );

        # Fetch view and assign vars
        $view = $this->getView();

        $view->assign('modules_summary', $modules_summary);
        $view->assign('modules', $modules_info_array);

        $this->display();
    }


    public function action_admin_edit()
    {
        $this->display();
    }

    public function action_admin_edit_info()
    {
         # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Edit Info'), '/modulemanager/admin/edit_info');

        $modulename = $this->request->getParameterFromGet('modulename');

        $moduleinfo = new Clansuite_ModuleInfoController();
        $modules_info_array = $moduleinfo->getModuleInformations($modulename);
        print_r($modules_info_array);

        $this->display();
    }

    /**
     * Install new modules
     */
    public function action_admin_install()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Show'), '/modulemanager/admin/install_new');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        // Prepare the Output
        $this->display();
    }

    /**
     * Export a module
     */
    public function action_admin_export()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Export'), '/modulemanager/admin/export');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        // Prepare the Output
        $this->display();
    }

    /**
     * Import/Export of Modules
     */
    public function action_admin_imexport()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Import & Export'), '/modulemanager/admin/imexport');

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        // Prepare the Output
        $this->display();
    }

    //
    // Module creator + methods below
    //

    /**
     * Shows the module builder
     */
    public function action_admin_builder()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Builder'), '/modulemanager/admin/builder');

        $existing_modules_js = '[';
        $module_dirs = Clansuite_ModuleInfoController::getModuleDirectories();

        foreach( $module_dirs as $key => $value )
        {
            $existing_modules_js .= '"' . str_replace(strtolower(ROOT_MOD), '', strtolower($value)) . '",';
        }
        $existing_modules_js = preg_replace( '#,$#', ']', $existing_modules_js);

        $view = $this->getView();

        $view->assign('existing_modules_js', $existing_modules_js);

        $this->display();
    }

    /**
     * Preview the new mod
     *
     * @todo convention ajaxaction_
     */
    public function action_admin_preview()
    {
        $view = $this->getView();

        # get parameter for module data
        $mod = $this->request->getParameter('m');
        var_dump($mod);

        # serialize the module data
        $mod['data'] = base64_encode(serialize($mod));

        # assign the whole pack to smarty
        $view->assign( 'mod', $mod );

        #$smarty->autoload_filters = array();
        #$smarty->unregister_prefilter('smarty_prefilter_inserttplnames');
        #error_reporting(0);

        /**
         * Include & Instantiate GeSHi
         * for the formatting of the sourcecode with geshi_highlight()
         */
        if(class_exists('GeSHi', false) == false)
        {
            include ROOT_LIBRARIES . 'geshi/geshi.php';
        }

        /**
         * Frontend = class Clansuite_Module_Modulename
         */
        if( isset($mod['frontend']['checked']) && $mod['frontend']['checked'] == 1)
        {

            /**
             * Frontend Header
             */
            $frontend = $smarty->fetch( ROOT_MOD . 'scaffolding/module_frontend.tpl');
            $view->assign( 'frontend', geshi_highlight($frontend,'php-brief', '',true ) );

            /**
             * Frontend Method
             */
            $frontend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_frontend_method.tpl');
            $view->assign( 'frontend_methods',  $frontend_methods);

            /**
             * Widget Method (Module integrated)
             */
            if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
            {
                $widget_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_widget_method.tpl');
                $view->assign( 'widget_methods',  $widget_methods);
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
            $view->assign( 'backend', geshi_highlight( $backend ,'php-brief', '',true ) );

            /**
             * Admin Module Method
             */
            $backend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend_method.tpl');
            $view->assign( 'backend_methods',  $backend_methods );
        }

        /**
         * CONFIG - Module Configuration File
         */
        if( isset($mod['config']['checked']) && $mod['config']['checked'] == 1)
        {
            $config = $smarty->fetch( ROOT_MOD . 'scaffolding/module_config.tpl');
            $view->assign( 'config', geshi_highlight($config,'php-brief', '',true ) );
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
        $this->display();
    }

    /**
     * Create the new module
     *
     * a) RAD: &modulename=language&classname=module_language_admin
     */
    public function action_admin_create()
    {
        # Permission check
        #$perms::check('cc_update_menueditor');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Create'), '/modulemanager/admin/create');

        $mod = $this->request->getParameter('mod_data');

        if($mod)
        {
            # unserialize module data
            $mod = unserialize(base64_decode($mod));

            # Check if the Modules folder is writeable
            if ( !is_writeable( ROOT_MOD ) )
            {
                $err['mod_folder_not_writeable'] = 1;
            }

            # Check if the Module directory is not already existing
            if (!is_dir(ROOT_MOD .  $mod['modulename']))
            {
                // CREATE DIRECTORIES
                mkdir( ROOT_MOD .  $mod['modulename'], fileperms(ROOT_MOD) );
                mkdir( ROOT_MOD .  $mod['modulename'] . DS . 'view', fileperms(ROOT_MOD .  $mod['modulename']) );
            }
            else
            {
                echo 'The Module folder already exists: '. ROOT_MOD .  $mod['modulename'];
                #exit;
            }

            /**
             * FRONTEND
             * It is the mainmodule of a module with the name modulename.module.php.
             */
            if( isset($mod['frontend']['checked']) && $mod['frontend']['checked'] == 1)
            {
                // WIDGETS
                if( isset($mod['widget']['checked']) && $mod['widget']['checked'] == 1)
                {
                    $widget_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_widget_method.tpl');
                    $view->assign( 'widget_methods',  $widget_methods);
                }

                $frontend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_frontend_method.tpl');
                $view->assign( 'frontend_methods',  $frontend_methods);
                $frontend = $smarty->fetch('module_frontend.tpl');
                file_put_contents(ROOT_MOD .  $mod['modulename'] . DS . $mod['modulename'] . '.module.php', $frontend );
            }

            /**
             * BACKEND
             * It is an submodule with the name modulename.admin.php.
             */
            if( isset($mod['backend']['checked']) && $mod['backend']['checked'] == 1)
            {
                $backend_methods = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend_method.tpl');
                $view->assign( 'backend_methods',  $backend_methods );
                $backend = $smarty->fetch( ROOT_MOD . 'scaffolding/module_backend.tpl');
                file_put_contents(ROOT_MOD .  $mod['modulename'] . DS . $mod['modulename'] . '.admin.php', $backend );
            }

            # Config
            $this->createConfigFromTemplate($mod['modulename']);

            # Setup
            $this->createSetupFromTemplate($mod['modulename']);

            # Frontend Templates
            $this->createFrontendTemplatesFromTemplate($mod['modulename'], $mod['frontend']);

            # Backend Templates = Adminmodule Templates
            $this->createBackendTemplatesFromTemplate($mod['modulename'], $mod['backend']);

            # Widget Method and Templates (Standalone Widget?)
            #$this->createWidgetFromTemplate($mod['modulename'], $mod['widget']);

            # Documentation
            #$this->createModuleDocumentationFromTemplate($mod['modulename']);

            # Unittest
            #$this->createUnitTestFromTemplate($mod['modulename']);

            # MODULE META INFORMATIONS

            $view = $this->getView();
            $view->assign( 'mod', $mod );

            // Set Layout Template
            #$this->getView()->setLayoutTemplate('index.tpl');
        }
        else # display a preview dialog of the module to be created
        {
            $view = $this->getView();
            $mod = array();
            $mod['modulename'] = $this->request->getParameter('modulename');
            $mod['classname']  = $this->request->getParameter('classname');
            $view->assign( 'mod', $mod );
        }

        $this->display();
    }

    public function action_admin_createconfig()
    {
        $view = $this->getView();

        $this->display();
    }

     /**
      * This Method creates the Config for the Module
      *
      * @param $modulename string Name of the Module
      */
     public function createConfigFromTemplate($modulename)
     {
        # get smarty
        $view = $this->getView();

        # load scaffolding template
        $config = $smarty->fetch( ROOT_MOD . 'scaffolding/module_config.tpl');

        # inject modifications

        # save to file
        file_put_contents( ROOT_MOD . $modulename . DS . $modulename . '.config.php' , $config);
     }

     /**
      * This Method creates the Menu (modulenavigation)  for the Module
      *
      * @param $modulename string Name of the Module
      */
     public function createMenuFromTemplate($modulename)
     {
        # get smarty
        $view = $this->getView();

        # load scaffolding template
        $menu_template_content = $smarty->fetch( ROOT_MOD . 'scaffolding/module_menu.tpl');

        # inject modifications

        # save to file
        file_put_contents( ROOT_MOD . $modulename . DS . $modulename . '.menu.php', $menu_template_content);
     }

    /**
     * This Method creates the Setup for the Module
     */
    public function createSetupFromTemplate($modulename)
    {
        # get smarty
        $view = $this->getView();

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
                    file_put_contents(ROOT_MOD . $module_name . DS . 'view' . DS . $value . '.tpl', '');
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
                    file_put_contents(ROOT_MOD . $module_name . DS . 'view' . DS . $value . '.tpl', '');
                }
            }
        }
    }

    /**
     * This Method creates a Widget for the Module
     *
     * @param string $module modulename
     * @param array $module_widget widget parameters array
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
                    file_put_contents(ROOT_MOD .  $module . DS . 'view' . DS . $value . '.tpl', '');
                }
            }
        }
    }

    /**
     * This Method creates a Documentation Template for the Module, if not existant yet.
     *
     * @param $module string Modulename
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
     * This Method creates a the modulenavigation file, if not existant yet.
     * modulename.menu.php
     *
     * @param $module string Modulename
     */
    public function createModuleMenunavigationTemplateFromTemplate($module)
    {
        # check, if the modulename.menu.php file exists, if not create menu from template
        if( is_file( ROOT_MOD .  $module . DS . $module . '.menu.asc') == false )
        {
            # get some moduleinfos from module_info.xml

            # one menu entry
            /*array = ( '1' => array(
                                    'action'  => 'show',
                                    'name'    => 'Overview',
                                    'url'      => 'index.php?mod=news&sub=admin', # &action=show
                                    'icon'    => '',
                                    'tooltip' => ''
                                        )
                    )*/

            # fill the documentation template with the moduleinfo data
            $documentation_template_content = $smarty->fetch( ROOT_MOD . 'scaffolding/module_menu.tpl');

            # write the documentation file to the moduledir
            file_put_contents( ROOT_MOD .  $module . DS . '.menu.asc', $documentation_template_content);
        }
    }

    public static function pretty_var($array)
    {
        print str_replace( array("\n"," "), array("<br>","&nbsp;"), var_export($array, true))."<br>";
    }

    public static function recursive_print ($varname, $varval)
    {
        if (is_array($varval) == false)
        {
            print $varname . ' = ' . var_export($varval, true) . ";<br>\n";
        }
        else
        {
            print $varname . " = array();<br>\n";
            foreach ($varval as $key => $val)
            {
              recursive_print ($varname . "[" . var_export($key, true) . "]", $val);
            }
        }
    }

    public function activate()
    {
        $module = new CsModule;
        $module['id'] = (int) $this->request['id'];
        $module['active'] = true;
        $module->save();

        $this->flashMessage("Module has been activated. Please check the access levels!");
        $this->redirect('index.php?module=modulemangager');
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
            throw new Clansuite_Exception('You cannot delete this essential core module : '.$module->module.'.');
        }
        else
        {
            $module = new CsModule;
            $module->id = (int) $_GET['id'];
            $module->getby(array('id'));

            $module->remove();

            # report position to the eventdispatcher
            $this->triggerEvent('onAfterModuleRemove');

            # redirect back to the administration area of the modulemanager
            $this->redirect('index.php?mod=modulemanager/admin');
        }
    }



    /* -------------------------------------------------------------------------
     *    MODULE INSTALLATION 
     * ----------------------------------------------------------------------- */

    public function clearTables( array $tables )
    {
        $con = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
        for($i=0; $i<count($tables); $i++) {
            $con->query('TRUNCATE TABLE '.$tables[$i] );
        }
        unset($con);
        return true;
    }

    /*
     * -----------------------------------------------------------------------------------------------------------
     * action_admin_installallmodulefirsttime
     * -----------------------------------------------------------------------------------------------------------
     * WARNING: Nur verwenden für die erstmalige Erfassung aller Module
     * hierzu müssen die Tabellen: cs_modules, cs_acl_resources, cs_acl_rules geleert werden
     */
    public function action_admin_installallmodulefirsttime()
    {
        $tables = array(
            'cs_acl_resources',
            'cs_acl_rules',
            'cs_modules'
        );

        if( Clansuite_DoctrineTools::truncateTables( $tables ) )
        {
            $modul = $resource = array();

            $moduleinfo = new Clansuite_ModuleInfoController();
            $modules_info_array = $moduleinfo->getModuleInformations();
            array_pop($modules_info_array);

            //Clansuite_Debug::printR( $modules_info_array );

            # ----------------------------------------------------------------------
            # Prepare Modules Records for DB Includes
            # ----------------------------------------------------------------------
            foreach( $modules_info_array as $modules_info )
            {
                // image
                $modul['image_name'] = 'module_'.$modules_info['name'].'.jpg';

                # *************** global ***************

                $modul['name'] = $modules_info['name'];
                $modul['title'] = ucfirst($modules_info['name']);

                if(isset($modules_info['description'])) 
                {
                    $modul['description'] = $modules_info['description']; 
                }
                else {
                    $modul['description'] = '';
                }

                # --- core modul ----------
                $modul['core'] = ($modules_info['core']?1:0);


                # *************** Section: Settings ***************

                # --- modul active ----------
                //$modul['enabled'] = ($modules_info['active']?1:0);
                if( isset( $modules_info['settings']['active'] ) )
                {
                    $modul['enabled'] = $modules_info['settings']['active'];
                }
                else {
                    $modul['enabled'] = false;
                }

                # --- section ----------
                if( isset( $modules_info['settings']['section'] ) )
                {
                    $modul['section_id'] = $modules_info['settings']['section'];
                }
                else {
                    $modul['section_id'] = null;
                }

                # *************** Section: Info Info ***************

                # --- author/copyright ----------
                if( isset( $modules_info['info'][$modules_info['name'].'_info']['author'] ) )
                {
                    $modul['author'] = utf8_encode($modules_info['info'][$modules_info['name'].'_info']['author']);
                    $modul['copyright'] = utf8_encode($modules_info['info'][$modules_info['name'].'_info']['author']);
                }
                else {
                    $modul['module_version'] = '0.2';
                }

                # --- license ----------
                if( isset( $modules_info['info'][$modules_info['name'].'_info']['license'] ) )
                {
                    $modul['license'] = $modules_info['info'][$modules_info['name'].'_info']['license'];
                }
                else {
                    $modul['license'] = 'GPLv2';
                }

                # --- link ----------
                if( isset( $modules_info['info'][$modules_info['name'].'_info']['link'] ) )
                {
                    $modul['homepage'] = $modules_info['info'][$modules_info['name'].'_info']['link'];
                }
                else {
                    $modul['homepage'] = '';
                }

                # *************** Section: Info Package ***************

                # --- version ----------
                if( isset( $modules_info['info'][$modules_info['name'].'_package']['version'] ) )
                {
                    $modul['module_version'] = $modules_info['info'][$modules_info['name'].'_package']['version'];
                }
                else {
                    $modul['module_version'] = '0.2';
                }

                # --- require_version ----------
                // require_version
                if( isset( $modules_info['info'][$modules_info['name'].'_package']['require_version'] ) )
                {
                    $modul['clansuite_version'] = $modules_info['info'][$modules_info['name'].'_package']['require_version'];
                }
                else {
                    $modul['clansuite_version'] = '0.2';
                }

                # save module record (cs_module)
                $modules = new CsModules();
                $modules->parent_id = 0;
                $modules->section_id = $modul['section_id'];
                $modules->name = $modul['name'];
                $modules->title = $modul['title'];
                $modules->description = $modul['description'];
                $modules->enabled = $modul['enabled'];
                $modules->core = $modul['core'];
                $modules->module_version = $modul['module_version'];
                $modules->clansuite_version = $modul['clansuite_version'];
                $modules->license = $modul['license'];
                $modules->author = $modul['author'];
                $modules->homepage = $modul['homepage'];
                $modules->copyright = $modul['copyright'];
                $modules->image_name = $modul['image_name'];
                $modules->save();
                $lastModuleID = Clansuite_DoctrineTools::lastTableInsertId('CsModules', 'module_id');

                //Clansuite_Debug::printR( $lastModuleID );

                if( $lastModuleID >0 )
                {
                    # write module-Id in config
                    # @todo


                    # *************** Section: acl ***************

                    # read modules rights and prepare acl-array for DB includes (table: cs_acl_resources and cs_acl_rules)
                    if( isset( $modules_info['acl'] ) )
                    {
                        foreach( $modules_info['acl'] as $key=>$val  ) 
                        {
                            //$lastid++;
                            # ---------------
                            # create and save resources
                            # ---------------
                            $resour = new CsAclResources();
                            $resour->modulname = $modul['name'];
                            $resour->action = $key;
                            $resour->save();
                            $resource_id = $lastModuleID;
                            $lastResourceID = Clansuite_DoctrineTools::lastTableInsertId('CsAclResources', 'resource_id');

                            # ---------------
                            # create and save permissions
                            # role_id 1 = root
                            # role_id 2 = bot
                            # role_id 3 = guest
                            # role_id 4 = member
                            # role_id 5 = admin
                            # ---------------
                            if($val == 'all' )
                            {
                                for($i=1; $i<6; $i++)
                                {
                                    $rule = new CsAclRules();
                                    $rule->role_id = $i;
                                    $rule->resource_id = $lastResourceID;
                                    $rule->access = 1;
                                    $rule->save();
                                }
                            }
                            else {
                                if( false !== mb_strpos( $val , '|' ) )
                                {
                                    $perm = explode('|', $val);
                                    for($i=0; $i<count($perm); $i++)
                                    {
                                        $rule = new CsAclRules();
                                        switch($perm[$i]) {
                                            case 'r':   $rule->role_id = 1; break;
                                            case 'a':   $rule->role_id = 5; break;
                                            case 'm':  $rule->role_id = 4; break;
                                            case 'g':   $rule->role_id = 3; break;
                                            case 'b':   $rule->role_id = 2; break;
                                        }
                                        $rule->resource_id = $lastResourceID;
                                        $rule->access = 1;
                                        $rule->save();
                                    }
                                }
                                else {
                                    //Clansuite_Debug::printR( $val );
                                    $rule = new CsAclRules();
                                    switch($val) {
                                        case 'r':   $rule->role_id = 1; break;
                                        case 'a':   $rule->role_id = 5; break;
                                        case 'm':  $rule->role_id = 4; break;
                                        case 'g':   $rule->role_id = 3; break;
                                        case 'b':   $rule->role_id = 2; break;
                                    }
                                    $rule->resource_id = $lastResourceID;
                                    $rule->access = 1;
                                    $rule->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    # --------------------- end action_admin_installallmodulefirsttime ----------------------------- #

}
?>