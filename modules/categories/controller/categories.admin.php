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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Module_Categories_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Categories
 */
class Clansuite_Module_Categories_Admin extends Clansuite_Module_Controller
{
    public function initializeModule()
    {
        parent::initModel('categories');
        parent::initModel('modulemanager');
    }

    public function action_admin_show()
    {
        //--------------------------
        // Datagrid configuration
        //--------------------------

        $ColumnSets = array();

        $ColumnSets[] = array(  'Alias'     => 'Select',
                                'ResultSet' => 'cat_id',
                                'Name'      => _('[x]'),
                                'Type'      => 'Checkbox' );

        $ColumnSets[] = array(  'Alias'     => 'Name',
                                'ResultSet' => array(   'name'      => 'name',
                                                        'id'        => 'cat_id' ),
                                'Name'      => _('Name'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'name',
                                'Type'      => 'Link' );

        $ColumnSets[] = array(  'Alias'     => 'Description',
                                'ResultSet' => 'description',
                                'Name'      => _('Description'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'description',
                                'Type'      => 'String' );

        $ColumnSets[] = array(  'Alias'     => 'Module',
                                'ResultSet' => 'module',
                                'Name'      => _('Module'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'module',
                                'Type'      => 'String' );

        $ColumnSets[] = array(  'Alias'     => 'Image',
                                'ResultSet' => 'image',
                                'Name'      => _('Image'),
                                'Type'      => 'Image' );

        $ColumnSets[] = array(  'Alias'     => 'Icon',
                                'ResultSet' => 'icon',
                                'Name'      => _('Icon'),
                                'Type'      => 'Image' );

        $ColumnSets[] = array(  'Alias'     => 'Color',
                                'ResultSet' => 'color',
                                'Name'      => _('Color'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'color',
                                'Type'      => 'String' );

        # Batchactions
        $BatchActions[] = array(    'Alias'     => 'create',
                                    'Name'      => _('Create a news'),
                                    'Action'    => 'create' );

        $BatchActions[] = array(    'Alias'     => 'delete',
                                    'Name'      => _('Delete selected items'),
                                    'Action'    => 'delete' );


        # Instantiate the datagrid
        $datagrid = new Clansuite_Datagrid( array(
                'Datatable'  => Doctrine::getTable('CsCategories'),
                'NamedQuery' => 'fetchAllCategories',
                'ColumnSets' => $ColumnSets,
                'url'        => 'index.php?mod=categories&sub=admin'
        ) );

        $datagrid->setBatchActions( $BatchActions );
        $datagrid->disableFeature(array('Label','Caption','Description'));
        $datagrid->getColumn('Select')->disableFeature('Search');
        $datagrid->getColumn('Name')->getRenderer()->linkFormat = '&action=edit&id=%{id}';
        $datagrid->getColumn('Name')->getRenderer()->linkTitle  = _('Edit this category');

        # Assign to tpl
        $this->getView()->assign('datagrid', $datagrid->render());

        $this->display();
    }

    public function action_admin_create()
    {
        /**
         * Create a new form
         */
        $form = new Clansuite_Form('category_form', 'post', '/categories/admin/update&type=create');

        /**
         * Assign some Formlements
         */
        $form->addElement('text')->setName('cat_form[name]')->setLabel(_('Category Name'));
        $modules = Doctrine::getTable('CsModules')->fetchAllModulesDropDown();
        $form->addElement('multiselect')->setName('cat_form[module_id]')->setLabel(_('Module'))->setOptions($modules)
        ->setDescription(_('Select the module to create the category for.'));

        $form->addElement('textarea')->setName('cat_form[description]')->setID('cat_form[description]')->setCols('60')->setRows('5')->setLabel(_('Description'))->setEditor('tinymce');
        $form->addElement('jqselectcolor')->setName('cat_form[color]')->setLabel(_('Select Color'))->setDescription(_('Click Inputfield to toggle Colorwheel.'));
        $form->addElement('jqselectimage')->setName('cat_form[image]')->setLabel(_('Select Image'));
        $form->addElement('jqselectimage')->setName('cat_form[icon]')->setLabel(_('Select Icon'));
        # @todo category image upload + db insert

        $form->addElement('buttonbar')->getButton('cancelbutton')->cancelURL = 'index.php?mod=categories/admin';

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function action_admin_edit()
    {
        # get id
        $cat_id = $this->request->getParameter('id');

        # fetch category
        $cat = Doctrine::getTable('CsCategories')->fetchSingleCategory($cat_id);

        # Create a new form
        # @todo form object with auto-population of values

        $form = new Clansuite_Form('cat_form', 'post', '/categories/admin/update&type=edit');

        /**
         * cat_id as hidden field
         */
        $form->addElement('hidden')->setName('cat_form[cat_id]')->setValue($cat['cat_id']);

        # Assign some formlements
        $form->addElement('text')->setName('cat_form[name]')->setLabel(_('Category Name'))->setValue($cat['name']);
        $modules = Doctrine::getTable('CsModules')->fetchAllModulesDropDown();
        $form->addElement('multiselect')->setName('cat_form[module_id]')->setLabel(_('Module'))->setDefaultValue($cat['module_id'])->setOptions($modules)
        ->setDescription(_('Select the module to create the category for.'))->setValue($cat['module_id']);
        $form->addElement('textarea')->setName('cat_form[description]')->setID('cat_form[description]')->setCols('60')->setRows('5')->setLabel(_('Description'))->setValue($cat['description']);
        $form->addElement('text')->setName('cat_form[sortorder]')->setLabel(_('Sort Order'))->setValue($cat['sortorder']);
        $form->addElement('jqselectcolor')->setName('cat_form[color]')->setLabel(_('Select Color'))->setValue($cat['color']);
        $form->addElement('jqselectimage')->setName('cat_form[image]')->setLabel(_('Select Image'))->setDefaultValue($cat['image']);
        #$form->addElement('uploadajax')->setName('Upload Image')->setLabel(_('Upload Image'));
        $form->addElement('jqselectimage')->setName('cat_form[icon]')->setLabel(_('Select Icon'))->setDefaultValue($cat['icon']);
        #$form->addElement('uploadajax')->setName('Upload Icon')->setLabel(_('Upload Icon'));
        $form->addElement('buttonbar')->getButton('cancelbutton')->cancelURL = 'index.php?mod=categories/admin';

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function action_admin_delete()
    {
        $delete  = $this->request->getParameter('delete', 'P');

        if(isset($delete))
        {
            $numDeleted = Doctrine_Query::create()->delete('CsCategories')->whereIn('cat_id', $delete)->execute();
            $this->response->redirectNoCache('/categories/admin', 2, 302, $numDeleted . _(' Categories deleted.'));
        }
        else
        {
           $this->response->redirectNoCache('/categories/admin');
        }
    }

    /**
     * Denest a nested arrays within the given array.
     * @param arraz $array Multi-Dimensional array to flatten.
     */
    function flatten_array(array $arraz)
    {
        $i = 0;
        while ($i < count($a))
        {
            if (is_array($a[$i]))
            {
                array_splice($a, $i, 1, $a[$i]);
            }
            else
            {
                $i++;
            }
        }
        return $a;
    }

    public function action_admin_update()
    {
        # get incoming data
        $data = $this->request->getParameter('cat_form');
        $type = $this->request->getParameter('type', 'G');

        if(isset($type) and $type == 'create')
        {
            $cats = new CsCategories;
            $cats->module_id    = $data['module_id'];
            $cats->name         = $data['name'];
            $cats->description  = $data['description'];
            $cats->color        = $data['color'];
            $cats->image        = $data['image'];
            $cats->icon         = $data['icon'];
            $cats->save();

            # redirect
            $this->response->redirectNoCache('/categories/admin', 2, 302, _('The category has been created.'));
        }
        elseif(isset($type) and $type == 'edit')
        {
             # @todo validation

            # get the categories table
            $catsTable = Doctrine::getTable('CsCategories');

            # fetch the category to update by cat_id
            $cats = $catsTable->findOneByCat_Id($data['cat_id']);

            # if that category exist, update values and save
            if ($cats !== false)
            {
                $cats->cat_id       = $data['cat_id'];
                $cats->module_id    = $data['module_id'];
                $cats->name         = $data['name'];
                $cats->description  = $data['description'];
                $cats->color        = $data['color'];
                $cats->image        = $data['image'];
                $cats->icon         = $data['icon'];
                $cats->sortorder    = $data['sortorder'];
                $cats->save();
            }

            # redirect
            $this->response->redirectNoCache('/categories/admin', 2, 302, _('The category has been edited.'));
        }
        else
        {
            # redirect
            $this->response->redirectNoCache('/categories/admin', 2, 302, _('Unknown Formaction.'));
        }
    }

    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/categories/admin/settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'categories_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT . 'index.php?mod=categories/admin/settings_update');

        $settings['categories'][] = array(  'id' => 'items_resultsPerPage',
                                            'name' => 'items_resultsPerPage',
                                            'label' => _('Categories per Page'),
                                            'description' => _('Categories per Page'),
                                            'formfieldtype' => 'text',
                                            'value' => $this->getConfigValue('items_resultsPerPage', '25'));

        $form = new Clansuite_Form($settings);

        # display formgenerator object
        #Clansuite_Debug::printR($form);

        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        # display form html
        #Clansuite_Debug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function action_admin_settings_update()
    {
        # Incomming Data
        # @todo get post via request object, sanitize
        $data = $this->request->getParameter('categories_settings');

        # Get Configuration from Injector and write Config
        $this->getInjector()->instantiate('Clansuite_Config')->writeModuleConfig($data);

        # clear the cache / compiled tpls
        $this->getView()->clearCache();

        # Redirect
        $this->response->redirectNoCache('/categories/admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>