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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: news.module.php 2006 2008-05-07 09:08:40Z xsign $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}


/**
 * Clansuite
 *
 * Module:  Module_Staticpages_Admin
 *
 */
class Module_Staticpages_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function __construct(Phemto $injector=null)
    {
        parent::__construct();
    }

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
		parent::initRecords('staticpages');
    }

    /**
     * action_admin_show()
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_admin_show_staticpages');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Overview'), '/index.php?mod=staticpages&amp;sub=admin&amp;action=show');

        $staticpages = Doctrine_Query::create()
                              ->select('*')
                              ->from('CsStaticPages s')
                              ->orderby('s.title ASC')
                              ->execute(array(), Doctrine::HYDRATE_ARRAY);

         # Get Render Engine
        $smarty = $this->getView();
        $smarty->assign( 'staticpages', $staticpages);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        # specifiy the template manually
        #$this->setTemplate('news/admin_show.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }


    /**
     * create_staticpages()
     */
    function create_staticpages()
    {
        # Permission check
        #$perms::check('cc_admin_create_staticpages');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create'), '/index.php?mod=staticpages&amp;sub=admin&amp;action=create');

        $html           = $_POST['html'];
        $description    = $_POST['description'];
        $title          = $_POST['title'];
        $url            = $_POST['url'];
        $submit         = $_POST['submit'];
        $iframe         = $_POST['iframe'];
        $iframe_height  = $_POST['iframe_height'];

        if ( !empty( $submit ) )
        {
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

            if ( !$input->check( $url, 'is_url' ) AND !empty( $url ) )
            {
                $error['give_correct_url'] = 1;
            }

            $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'static_pages WHERE title = ?' );
            $stmt->execute( array( $title ) );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ( is_array( $result ) )
            {
                $error['static_already_exist'] = 1;
            }

            if ( count( $error ) == 0 )
            {
                $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'static_pages ( title, description, url, html, iframe, iframe_height ) VALUES ( ?, ?, ?, ?, ?, ? )' );
                $stmt->execute( array( $title, $description, $url, $html, $iframe, $iframe_height ) );

                $functions->redirect( 'index.php?mod=controlcenter&sub=staticpages&action=show', 'metatag|newsite', 3, $lang->t( 'The static page was successfully created...' ), 'admin' );
            }
        }

        $smarty = $this->getView();
        $smarty->assign( 'description' , $description );
        $smarty->assign( 'title'       , $title );
        $smarty->assign( 'url'         , $url );
        $smarty->assign( 'html'        , $html);
        $smarty->assign( 'error'       , $error);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        # specifiy the template manually
        #$this->setTemplate('staticpages/create.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * edit_staticpages()
     */
    function edit_staticpages()
    {
        # Permission check
        #$perms::check('cc_admin_edit_staticpages');

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Edit'), '/index.php?mod=staticpages&amp;sub=admin&amp;action=edit');

        $info['html']           = $_POST['html'];
        $info['description']    = $_POST['description'];
        $info['title']          = $_POST['title'];
        $info['orig_title']     = $_POST['orig_title'];
        $info['url']            = $_POST['url'];
        $info['iframe']         = $_POST['iframe'];
        $info['iframe_height']  = $_POST['iframe_height'];
        $info['submit']         = $_POST['submit'];
        $info['id']             = $_POST['id'];

        if ( !empty( $info['submit'] ) )
        {
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

            if ( !$input->check( $info['url'], 'is_url' ) AND !empty( $url ) )
            {
                $error['give_correct_url'] = 1;
            }

            $stmt = $db->prepare( 'SELECT id FROM ' . DB_PREFIX . 'static_pages WHERE title = ?' );
            $stmt->execute( array( $info['title'] ) );
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ( is_array( $result ) AND $info['orig_title'] != $info['title'] )
            {
                $error['static_already_exist'] = 1;
            }

            if ( count( $error ) == 0 )
            {
                $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'static_pages SET title = ?, description = ?, url = ?, html = ?, iframe = ?, iframe_height = ? WHERE id = ?' );
                $stmt->execute( array( $info['title'], $info['description'], $info['url'], $info['html'], $info['iframe'], $info['iframe_height'], $info['id'] ) );

                $functions->redirect( 'index.php?mod=controlcenter&sub=staticpages&action=show', 'metatag|newsite', 3, $lang->t( 'The static page was successfully changed...' ), 'admin' );
            }
        }
        else
        {
            if ( !empty( $info['id'] ) )
            {
                $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'static_pages WHERE id = ?' );
                $stmt->execute( array( $info['id'] ) );
                $info = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

        $smarty = $this->getView();
        $smarty->assign( 'error'  , $error);
        $smarty->assign( 'info' , $info);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');
        # specifiy the template manually
        #$this->setTemplate('staticpages/edit.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }
	
    /**
     * Action for displaying the Settings of a Module Staticpages
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=staticpages&amp;sub=admin&amp;action=settings');
        
        $settings = array();
        
        $settings['form']   = array(    'name' => 'staticpages_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=staticpages&amp;sub=admin&amp;action=settings_update');
                                        
        $settings['staticpages'][] = array(    'id' => 'items_resultsPerPage',
                                        'name' => 'items_resultsPerPage',
                                        'description' => _('Staticpages per Page'),
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
        $data = $this->getHttpRequest()->getParameter('staticpages_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');
        
        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'staticpages/staticpages.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=staticpages&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>