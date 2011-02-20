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
    * @copyright  Jens-André Koch (2005-onwards)
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
 * Clansuite_Module_News_Admin
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  News
 */
class Clansuite_Module_News_Admin extends Clansuite_Module_Controller
{
    public $publishing_status_map = array();

    public function initializeModule()
    {
        parent::initModel('news');
        parent::initModel('users');
        parent::initModel('categories');

        $this->publishing_status_map = array(
            '0' => _('Not published'),
            '1' => _('Draft'),
            '2' => _('Private'),
            '3' => _('Pending review'),
            '4' => _('Published')
        );
    }

    /**
     * Clansuite_Module_News_Admin -> action_admin_show()
     *
     * Show all news entries and give the possibility to edit/delete
     * Show DropDown with possibility to select the news category
     */
    public function action_admin_show()
    {
        # Get Render Engine
        $view = $this->getView();

        //--------------------------
        // Datagrid configuration
        //--------------------------

        $ColumnSets = array();

        $ColumnSets[] = array(  'Alias'     => 'Select',
                                'ResultSet' => 'news_id',
                                'Name'      => '[x]',
                                'Type'      => 'Checkbox' );

        $ColumnSets[] = array(  'Alias'     => 'Title',
                                'ResultSet' => array(   'name'      => 'news_title',
                                                        'id'        => 'news_id',
                                                        'comments'  => 'nr_news_comments' ),
                                'Name'      => _('Title'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'news_title',
                                'Type'      => 'Link' );

        $ColumnSets[] = array(  'Alias'     => 'Preview',
                                'ResultSet' => array(  'preview' => 'news_preview',
                                                       'body'    => 'news_body' ),
                                'Name'      => _('Preview'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'news_body',
                                'Type'      => 'String' );

        $ColumnSets[] = array(  'Alias'     => 'Date',
                                'ResultSet' => 'created_at',
                                'Name'      => _('Created at'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'created_at',
                                'Type'      => 'Date' );

        $ColumnSets[] = array(  'Alias'     => 'Category',
                                'ResultSet' => 'CsCategories.name',
                                'Name'      => _('Category'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'c.name',
                                'Type'      => 'String' );

        $ColumnSets[] = array(  'Alias'     => 'Status',
                                'ResultSet' => 'news_status',
                                'Name'      => _('Status'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'news_status',
                                'Type'      => 'Integer' );

        $ColumnSets[] = array(  'Alias'     => 'User',
                                'ResultSet' => array('CsUsers.email','CsUsers.nick'),
                                'Name'      => _('User'),
                                'Sort'      => 'DESC',
                                'SortCol'   => 'u.nick',
                                'Type'      => 'Email' );

        /*$ColumnSets[] = array(  'Alias'     => 'Action',
                                'ResultSet' => 'news_id',
                                'Name'      => _('Action'),
                                'Type'      => 'Editbutton' );*/

        $BatchActions = array();
        $BatchActions[] = array(    'Alias'     => 'create',
                                    'Name'      => _('Create a news'),
                                    'Action'    => 'create' );

        $BatchActions[] = array(    'Alias'     => 'delete',
                                    'Name'      => _('Delete selected items'),
                                    'Action'    => 'delete' );

        # Instantiate the datagrid
        $datagrid = new Clansuite_Datagrid( array(
                # Set only the name manually
                #'Entity'       => 'Entities\News',
                # Set the name automatically
                'Entity'        => $this->getEntityNameFromClassname(),
                # Table Column Descriptions
                'ColumnSets'    => $ColumnSets,
                # Pager URL
                'Url'           => '?mod=news&sub=admin'
        ) );

        $datagrid->setBatchActions( $BatchActions );

        $datagrid->getColumn('Select')->disableFeature('Search');

        $datagrid->getColumn('Title')->getRenderer()->linkFormat  = '&action=edit&id=%{id}';
        $datagrid->getColumn('Title')->getRenderer()->linkTitle   = _('Edit this news');
        $datagrid->getColumn('Title')->getRenderer()->nameFormat  = '%{name} - %{comments} Comment(s)';

        $datagrid->getColumn('Preview')->disableFeature('Sorting');
        $datagrid->getColumn('Preview')->getRenderer()->stringFormat = '<div title="%{body}">%{preview}</div>';

        $datagrid->modifyResultSetViaHook($this, 'manipulateResultSet');

        $datagrid->setResultsPerPage(self::getConfigValue('resultsPerPage_adminshow', '5'));

        # Assing datagrid
        $view->assign('datagrid', $datagrid->render());

        $this->display();
    }

    /**
     * Hook for manipulating database values
     *
     * @param array $resultSet
     */
    public function manipulateResultSet(&$_ArrayReference)
    {
        /**
         * map news_status from "integer" to "string"
         */
        if(isset($this->publishing_status_map[$_ArrayReference['news_status']]))
        {
            $_ArrayReference['news_status'] = $this->publishing_status_map[$_ArrayReference['news_status']];
        }

        /**
         * shorten news_preview if body longer than 50 chars
         */
        $wrapLength = 50;
        $_ArrayReference['news_body'] = htmlspecialchars(strip_tags($_ArrayReference['news_body']));
        if( strlen($_ArrayReference['news_body']) > $wrapLength )
        {
            $_ArrayReference['news_preview'] = substr($_ArrayReference['news_body'],0,$wrapLength) . '...';
        }
        else
        {
            $_ArrayReference['news_preview'] = $_ArrayReference['news_body'];
        }
    }

    /**
     * action_admin_create
     */
    public function action_admin_create()
    {
        # Create a new form
        $form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update&type=create');
        $form->setClass('News');

        # Assign some formlements
        $form->addElement('text')->setName('news_form[news_title]')->setLabel(_('Title'));
        $categories = $this->getModel()->fetchAllNewsCategoriesDropDown();
        $form->addElement('multiselect')->setName('news_form[cat_id]')->setLabel(_('Category'))
                ->setOptions($categories);
        $form->addElement('multiselect')->setName('news_form[news_status]')->setLabel(_('Status'))
                ->setOptions($this->publishing_status_map)->setDefaultValue("0");
        $form->addElement('textarea')->setName('news_form[news_body]')->setID('news_form[news_body]')
                ->setCols('100')->setRows('40')->setLabel(_('Your Article:'))
                ->setEditor();

        # add the buttonbar
        $form->addElement('buttonbar')->getButton('cancelbutton')->cancelURL = 'index.php?mod=news&sub=admin';

        # Assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    /**
     * Edit News
     *
     * @todo autoloader/di for forms
     */
    public function action_admin_edit()
    {
        # get id
        $news_id = $this->request->getParameter('id');

        # fetch news
        $news = $this->getModel()->fetchSingleNews($news_id);

        # utf8 handling
        $news['news_title'] = mb_convert_encoding( $news['news_title'] , 'UTF-8', 'HTML-ENTITIES');
        $news['news_body'] = mb_convert_encoding( $news['news_body'] , 'UTF-8', 'HTML-ENTITIES');

        # Create a new form
        $form = new Clansuite_Form('news_form', 'post', 'index.php?mod=news&sub=admin&action=update&type=edit');

        # news_id as hidden field
        $form->addElement('hidden')->setName('news_form[news_id]')->setValue($news['news_id'])->setDecorator('none');

        # user_id as hidden field
        $form->addElement('hidden')->setName('news_form[user_id]')->setValue($news['user_id'])->setDecorator('none');

        $form->addElement('text')->setName('news_form[news_title]')->setLabel(_('Title'))->setValue($news['news_title']);
        $categories = $this->getModel()->fetchAllNewsCategoriesDropDown();
        $form->addElement('multiselect')->setName('news_form[cat_id]')->setLabel(_('Category'))
                ->setOptions($categories)->setDefaultValue($news['cat_id']);
        $form->addElement('multiselect')->setName('news_form[news_status]')->setLabel(_('Status'))
                ->setOptions($this->publishing_status_map)->setDefaultValue($news['news_status']);
        $form->addElement('textarea')->setName('news_form[news_body]')->setID('news_form[news_body]')
                ->setCols('110')->setRows('30')->setLabel(_('Your Article:'))->setValue($news['news_body'])
                ->setEditor('nicedit');

        # add the buttonbar
        $form->addElement('buttonbar')->getButton('cancelbutton')->cancelURL = 'index.php?mod=news/admin';

        # assign the html of the form to the view
        $this->getView()->assign('form', $form->render());

        $this->display();
    }

    /**
     * Update a News Entry identified by news_id
     *
     * @todo validation
     */
    public function action_admin_update()
    {
        # get incoming data
        $data = $this->request->getParameter('news_form');
        $type = $this->request->getParameter('type', 'GET');

        if(isset($type) and $type == 'create')
        {
            $news = new CsNews();
            $news->news_title   = $data['news_title'];
            $news->news_body    = $data['news_body'];
            $news->cat_id       = $data['cat_id'];
            $news->user_id      = $_SESSION['user']['user_id'];
            $news->news_status  = $data['news_status'];
            $news->save();

            # redirect
            $this->response->redirectNoCache('/news/admin', 2, 302, _('The news has been created.'));
        }
        elseif(isset($type) and $type == 'edit')
        {
            # fetch the news to update by news_id
            $news = $this->getModel()->findOneByNews_Id($data['news_id']);

            # if that news exist, update values and save
            if ($news !== false)
            {
                $news->news_title   = $data['news_title'];
                $news->news_body    = $data['news_body'];
                $news->cat_id       = $data['cat_id'];
                $news->user_id      = $data['user_id'];
                $news->news_status  = $data['news_status'];
                $news->save();
            }
            else
            {
                # redirect
                $this->response->redirectNoCache('/news/admin', 2, 302, _('The news doesn\'t exist anymore.'));
            }

            # redirect
            $this->response->redirectNoCache('/news/admin', 2, 302, _('The news has been edited.'));
        }
        else
        {
            # redirect
            $this->response->redirectNoCache('/news/admin', 2, 302, _('Unknown Formaction.'));
        }
    }

    /**
     * Deletes News
     */
    public function action_admin_delete()
    {
        $aDelete  = $this->request->getParameter('Checkbox');

        if(isset($aDelete) && is_array($aDelete))
        {
            $numDeleted = 0;
            foreach( $aDelete as $id )
            {
                $numDeleted += Doctrine_Query::create()->delete('CsNews')->whereIn('news_id', $id)->execute();
            }
            $this->response->redirectNoCache('/news/admin', 2, 302, $numDeleted . _(' News deleted.'));
        }
        else
        {
           $this->response->redirectNoCache('/news/admin');
        }
    }

    /**
     * Action for displaying the Settings of a Module News
     */
    public function action_admin_settings()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Breadcrumb::add( _('Settings'), '/news/admin/settings');

        $this->loadForm();
        $this->display();
    }

    public function action_admin_settings_update()
    {
        $data = $this->request->getParameter('news_settings');

        # Get Configuration from Injector and write Config
        $this->getInjector()->instantiate('Clansuite_Config')->writeModuleConfig($data);

        # clear the cache / compiled tpls
        $this->getView()->clearCache();

        # Redirect
        $this->response->redirectNoCache('/news/admin', 2, 302, _('success#The config file has been successfully updated.'));
    }
}
?>