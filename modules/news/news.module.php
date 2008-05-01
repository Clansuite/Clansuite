<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
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
 *
 */
class Module_News extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_News -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * module news action_show()
     *
     * 1. get news with nick of author and category
     * 2. add general data of comments for each news
     *
     * @output: $newslist ( array for smarty template output )
     */
    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=news&amp;action=show');

        // Defining initial variables
        // Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = $this->injector->instantiate('httprequest')->getParameter('page');
        $resultsPerPage = 3;

        // Load DBAL
        $this->injector->instantiate('clansuite_doctrine')->doctrine_initialize();

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
                             '?mod=news&action=show&page={%page}'
                             );

        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');
        #var_dump($pager_layout);

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
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

        # Get Render Engine
        $smarty = $this->getView();
        #$smarty->assign('news', $news->toArray());
        
        // Assign $news array to Smarty for template output
        $smarty->assign('news', $news);

        // Displaying page links: [1][2][3][4][5]
        // With links in all pages, except the $currentPage (our example, page 1)
        // display 2 parameter = true = only return, not echo the pager template.
        $smarty->assign('pagination_links',$pager_layout->display('',true));       
        $smarty->assign('pagination_needed',$pager->haveToPaginate());          #   Return true if it's necessary to paginate or false if not
        $smarty->assign('paginate_totalitems',$pager->getNumResults());         #   total number of items found on query search
        $smarty->assign('paginate_resultsinpage',$pager->getResultsInPage());   #   current Page
        $smarty->assign('paginate_lastpage',$pager->getLastPage());             #   Return the total number of pages
        $smarty->assign('paginate_currentpage',$pager->getPage());              #   Return the current page

        # specifiy the template manually
        #$this->setTemplate('news/show.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * widget_news
     *
     * Displayes specified number of News-Headlines.
     * Call this widget from Template-Side - by adding this to a template: 
     * {widget mod="news" params="5"}
     *
     *  // register with smarty
     *  $smarty->register_block('translate', 'do_translation');
     *
     * http://www.smarty.net/manual/en/tips.componentized.templates.php
     */
    public function widget_news($numberNews)
    {
        // Load DBAL
        $this->getInjector()->instantiate('clansuite_doctrine')->doctrine_initialize();
        
        $news_headlines = Doctrine_Query::create()
                          ->select('n.*, u.nick, u.user_id, c.name, c.image')
                          ->from('CsNews n')
                          ->leftJoin('n.CsUsers u')
                          ->leftJoin('n.CsCategories c')
                         #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                          ->orderby('n.news_id DESC')
                          ->execute();
        
        $smarty = $this->getView();
        
       
        // Assign $news array to Smarty for template output
        #$smarty->assign('news', $news_headlines);
        
        return $this->view()->fetch('news/news_widget.tpl');
        
    }

    function archiv()
    {
        global $cfg, $db, $tpl, $error, $lang, $functions, $security;

        // Incoming Vars
        $cat = isset($_POST['cat_id']) ? (int) $_POST['cat_id'] : 0;

        // Smarty Pagination load and init
        require(ROOT . 'core/smarty/SmartyPaginate.class.php');

        // set URL
        $SmartyPaginate->setUrl('index.php?mod=news&action=archiv');
        $SmartyPaginate->setUrlVar('page');
        // set items per page
        $SmartyPaginate->setLimit(10);

        // add cat_id to select statement if set, else empty
        $sql_cat = $cat == 0 ? '' : 'AND n.cat_id = ' . $cat;

        // $newsarchiv = newsentries mit nick und category
        $stmt = $db->prepare('SELECT n.news_id,  n.news_title, n.news_added,
                                     n.user_id, u.nick,
                                     n.cat_id, c.name as cat_name, c.image as cat_image
                                FROM ' . DB_PREFIX .'news n
                                LEFT JOIN '. DB_PREFIX .'users u USING(user_id)
                                LEFT JOIN '. DB_PREFIX .'categories c
                                ON ( n.cat_id = c.cat_id AND
                                     c.module_id = ? )
                                WHERE n.draft = ?
                                 ' . $sql_cat . '
                                ORDER BY news_id LIMIT '. $SmartyPaginate->getCurrentIndex() .' ,
                                                       '. $SmartyPaginate->getLimit() .' ');

        // @TODO: news with status: draft, published, private, private+protected
        $draft = '0';
        $stmt->execute( array ( $cfg->modules['news']['module_id'] , $draft) );
        $newsarchiv = $stmt->fetchAll(PDO::FETCH_NAMED);

        // Get Number of Rows
        $rows = $db->prepare('SELECT COUNT(*) FROM '. DB_PREFIX .'news n WHERE n.draft = ? ' . $sql_cat);
        $rows->execute( array ( $draft ) );
        $count = $rows->fetch(PDO::FETCH_NUM);
        // DEBUG - show total numbers of last Select
        // var_dump($count);

        // Finally: assign total number of rows to SmartyPaginate
        $SmartyPaginate->setTotal($count[0]);
        // assign the {$paginate} to $tpl (smarty var)
        $SmartyPaginate->assign($tpl);

        // $categories for module_news
        $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'categories WHERE module_id = ?' );
        $stmt->execute( array ( $cfg->modules['news']['module_id'] ) );
        $newscategories = $stmt->fetchAll(PDO::FETCH_NAMED);

        // give $newslist array to Smarty for template output
        $tpl->assign('newsarchiv', $newsarchiv);
        $tpl->assign('newscategories', $newscategories);

        $this->output = $tpl->fetch('news/news_archiv.tpl');

    }
}
?>