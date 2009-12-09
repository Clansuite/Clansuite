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
        #Clansuite_Trail::addStep( _('Show'), 'index.php?mod=news&amp;sub=admin&amp;action=show');

        # Incoming Variables
        $request = $this->getHttpRequest();
        $category       = (int) $request['news_category_form']['cat_id'];
        $currentPage    = (int) $request->getParameter('page');
        $resultsPerPage = (int) $this->getConfigValue('resultsPerPage_adminshow', '10');

        # SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . 'smarty/libs/SmartyColumnSort.class.php');
        # A list of database columns to use in the table.
        $columns = array( 'n.created_at', 'n.news_title', 'c.name', 'u.nick', 'n.news_status');
        # Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        # And set the the default sort column and order.
        $columnsort->setDefault('n.created_at', 'desc');
        # Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        # if cat is no set, we need a query to show all news regardless which category,
        if(empty($category))
        {
            $newsQuery = Doctrine::getTable('CsNews')->fetchAllNews($currentPage, $resultsPerPage, true, $sortorder);
        }
        else # else we need a qry with the where(cat) statement
        {
            $newsQuery = Doctrine::getTable('CsNews')->fetchNewsByCategory($category, $currentPage, $resultsPerPage, true, $sortorder);
        }

        # import array variables into the current symbol table ($newsQuery is an array('news','pager','pager_layout')
        extract($newsQuery);
        unset($newsQuery);

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
     * Create News
     */
    function action_admin_create()
    {
        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        # Create a new form
        $form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update&type=create');

        # Assign some formlements
        $form->addElement('text')->setName('news_form[news_title]')->setLabel(_('Title'));
        $categories = Doctrine::getTable('CsNews')->fetchAllNewsCategoriesDropDown();
        $form->addElement('multiselect')->setName('news_form[cat_id]')->setLabel(_('Category'))->setOptions($categories);
        $form->addElement('textarea')->setName('news_form[news_body]')->setID('news_form[news_body]')->setCols('110')->setRows('30')->setLabel(_('Your Article:'));
        $form->addElement('submitbutton');
        $form->addElement('resetbutton');
		$form->addElement('cancelbutton');

        # Assign the html of the form to the view
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

        # Load Form Class (@todo autoloader / di)
        require ROOT_CORE . 'viewhelper/form.core.php';

        # Create a new form
        # @todo form object with auto-population of values
        #$form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update', $news);

        $form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update&type=edit');

        /**
         * news_id as hidden field
         */
        $form->addElement('hidden')->setName('news_form[news_id]')->setValue($news['news_id']);

        # Assign some formlements
        $form->addElement('text')->setName('news_form[news_title]')->setLabel(_('Title'))->setValue($news['news_title']);
        $categories = Doctrine::getTable('CsNews')->fetchAllNewsCategoriesDropDown();
        $form->addElement('multiselect')->setName('news_form[cat_id]')->setLabel(_('Category'))->setOptions($categories)->setDefaultValue($news['cat_id']);
        $form->addElement('textarea')->setName('news_form[news_body]')->setID('news_form[news_body]')->setCols('110')->setRows('30')->setLabel(_('Your Article:'))->setValue($news['news_body']);;
		$form->addElement('submitbutton')->setValue('Submit');
        $form->addElement('resetbutton')->setValue('Reset');
		$form->addElement('cancelbutton');

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
        $type = $this->getHttpRequest()->getParameter('type', 'G');

        if(isset($type) and $type == 'create')
        {
            $news = new CsNews;
            $news->news_title = $data['news_title'];
            $news->news_body  = $data['news_body'];
            $news->cat_id     = $data['cat_id'];
            #$news['news_status']     = $data['news_form']['status'];
            $news->save();
        }
        elseif(isset($type) and $type == 'edit')
        {
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
        }
        else
        {
            # redirect
            $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin', 2, 302, _('Unknown Formaction.'));
        }

        # redirect
        $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin', 2, 302, _('The news has been edited.'));
    }

    /**
     * Deletes News
     */
    function action_admin_delete()
    {
        $request = $this->getHttpRequest();
        $delete  = $request->getParameter('delete');
        
        if(isset($delete))
        {
            $numDeleted = Doctrine_Query::create()->delete('CsNews')->whereIn('news_id', $delete)->execute();
            $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin', 2, 302, $numDeleted . _(' News deleted.'));
        }
        else
        {
           $this->getHttpResponse()->redirectNoCache('index.php?mod=news&amp;sub=admin');
        }
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
                                        'label' => 'News Items',
                                        'description' => _('Newsitems to show in Newsmodule'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_show', '3'));

        $settings['news'][] = array(    'id' => 'items_newswidget',
                                        'name' => 'items_newswidget',
                                        'label' => 'LatestNews Items',
                                        'description' => _('Newsitems to show in LatestNews Widget'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('items_newswidget', '5'));

        $settings['news'][] = array(    'id' => 'resultsPerPage_fullarchive',
                                        'name' => 'resultsPerPage_fullarchive',
                                        'label' => 'Newsarchive Items',
                                        'description' => _('Newsitems to show in Newsarchive'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_fullarchive', '3'));

        $settings['news'][] = array(    'id' => 'resultsPerPage_adminshow',
                                        'name' => 'resultsPerPage_adminshow',
                                        'label' => 'Admin News Items',
                                        'description' => _('Newsitems to show in the administration area.'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_adminshow', '10'),
                                        'validationrules' => array('int'),
                                        'errormessage' => 'Please use digits!');


        $settings['news'][] = array(    'id' => 'resultsPerPage_archive',
                                        'name' => 'resultsPerPage_archive',
                                        'label' => 'Newsarchive Widget Items',
                                        'description' => _('Newsitems to show in Newsarchive'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('resultsPerPage_archive', '3'));

        $settings['news'][] = array(    'id' => 'feed_format',
                                        'name' => 'feed_format',
                                        'label' => 'Newsfeed Format',
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
                                        'label' => 'Newsfeed Items',
                                        'description' => _('Sets the default number of feed items.'),
                                        'formfieldtype' => 'text',
                                        'value' => $this->getConfigValue('feed_items', '10'));

        # fetch the formgenerator
        require ROOT_CORE . '/viewhelper/formgenerator.core.php';

        # fill the settings array into the formgenerator
        $form = new Clansuite_Array_Formgenerator($settings);
        # add additional buttons to the form
        $form->addElement('submitbutton')->setName('Save');
        $form->addElement('resetbutton');

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