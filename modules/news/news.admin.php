<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch Â© 2005 - onwards
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
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      News
 * Submodule:   Admin
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2005 - 2008)
 * @since      Class available since Release 1.0alpha
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  News
 */
class Module_News_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        parent::initRecords('news');
        parent::initRecords('users');
        parent::initRecords('categories');
    }

    /**
     * Module_News_Admin - action_admin_show
     *
     * Show all news entries and give the possibility to edit/delete
     * Show DropDown with possibility to select the news category
     */
    public function action_admin_show()
    {
        # Permission check
        #$perms::check('cc_view_news');

        # Set Pagetitle and Breadcrumbs
        #Clansuite_Trail::addStep( _('Show'), '/index.php?mod=news&amp;sub=admin&amp;action=show');

        # Incoming Variables
        $request = $this->getHttpRequest();
        $category       = (int) $request['news_category_form']['cat_id'];
        $currentPage    = (int) $request->getParameter('page');
        $resultsPerPage = (int) $this->getConfigValue('resultsPerPage_adminshow', '10');;

        # SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/SmartyColumnSort.class.php');
        # A list of database columns to use in the table.
        $columns = array( 'n.created_at', 'n.news_title', 'c.name','u.nick', 'n.news_status');
        # Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        # And set the the default sort column and order.
        $columnsort->setDefault('n.created_at', 'desc');
        # Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        # if cat is no set, we need a query to show all news regardless which category,
        if(empty($category))
        {
            $newsQuery = Doctrine::getTable('CsNews')->fetchAllNews($currentPage, $resultsPerPage, true);
        }
        else # else we need a qry with the where(cat) statement
        {
            $newsQuery = Doctrine::getTable('CsNews')->fetchNewsByCategory($category, $currentPage, $resultsPerPage, true);
        }

        # this is not needed, but for showing, that $newsQuery is an array
        $news           = $newsQuery['news'];
        $pager          = $newsQuery['pager'];
        $pager_layout   = $newsQuery['pager_layout'];

        $newscategories = Doctrine::getTable('CsNews')->fetchUsedNewsCategories();

        # Get Render Engine
        $smarty = $this->getView();

        $smarty->assign('news', $news);
        $smarty->assign('newscategories', $newscategories);

        // Return true if it's necessary to paginate or false if not
        $smarty->assign('pagination_needed',$pager->haveToPaginate());

        // Pagination
        $smarty->assign_by_ref('pager', $pager);
        $smarty->assign_by_ref('pager_layout', $pager_layout);

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # specifiy the template manually
        #$this->setTemplate('news/admin_show.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Deletes News
     */
    function action_admin_delete()
    {
        $request = $this->getHttpRequest();
        $delete  = $request->getParameter('delete');          
        $numDeleted = Doctrine_Query::create()->delete('CsNews')->whereIn('news_id', $delete)->execute();        
        $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin', 2, 302, _( $numDeleted. ' News deleted.'));        
    }

    /**
     * Debugging Action
     * testformgenerator
     */
    function action_admin_testformgenerator()
    {
        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';
        # Create a new form

        $form = new Clansuite_Form('news_create_form', 'POST', 'upload-file.php');
        $form->setId('news_create_form')
             ->setTarget('hidden_upload')
             ->setHeading('News Create Form')
             ->setEncoding('multipart/form-data')
             ->setDescription('My news create form...');

        # Assign some Formlements
        /*$form->addElement('captcha')->setLabel('captcha label');

        $form->addElement('checkbox')->setLabel('checkbox label');
        $form->addElement('checkboxlist')->setLabel('checkboxlist label');
        $form->addElement('confirmsubmitbutton')->setLabel('confirmsubmitbutton label');
        */

        # you can specify several uploadTypes: uploadify, apc, ajaxupload
        # or no uploadType at all (for default upload)
        #$form->addElement('file')->setUploadType('uploadify')->setLabel('file upload label');
/*
        $form->addElement('jqconfirmsubmitbutton')->setFormId('news_create_form')->setLabel('jqconfirmsubmitbutton label');

        $form->addElement('jqselectdate')->setLabel('jqselectdate label'); #->setFormId('news_create_form')

        $form->addElement('hidden')->setLabel('hidden label');

        $form->addElement('radio')->setLabel('radio label');
        $form->addElement('radiolist')->setLabel('radiolist label');

        $form->addElement('selectcountry');
        $form->addElement('selectyesno');
*/
        $form->addElement('text')->setLabel('text label');
        $form->addElement('textarea')->setCols('70')->setLabel('textarea label');

        #$form->addElement('submitbutton')->setValue('Submit')->setLabel('Submit Button')->setClass('ButtonGreen');
        #$form->addElement('resetbutton')->setValue('Reset')->setLabel('Reset Button');
/*
        $form->addElement('imagebutton')->setValue('Reset')->setLabel('Image Button'); # setSource
*/
        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    /**
     * Create News
     */
    function action_admin_create()
    {
        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        /**
         * Create a new form
         */
        $form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update');

        /**
         * Assign some Formlements
         */
        $form->addElement('text')->setName('news_form[title]')->setLabel(_('Title'));
        $categories = Doctrine::getTable('CsNews')->fetchAllNewsCategoriesDropDown();
        $form->addElement('multiselect')->setName('news_form[category]')->setLabel(_('Category'))->setOptions($categories);
        $form->addElement('textarea')->setName('news_form[body]')->setID('news_form[body]')->setCols('110')->setRows('30')->setLabel(_('Your Article:'));
        $form->addElement('submitbutton')->setValue('Submit')->setLabel('Submit Button')->setClass('ButtonGreen');
        $form->addElement('resetbutton')->setValue('Reset')->setLabel('Reset Button');

        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    /**
     * Edit News
     */
    function action_admin_edit()
    {
        # get id
        $news_id = $this->getHttpRequest()->getParameter('id');

        # fetch news
        $news = Doctrine::getTable('CsNews')->fetchSingleNews($news_id);

        #clansuite_xdebug::printR($news);

        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        /**
         * Create a new form
         */
        # @todo form object with auto-population of values
        #$form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update', $news);

        $form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update');

        /**
         * news_id as hidden field
         */
        $form->addElement('hidden')->setName('news_form[news_id]')->setValue($news['news_id']);

        /**
         * Assign some Formlements
         */
        $form->addElement('text')->setName('news_form[news_title]')->setLabel(_('Title'))->setValue($news['news_title']);
        $categories = Doctrine::getTable('CsNews')->fetchAllNewsCategoriesDropDown();
        $form->addElement('multiselect')->setName('news_form[cat_id]')->setLabel(_('Category'))->setOptions($categories)->setDefaultValue($news['cat_id']);
        $form->addElement('textarea')->setName('news_form[news_body]')->setID('news_form[news_body]')->setCols('110')->setRows('30')->setLabel(_('Your Article:'))->setValue($news['news_body']);;
        $form->addElement('submitbutton')->setValue('Submit')->setLabel('Submit Button')->setClass('ButtonGreen');
        $form->addElement('resetbutton')->setValue('Reset')->setLabel('Reset Button');
        

        # Debugging Form Object
        #clansuite_xdebug::printR($form);

        # Debugging Form HTML Output
        #clansuite_xdebug::printR($form->render());

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->prepareOutput();
    }

    /**
     * Update a News Entry identified by news_id
     */
    function action_admin_update()
    {
        # get incoming data
        $data = $this->getHttpRequest()->getParameter('news_form');

        # @todo validation
         
        # get the news table
        $newsTable = Doctrine::getTable('CsNews');
        
        # fetch the news to update by news_id
        $news = $newsTable->findOneByNews_Id($data['news_id']);
       
        # if that news exist, update values and save
        if ($news !== false)
        {     
            $news->news_id    = $data['news_id'];
            $news->news_title = $data['news_title'];
            $news->news_body  = $data['news_body'];
            $news->cat_id     = $data['cat_id'];
            #$news['news_status']     = $data['news_form']['status'];
            $news->save();
        }

        # redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin', 2, 302, _('The news has been edited.'));
    }

    /**
     * Edit news
     *
     * @global $db
     * @global $lang
     * @global $functions
     * @global $input
     * @global $tpl
     * @global $cfg
     * @global $perms
     */
    function edit()
    {
        global $db, $functions, $input, $lang, $tpl, $cfg, $perms;

        // Permission check
        if( $perms->check('cc_edit_news', 'no_redirect') == true )
        {

            // Incoming Vars
            $submit = isset($_POST['submit']) ? $_POST['submit'] : '';
            $infos = $_POST['infos'];
            $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
            $front  = isset($_GET['front']) ? $_GET['front'] : 0;

            // check for fills
            if( ( empty($infos['title']) OR
                empty($infos['news_body']) )
                AND !empty($submit) )
            {
                $err['fill_form'] = 1;
            }

            // Create news in DB
            if( !empty($submit) )
            {
                if( count($err) == 0 )
                {
                    // build groups
                    /*
                    foreach( $infos['groups'] as $key => $value )
                    {
                        $groups .= $value . ',';
                    }
                    $groups = substr( $groups, 0, -1 );
                    */

                    // Query DB
                    $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'news SET news_title = ?, news_body = ?, cat_id = ?, user_id = ?, draft = ? WHERE news_id = ?' );
                    $stmt->execute( array(  $infos['title'],
                                            $infos['news_body'],
                                            $infos['cat_id'],
                                            $_SESSION['user']['user_id'],
                                            $infos['draft'],
                                            //$groups ) );
                                            $id ) );

                    if( $infos['front'] == 1 )
                    {
                        // Redirect on finish
                        $functions->redirect( 'index.php?mod=news&action=show', 'metatag|newsite', 3, $lang->t( 'The news has been edited.' ) );
                    }
                    else
                    {
                        // Redirect on finish
                        $functions->redirect( 'index.php?mod=news&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The news has been edited.' ), 'admin' );
                    }
                }
                else
                {
                    $functions->redirect( 'index.php?mod=news&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'ERROR: Please fill all fields!' ), 'admin' );
                }

            }

            // Get all groups
            /*
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups' );

            $stmt->execute();
            $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
            */

            // get infos
            $stmt = $db->prepare('SELECT * FROM ' . DB_PREFIX . 'news WHERE news_id = ?');
            $stmt->execute( array( $id ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);

            // $categories for module_news
            $stmt = $db->prepare( 'SELECT cat_id, name FROM ' . DB_PREFIX . 'categories WHERE module_id = ?' );
            $stmt->execute( array ( $cfg->modules['news']['module_id'] ) );
            $newscategories = $stmt->fetchAll(PDO::FETCH_NAMED);

            // give $newslist array to Smarty for template output
            $tpl->assign('newscategories', $newscategories);

            // Load FCK
            require( ROOT_CORE . '/fckeditor/fckeditor_php5.php' );
            $fck = new FCKeditor('infos[news_body]');
            $fck->Height = '450';
            $fck->Value = $result['news_body'];
            $fck_html = $fck->CreateHtml();

            // Output Stuff
            $tpl->assign( 'front'       , $front );
            $tpl->assign( 'fck'         , $fck_html );
            $tpl->assign( 'infos'       , $result );
            $tpl->assign( 'err'         , $err);
            //$tpl->assign( 'all_groups'  , $all_groups);
            $this->output .= $tpl->fetch('news/admin_edit.tpl');
        }
        else
        {
            $this->output = $lang->t('You do not have sufficient rights.') . '<br /><input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="Abort"/>';
        }
        $this->suppress_wrapper = 1;
    }

    /**
     * Show a single news
     *
     * @global $db
     * @global $lang
     * @global $functions
     * @global $input
     * @global $tpl
     * @global $cfg
     * @global $perms
     */
    function show_single()
    {
        global $db, $functions, $input, $lang, $tpl, $cfg, $perms;

        // Incoming vars
        $news_id = $_GET['id'];

        if( $perms->check('cc_view_news', 'no_redirect') == true )
        {
            $stmt = $db->prepare('SELECT news_body FROM ' . DB_PREFIX . 'news WHERE news_id = ?');
            $stmt->execute( array( $news_id ) );
            $result = $stmt->fetch( PDO::FETCH_NAMED );
            $this->output =  $result['news_body'];
        }
        else
        {
            $this->output .= $lang->t('You are not allowed to view single news.');
        }
        $this->suppress_wrapper = 1;
    }

    /**
     * Action for displaying the Settings of a Module News
     */
    function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Settings'), '/index.php?mod=news&amp;sub=admin&amp;action=settings');

        $settings = array();

        $settings['form']   = array(    'name' => 'news_settings',
                                        'method' => 'POST',
                                        'action' => WWW_ROOT.'/index.php?mod=news&amp;sub=admin&amp;action=settings_update');

        $settings['news'][] = array(    'id' => 'resultsPerPage_show',
                                        'name' => 'resultsPerPage_show',
                                        'description' => _('Newsitems to show in Newsmodule'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_show', '3'));

        $settings['news'][] = array(    'id' => 'items_newswidget',
                                        'name' => 'items_newswidget',
                                        'description' => _('Newsitems to show in LatestNews Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('items_newswidget', '5'));

        $settings['news'][] = array(    'id' => 'resultsPerPage_fullarchive',
                                        'name' => 'resultsPerPage_fullarchive',
                                        'description' => _('Newsitems to show in Newsarchive'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_fullarchive', '3'));

        $settings['news'][] = array(    'id' => 'resultsPerPage_adminshow',
                                        'name' => 'resultsPerPage_adminshow',
                                        'description' => _('Newsitems to show in the administration area.'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_adminshow', '10'));


        $settings['news'][] = array(    'id' => 'resultsPerPage_archive',
                                        'name' => 'resultsPerPage_archive',
                                        'description' => _('Newsitems to show in Newsarchive'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_archive', '3'));

        $settings['news'][] = array(    'id' => 'feed_format',
                                        'name' => 'feed_format',
                                        'description' => _('Set the default format of the news feed. You can chose among these options: RSS2.0, MBOX, OPML, ATOM, HTML, JS'),
                                        'formfieldtype' => 'multiselect',
                                        'value' => array( 'selected' => $this->getConfigValue('feed_format', 'RSS2.0'),
                                                          'RSS2.0'   => 'RSS2.0',
                                                          'MBOX'     => 'MBOX',
                                                          'OPML'     => 'OPML',
                                                          'ATOM'     => 'ATOM',
                                                          'HTML'     => 'HTML',
                                                          'JS'       => 'JS'));

        $settings['news'][] = array(    'id' => 'feed_items',
                                        'name' => 'feed_items',
                                        'description' => _('Sets the default number of feed items.'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('feed_items', '10'));

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
        $data = $this->getHttpRequest()->getParameter('news_settings');

        # Get Configuration from Injector
        $config = $this->injector->instantiate('Clansuite_Config');

        # write config
        $config->confighandler->writeConfig( ROOT_MOD . 'news'.DS.'news.config.php', $data);

        # clear the cache / compiled tpls
        # $this->getView()->clear_all_cache();
        $this->getView()->clear_compiled_tpl();

        # Redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin', 2, 302, 'The config file has been succesfully updated.');
    }
}
?>
