<?php
/**
 * Index Module
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
 * @license    GNU/GPL, see COPYING.txt
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$Date$)
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
 * Clansuite Module: Index
 *
 * Purpose: This class is the PageController which has many pages to deal with.
 *
 * Class was rewritten for Version 0.2
 */
class module_index extends controller_base implements clansuite_module
{
    function __construct(Phemto $injector=null)
    {
        parent::__construct(); # run constructor on controller_base
    }

    /**
     * Main Method of Index Module
     * 
     * Sets up module specific stuff, needed by all actions of the module
     * - Calls the requested Action $_REQUEST['action'] Vars to the functions
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # - try proceed to the requested action or display action not found!
        $this->getActionController($request);
    }

    /**
     *  Test the MVC Framework
     *  by calling the URL "index.php?mod=index&action=mvc"
     */
    function action_mvc()
    {
        $index_view = new module_index_view;            # initialize the view
        $index_view->showUserData('1');                 # call view function for output
        # the requested user_id would be a get or post input variable
        echo 'Clansuite Framework - MVC is working!';   # give status and exit
        exit;
    }

    /**
     * index() redirects to show()
     */
    function index()
    {
        # Load DBAL
        $db = $this->injector->instantiate('clansuite_doctrine');
        $db->doctrine_bootstrap();

        # Load Models
        require ROOT . '/myrecords/generated/BaseCsNews.php';
        require ROOT . '/myrecords/CsNews.php';
        require ROOT . '/myrecords/generated/BaseCsNewsComments.php';
        require ROOT . '/myrecords/CsNewsComments.php';
        require ROOT . '/myrecords/generated/BaseCsCategories.php';
        require ROOT . '/myrecords/CsCategories.php';
        require ROOT . '/myrecords/generated/BaseCsUsers.php';
        require ROOT . '/myrecords/CsUsers.php';
        #require ROOT . '/myrecords/generated/BaseCsModules.php';
        #require ROOT . '/myrecords/CsModules.php';

        #$models = Doctrine::getLoadedModels();
        #print_r($models);

        // Defining initial variables
        // Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = $this->injector->instantiate('httprequest')->getParameter('page');
        $resultsPerPage = 3;

        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('n.*, u.nick, u.user_id, c.name, c.image')
                                    ->from('CsNews n')
                                    ->leftJoin('n.CsUsers u')
                                    ->leftJoin('n.CsCategories c')
                                    #->where('c.module_id = 7')
                                    #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->orderby('n.news_id DESC'),
                                 # The following is Limit  ?,? =
                                 $currentPage, // Current page of request
                                 $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5
                             )),
                             '?mod=index&action=index&page={%page}'
                             );

        # Get Render Engine
        $smarty = $this->getView();

        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');
        #var_dump($pager_layout);

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
        #var_dump($pager->getExecuted());
        $news = $pager->execute(array(), Doctrine::FETCH_ARRAY);

        // Fetch the related COUNT on news_comments and the author of the latest!
        // a) Count all newst
        // b) get the nickname of the last comment for certain news_id
        $stmt1 = Doctrine_Query::create()
                         ->select('nc.*, u.nick as lastcomment_by, count(nc.comment_id) as nr_news_comments')
                         ->from('CsNewsComments nc')
                         ->leftJoin('nc.CsUsers u')
                         ->groupby('nc.news_id')
                         ->setHydrationMode(Doctrine::HYDRATE_NONE)
                         ->where('nc.news_id = ?');

        foreach ($news as $k => $v)
        {
            // add to $newslist array, the numbers of news_comments for each news_id
            $cs_news_comments_array = $stmt1->execute(array( $v['news_id'] ), Doctrine::FETCH_ARRAY);
            # check if something was returned
            if(isset($cs_news_comments_array[0]))
            {
                # strip the [0] array off
                $news[$k]['CsNewsComments'] = $cs_news_comments_array[0];
            }
            else
            {
                # nothing was returned, so we set 0
                $news[$k]['CsNewsComments'] = array('nr_news_comments' => 0);
            }
        }
        #var_dump($news);

        #$smarty->assign('news', $news->toArray());
        $smarty->assign('news', $news);

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

        # specifiy the template manually
        $this->setTemplate('index/newsshow.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }

    function smarty_error_example()
    {
        $this->output .= 'test';
        $this->setTemplate('smarty_error_example.tpl');
        # Starting the View
        $this->setView($this->getRenderEngine());
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * Show the Index / Entrance -> welcome message etc.
     */
    function action_show()
    {
        /***
         * To set a Render Engine use the following method:
         * $this->setRenderEngine('smarty');
         *
         * You can define a specific view_type like (smarty, json, rss, php)
         *    -- or leave it away, then smarty is used as fallback!
         *
         */
        #$this->setRenderEngine('smarty');

        /**
         * Directly assign something to the output
         */
        #$this->output   .= 'This writes directly to the OUTPUT. Action show() was called.';

       /**
        * Usage of method: setTemplate($templatename)
        *
        * 1. you can specify a complete template-filename (including its path)
        * 2. if you NOT use this method,
        * we try to automatically detect the template-file by using module and action as templatename.
        *
        * the template lookup will take place in the following paths:
        *    a) in the activated "layout theme" folder (according to the user-session)
        *        example: usertheme = "standard" and $this->template = 'modulename/filename.tpl';
        *         then lookup of template in /standard/modulename/filename.tpl
        *    b) the modul-directory/templatefolder/rendererfolder/actionname.tpl
        *
        * As a result of this direct connection of URL to TPL, it's possible to
        * code in a very straightforward way:  index.php?mod=something&action=any
        * would result in a template-search in /modules/something/templates/any.tpl
        *
        * Even an empty module function would result in an rendering - a good starting point i guess!
        *
        */
        # Direct Path Assignments
        # a) call the template in root_tpl (themefolder) + path
        # This is also automagically called, when no template was set!
        #$this->setTemplate('index/show.tpl');
        # OR
        # b) directly call template in module path
        #$this->setTemplate( ROOT_MOD . '/index/templates/show.tpl' );

        # Starting the View
        $this->setView($this->getRenderEngine());

        # Prepare the Output
        $this->prepareOutput();
    }
}

/**
 * We don't need the following classes.
 * They are here for understanding the MVC Pattern.
 * Demonstration is done via function $module_index->mvc();
 */

/**
 * Module Index - View Class
 * V = View in MVC
 *
 * Purpose: View selects the Model for the choosen view(action)
 *          and assembles/prepares that view(action) with Model-Informations for Output
 *          When a Model-Object is fetched, the View calls a certain method on it to extract the data.
 *          Like $users = $userobject->findUserByID($id);
 *
 */
class module_index_view
{
     public function showUserData($user_id)
     {
        // instantiate the module_index_model
        $index_model = new module_index_model;
        // fetch data-object from the model
        $user_object = $index_model->findUserbyID($user_id);
        // perform actions on the fetched data
        #ucfirst($user_object);
        // assign data-object to view and output
        foreach ($user_object as $user_object_data)
        {
            echo '<br /><strong>'. $user_object_data .'</strong><br />';
        }
     }
}

/**
 * Module Index - Model Class
 * M = Model in MVC
 *
 * Purpose: Select Data from Database and return Model-Informations (complete objects) to the View-Layer
 *          Like return $user;
 */
class module_index_model
{
    /**
     * test function for demonstrating the mvc approach
     * this would normally execute a sql query to fetch something from database
     * here we just cheat a little and pass data along as a return value
     */
    public function findUserById($user_id)
    {
        // SQL logic to get User Infos for a certain $user_id
        $user_data = array('name' => 'John Wayne', 'town' => 'Berlin');
        //returns User-ROW!
        return $user_data;
    }
}
?>