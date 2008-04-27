<?php
/**
* new.admin.php
* Modul - News - Admin Interface
*
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2005-2007 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-André Koch
* @copyright  Clansuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


/**
 * Clansuite
 *
 * Module:      News 
 * Submodule:   Admin
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006 - 2007)
 * @since      Class available since Release 1.0alpha
 *
 * @package     clansuite
 * @category    module
 * @subpackage  news
 */
class Module_News_Admin extends ModuleController implements Clansuite_Module_Interface
{
    public function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }
    
    /**
    * @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
    * @desc Loads necessary language files
    */

    function auto_run()
    {
        global $lang, $trail, $perms;
        $params = func_get_args();

        // Permission check
        $perms->check('cc_access');

        // Set Pagetitle and Breadcrumbs
        $trail->addStep($lang->t('News'), '/index.php?mod=news&amp;sub=admin');

        switch ($_REQUEST['action'])
        {
            default:
            case 'show':
                $trail->addStep($lang->t('Show'), '/index.php?mod=news&amp;sub=admin&amp;action=show');
                $this->show();
                break;

            case 'instant_show':
                $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
                break;

            case 'create':
                $trail->addStep($lang->t('Create News'), '/index.php?mod=news&amp;sub=admin&amp;action=show&amp;action=create');
                $this->create();
                break;

            case 'edit':
                $trail->addStep($lang->t('Edit News'), '/index.php?mod=news&amp;sub=admin&action=show&amp;action=edit');
                $this->edit();
                break;

            case 'delete':
                $trail->addStep($lang->t('Delete News'), '/index.php?mod=news&amp;sub=admin&action=show&amp;action=delete');
                $this->delete();
                break;

            case 'show_single':
                $this->show_single();
                break;
        }

        return array( 'OUTPUT'          => $this->output,
                      'ADDITIONAL_HEAD' => $this->additional_head,
                      'SUPPRESS_WRAPPER'=> $this->suppress_wrapper );
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
        trail::addstep( _('Show'), '/index.php?mod=news&amp;sub=admin&amp;action=show');
        
        # Incoming Variables
        $cat_id      = (int) $this->injector->instantiate('httprequest')->getParameter('cat_id');
        // add cat_id to select statement if set, else empty
        #$sql_cat = $cat_id == 0 ? 0 : $cat_id;
        $currentPage = (int) $this->injector->instantiate('httprequest')->getParameter('page');
        $resultsPerPage = (int) 10;  
                
        # Load DBAL
        $db = $this->injector->instantiate('clansuite_doctrine');
        $db->doctrine_initialize();
        
        # Load Models (automatic + lazy loading)
        Doctrine::loadModels(ROOT . '/myrecords/', Doctrine::MODEL_LOADING_CONSERVATIVE);  
        
        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'n.news_added', 'n.news_title', 'c.name','u.nick', 'n.draft');
        // Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('n.news_added', 'desc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default
        
        # Display ALL NEWS
        if($cat_id == 0)
        {
            # Creating Pager Object with a Query Object inside
            $pager_layout = new Doctrine_Pager_Layout(
                            new Doctrine_Pager(
                                Doctrine_Query::create()
                                        ->select('n.*, u.nick, u.user_id, c.name, c.image')
                                        ->from('CsNews n')
                                        ->leftJoin('n.CsUsers u')
                                        ->leftJoin('n.CsCategories c')
                                       #->where('c.module_id = 7')
                                       #->setHydrationMode(Doctrine::HYDRATE_ARRAY)                                      
                                        ->orderby($sortorder),
                                         # The following is Limit  ?,? =
                                         $currentPage, // Current page of request
                                         $resultsPerPage // (Optional) Number of results per page Default is 25
                                 ),
                                 new Doctrine_Pager_Range_Sliding(array(
                                     'chunk' => 5
                                 )),
                                 '?mod=news&sub=admin&action=show&page={%page}'
                                 );
            
            # Assigning templates for page links creation
            $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
            $pager_layout->setSelectedTemplate('[{%page}]');
            # Retrieving Doctrine_Pager instance
            $pager = $pager_layout->getPager();                    
                                 
            // Fetching news
            #var_dump($pager->getExecuted());
            $newsarchiv = $pager->execute(array(), Doctrine::FETCH_ARRAY);
        }
        # Display News ordered by Category
        else
        {
            # Creating Pager Object with a Query Object inside
            $pager_layout = new Doctrine_Pager_Layout(
                            new Doctrine_Pager(
                                Doctrine_Query::create()
                                        ->select('n.*, u.nick, u.user_id, c.name, c.image')
                                        ->from('CsNews n')
                                        ->leftJoin('n.CsUsers u')
                                        ->leftJoin('n.CsCategories c')
                                       #->where('c.module_id = 7')
                                       #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                        ->where('n.cat_id = ?')
                                        ->orderby($sortorder),
                                         # The following is Limit  ?,? =
                                         $currentPage, // Current page of request
                                         $resultsPerPage // (Optional) Number of results per page Default is 25
                                 ),
                                 new Doctrine_Pager_Range_Sliding(array(
                                     'chunk' => 5
                                 )),
                                 '?mod=news&sub=admin&action=show&page={%page}'
                                 );
                                 
            # Assigning templates for page links creation
            $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
            $pager_layout->setSelectedTemplate('[{%page}]');
            # Retrieving Doctrine_Pager instance
            $pager = $pager_layout->getPager();  
                               
             // Fetching news
            #var_dump($pager->getExecuted());
            $newsarchiv = $pager->execute(array($cat_id), Doctrine::FETCH_ARRAY);
        }

        // Get all $categories for module_news
        $newscategories = Doctrine_Query::create()
                               ->select('cat_id, name')
                               ->from('CsCategories c')
                               ->where('c.module_id = 7')
                              #->where('c.module_id = ?);
                         #$stmt->execute( array ( $cfg->modules['news']['module_id'] ) );
                               ->execute(array(), Doctrine::HYDRATE_ARRAY);
        
        # Get Render Engine
        $smarty = $this->getView();        
        
        #$smarty->assign('news', $news->toArray());
        $smarty->assign('newsarchiv', $newsarchiv);
        $smarty->assign('newscategories', $newscategories);

        // Return true if it's necessary to paginate or false if not
        $smarty->assign('pagination_needed',$pager->haveToPaginate());

        // Displaying page links
        // Displays: [1][2][3][4][5]
        // With links in all pages, except the $currentPage (our example, page 1)
        // display 2 parameter = true = only return, not echo the pager template.
        $smarty->assign('pagination_links',$pager_layout->display('',true));

        $smarty->assign('paginate_totalitems',$pager->getNumResults()); #  total number of items found on query search
        $smarty->assign('paginate_resultsperpage',$pager->getResultsInPage()); #  current Page

        // Return the total number of pages
        $smarty->assign('paginate_lastpage',$pager->getLastPage());
        // Return the current page
        $smarty->assign('paginate_currentpage',$pager->getPage());
        
        # Set Layout Template
        $this->getView()->setLayoutTemplate('admin/index.tpl');
        # specifiy the template manually
        #$this->setTemplate('news/admin_show.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
    * Deletes a news with questioning
    *
    * @global $db
    * @global $lang
    * @global $functions
    * @global $input
    * @global $perms
    */
    function delete()
    {
        global $db, $functions, $input, $lang, $perms;

        // Permission check
        $perms->check('cc_edit_news');

        /**
         * @desc Init
         */
        $submit     = $_POST['submit'];
        $confirm    = $_POST['confirm'];
        $abort      = $_POST['abort'];
        $ids        = isset($_POST['ids'])      ? $_POST['ids'] : array();
        $ids        = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['ids'])) : $ids;
        $delete     = isset($_POST['delete'])   ? $_POST['delete'] : array();
        $delete     = isset($_POST['confirm'])  ? unserialize(urldecode($_GET['delete'])) : $delete;
        $front      = isset($_GET['front'])     ? $_GET['front'] : 0;

        if ( count($delete) < 1 )
        {
            $functions->redirect( 'index.php?mod=news&sub=admin', 'metatag|newsite', 3, $lang->t( 'No news selected to delete! Aborted... ' ), 'admin' );
        }

        /**
         * @desc Abort
         */
        if ( !empty( $abort ) )
        {
            $functions->redirect( 'index.php?mod=news&sub=admin' );
        }

        /**
         * @desc Create Select Statement
         */
        $select = 'SELECT news_id, news_title FROM ' . DB_PREFIX . 'news WHERE ';
        foreach ( $delete as $key => $id )
        {
            $select .= 'news_id = ' . (int) $id . ' OR ';
        }
        /**
         * dirty select statement creation, by cutting off the last OR
         */
        $select = substr($select, 0, -4);
        $stmt = $db->prepare( $select );
        $stmt->execute();
        while( $result = $stmt->fetch(PDO::FETCH_ASSOC) )
        {
            if( in_array( $result['news_id'], $delete  ) )
            {
                $names .= '<br /><b>' .  $result['news_title'] . '</b>';
            }
            $all[] = $result;
        }

        /**
         * @desc Delete the groups
         */
        foreach( $all as $key => $value )
        {
            if ( count ( $delete ) > 0 )
            {
                if ( in_array( $value['news_id'], $ids ) )
                {
                    $d = in_array( $value['news_id'], $delete  ) ? 1 : 0;
                    if ( !isset ( $_POST['confirm'] ) )
                    {
                        $functions->redirect( 'index.php?mod=news&sub=admin&action=delete&ids=' . urlencode(serialize($ids)) . '&delete=' . urlencode(serialize($delete)) . ( $front==1 ? '&front=1' : '' ), 'confirm', 3, $names, 'admin', $lang->t( 'You have selected the following news to delete:') );
                    }
                    else
                    {
                        if ( $d == 1 )
                        {
                            $stmt = $db->prepare( 'DELETE FROM ' . DB_PREFIX . 'news WHERE news_id = ?' );
                            $stmt->execute( array($value['news_id']) );
                        }
                    }
                }
            }
        }

        /**
        * @desc Redirect on finish
        */
        if( $front == 1 )
            $functions->redirect( 'index.php?mod=news&action=show', 'metatag|newsite', 3, $lang->t( 'The selected news has/have been deleted.' ), 'admin' );
        else
            $functions->redirect( 'index.php?mod=news&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The selected news has/have been deleted.' ), 'admin' );

    }

    /**
    * Create news
    *
    * @global $db
    * @global $lang
    * @global $functions
    * @global $input
    * @global $tpl
    * @global $cfg
    * @global $perms
    */
    function create()
    {
        global $db, $functions, $input, $lang, $tpl, $cfg, $perms;

        // Permission check
        $perms->check('cc_create_news');

        // Incoming Vars
        $submit = isset($_POST['submit']) ? $_POST['submit'] : '';
        $infos = $_POST['infos'];

        // check for fills
        if( ( empty($infos['title']) OR
            empty($infos['body']) )
            AND !empty($submit) )
        {
            $err['fill_form'] = 1;
        }

        // Create news in DB
        if( !empty($submit) && count($err) == 0)
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
            $stmt = $db->prepare( 'INSERT INTO ' . DB_PREFIX . 'news SET news_title = ?, news_body = ?, cat_id = ?, user_id = ?, news_added = ?, draft = ?' );
            $stmt->execute( array(  $infos['title'],
                                    $infos['body'],
                                    $infos['cat_id'],
                                    $_SESSION['user']['user_id'],
                                    time(),
                                    $infos['draft'],
                                    //$groups ) );
                                    ) );

            // Redirect on finish
            $functions->redirect( 'index.php?mod=news&sub=admin&action=show', 'metatag|newsite', 3, $lang->t( 'The news has been created.' ), 'admin' );

        }

        // Get all groups
        /*
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'groups' );

        $stmt->execute();
        $all_groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        */

        // $categories for module_news
        $stmt = $db->prepare( 'SELECT cat_id, name FROM ' . DB_PREFIX . 'categories WHERE module_id = ?' );
        $stmt->execute( array ( $cfg->modules['news']['module_id'] ) );
        $newscategories = $stmt->fetchAll(PDO::FETCH_NAMED);

        // give $newslist array to Smarty for template output
        $tpl->assign('newscategories', $newscategories);

        // Output Stuff
        $tpl->assign( 'err'         , $err);
        //$tpl->assign( 'all_groups'  , $all_groups);
        $this->output .= $tpl->fetch('news/admin_create.tpl');
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
                    $stmt = $db->prepare( 'UPDATE ' . DB_PREFIX . 'news SET news_title = ?, news_body = ?, cat_id = ?, user_id = ?, news_added = ?, draft = ? WHERE news_id = ?' );
                    $stmt->execute( array(  $infos['title'],
                                            $infos['news_body'],
                                            $infos['cat_id'],
                                            $_SESSION['user']['user_id'],
                                            time(),
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
    * @desc This content can be instantly displayed by adding {mod name="admin" func="instant_show" params="mytext"} into a template
    * @desc You have to add the lines as shown above into the case block: $this->output .= call_user_func_array( array( $this, 'instant_show' ), $params );
    */
    function instant_show($my_text)
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security, $input, $perms;

        /**
        * @desc Handle the output - $lang-t() translates the text.
        */
        $this->output .= $lang->t($my_text);
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

}
?>