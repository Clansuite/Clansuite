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

namespace Clansuite\Modules\Staticpages\Controller;

use Clansuite\Core\Mvc\ModuleController;

/**
 * Clansuite_Module_Staticpages_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Staticpages
 */
class StaticpagesAdminController extends ModuleController
{
    /**
     * actionshow()
     */
    public function actionshow()
    {
        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add( _('Overview'), '/staticpages/admin/show');

        $model = $this->getModel()->findAll();

        \Koch\Debug\Debug::firebug($model);

        $view = $this->getView();
        $view->assign( 'staticpages', $model);
        $view->setLayoutTemplate('index.tpl');
        $this->display();
    }

    public function create_staticpages()
    {
        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add( _('Create'), '/staticpages/admin/create');

        // fetch entity
        $model = $this->getModel();

        // insert entity into the create form
        $form = $this->loadForm($formname, $module, $action, $assign_to_view);

        // @todo define form array
        $html           = $_POST['html'];
        $description    = $_POST['description'];
        $title          = $_POST['title'];
        $url            = $_POST['url'];
        $submit         = $_POST['submit'];
        $iframe         = $_POST['iframe'];
        $iframe_height  = $_POST['iframe_height'];

        // @todo form validation
        if ( !empty( $submit ) ) {
            if ( empty( $description ) OR
                 empty( $title ) )
            {
                $error['fill_form'] = 1;
            }

            if (  ( !$input->check( $description        , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $title              , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $iframe_height      , 'is_int' ) )
                    AND !$error['fill_form'] )
            {
                $error['no_special_chars'] = 1;
            }

            if ( !$input->check( $url, 'is_url' ) AND !empty( $url ) ) {
                $error['give_correct_url'] = 1;
            }

            $result = Doctrine_Query::create()->select('id')->from('CsStaticPages')->where('title', $title);

            if ( is_array( $result ) ) {
                $error['static_already_exist'] = 1;
            }

            // ----

            if ( count( $error ) == 0 ) {
                $page = new CsStaticPages();
                $page->title = $title;
                $page->description = $description;
                $page->url = $url;
                $page->html = $html;
                $page->iframe = $iframe;
                $page->iframe_height = $iframe_height;
                $page-save();

                $this->setFlashmessage('success', _( 'The Page successfully created.'));
                $this->redirectToReferer();
            }
        }

        $view = $this->getView();
        $view->assign( 'description' , $description );
        $view->assign( 'title'       , $title );
        $view->assign( 'url'         , $url );
        $view->assign( 'html'        , $html);
        $view->assign( 'error'       , $error);

        // Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        // specifiy the template manually
        #$view->setTemplate('staticpages/create.tpl');

        $this->display();
    }

    public function edit_staticpages()
    {
        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add( _('Edit'), '/staticpages/admin/edit');

        $info['html']           = $_POST['html'];
        $info['description']    = $_POST['description'];
        $info['title']          = $_POST['title'];
        $info['orig_title']     = $_POST['orig_title'];
        $info['url']            = $_POST['url'];
        $info['iframe']         = $_POST['iframe'];
        $info['iframe_height']  = $_POST['iframe_height'];
        $info['submit']         = $_POST['submit'];
        $info['id']             = $_POST['id'];

        if ( !empty( $info['submit'] ) ) {
            if ( empty( $info['description'] ) OR
                 empty( $info['title'] ) )
            {
                $error['fill_form'] = 1;
            }

            if (  ( !$input->check( $info['description']    , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $info['title']          , 'is_abc|is_int|is_custom', '_\s' ) OR
                    !$input->check( $info['iframe_height']  , 'is_int' ) )
                    AND !$error['fill_form'] )
            {
                $error['no_special_chars'] = 1;
            }

            if ( !$input->check( $info['url'], 'is_url' ) AND !empty( $url ) ) {
                $error['give_correct_url'] = 1;
            }

            $entity = $this->getModel()->findOneBySlug($slug);

            if ($entity === null) {
                throw new Clansuite_Exception('Unable to find the requested Page: ' . $slug);
            }
            \Koch\Debug\Debug::firebug($entity);
            #$page = Doctrine::getTable('CsStaticPages')->findOneBy('title', $title);

            if ( is_array( $page ) and $info['orig_title'] != $info['title'] ) {
                $error['static_already_exist'] = 1;
            }

            if ( count( $error ) == 0 ) {
                $page->title         = $info['title'];
                $page->description   = $info['description'];
                $page->url           = $info['url'];
                $page->html          = $info['html'];
                $page->iframe        = $info['iframe'];
                $page->iframe_height = $info['iframe_height'];
                $page->save();

                $this->setFlashmessage('success', _( 'The page was successfully modified.' ));
                $this->redirect('/controlcenter/staticpages&action=show');
            }
        } else {
            // $info
        }

        $view = $this->getView();
        $view->assign('error', $error);
        $view->assign('info', $info);
        $view->setLayoutTemplate('index.tpl');

        $this->display();
    }

    /**
     * Action for displaying the Settings of a Module Staticpages
     */
    public function actionSettings()
    {
        // Set Pagetitle and Breadcrumbs
        \Koch\View\Helper\Breadcrumb::add( _('Settings'), '/staticpages/admin/settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'staticpages_settings',
                                        'method' => 'POST',
                                        'action' => '/staticpages/admin/settingsUpdate');

        $settings['staticpages'][] = array(    'id' => 'items_resultsPerPage',
                                        'name' => 'items_resultsPerPage',
                                        'description' => _('Staticpages per Page'),
                                        'formfieldtype' => 'text',
                                        'value' => self::getConfigValue('items_resultsPerPage', '25'));

        $form = new Clansuite_Form($settings);

        // display formgenerator object
        #\Koch\Debug\Debug::printR($form);

        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

        // display form html
        #\Koch\Debug\Debug::printR($form->render());

        // assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    public function actionSettingsUpdate()
    {
        // Incomming Data
        $data = $this->request->getParameter('staticpages_settings');

        // Get Configuration from Injector and write Config
        $this->getInjector()->instantiate('Clansuite_Config')->writeModuleConfig($data);

        // clear the cache / compiled tpls
        $this->getView()->clearCache();

        // Redirect
        $this->response->redirectNoCache('/staticpages/admin');
    }
}
