<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      Downloads
 * Submodule:   Admin
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - $Date: 2008-06-12 01:44:20 +0200 (Do, 12 Jun 2008) $)
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Categories
 */
class Module_Categories_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{

    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        parent::initRecords('categories');
        parent::initRecords('modulemanager');
    }

    /**
     * Module_Matches_Admin - action_admin_show
     *
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_view_matches');

        # SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/SmartyColumnSort.class.php');
        # A list of database columns to use in the table.
        $columns = array( 'm.name', 'c.name');
        # Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        # And set the the default sort column and order.
        $columnsort->setDefault('m.name', 'desc');
        # Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

		
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=categories&amp;sub=admin&amp;action=show');

		$categories = Doctrine::getTable('CsCategories')->fetchAllCategories($sortorder);
		

        $view = $this->getView();
        $view->assign('categories', $categories);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Create Category
     */
    function action_admin_create()
    {
        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        /**
         * Create a new form
         */
        $form = new Clansuite_Form('categrory_form', 'post', 'index.php?mod=categories&sub=admin&action=update&type=create');

        /**
         * Assign some Formlements
         */
        $form->addElement('text')->setName('cat_form[name]')->setLabel(_('Category Name'));
        $modules = Doctrine::getTable('CsModules')->fetchAllModulesDropDown();        
        $form->addElement('multiselect')->setName('cat_form[module_id]')->setLabel(_('Module'))->setOptions($modules)
        ->setDescription(_('Select the module to create the category for.'));;
        $form->addElement('textarea')->setName('cat_form[description]')->setID('cat_form[description]')->setCols('60')->setRows('5')->setLabel(_('Description'));        
        $form->addElement('text')->setName('cat_form[sortorder]')->setLabel(_('Sort Order'));
        $form->addElement('jqselectcolor')->setName('cat_form[color]')->setLabel(_('Select Color'));
        # @todo category image upload + db insert
        #$form->addElement('image');
        #$form->addElement('icon');
        $form->addElement('submitbutton')->setValue('Submit');
        $form->addElement('resetbutton')->setValue('Reset');

        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    /**
     * Action for displaying the Settings of a Module Categories
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=categories&amp;sub=admin&amp;action=settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'news_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=categories&amp;sub=admin&amp;action=settings_update');

        $settings['categories'][] = array(  'id' => 'items_resultsPerPage',
                                            'name' => 'items_resultsPerPage',
                                            'description' => _('Categories per Page'),
                                            'formfieldtype' => 'text',
                                            'value' => $this->getConfigValue('items_resultsPerPage', '25'));

        require ROOT_CORE . '/viewhelper/formgenerator.core.php';
        $form = new Clansuite_Array_Formgenerator($settings);

        # display formgenerator object
        #clansuite_xdebug::printR($form);

        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        # display form html
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    function action_admin_settings_update()
    {
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->getHttpRequest()->getParameter('categories_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');

        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'categories/categories.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=categories&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>