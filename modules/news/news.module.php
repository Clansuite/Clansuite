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
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Module - News
 *
 * @since      File available since Release 0.2
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  News
 */
class Module_News extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_News -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {

    }

    /**
     * module news action_show()
     *
     * 1. Get news with nick of author and category
     * 2. Add general data of comments for each news
     * 3. Paginate
     *
     * @output: $news ( array for smarty template output )
     */
    public function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=news&amp;action=show');

        // Defining initial variables
        // Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('page');
        $resultsPerPage = 3;

        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('n.*,
                                              u.nick, u.user_id, u.email, u.country,
                                              c.name, c.image, c.icon, c.color,
                                              nc.*,
                                              ncu.nick, ncu.email, ncu.country')
                                    ->from('CsNews n')
                                    ->leftJoin('n.CsUser u')
                                    ->leftJoin('n.CsCategory c')
                                    ->leftJoin('n.CsComment nc')
                                    ->leftJoin('nc.CsUser ncu')
                                    #->where('c.module_id = 7')
                                    ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->orderby('n.news_id DESC, n.news_added DESC'),
                                 # The following is Limit  ?,? =
                                 $currentPage, // Current page of request
                                 $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5
                             )),
                             '?mod=news&amp;action=show&amp;page={%page}'
                             );

        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        // Calculate Number of Comments
        foreach ($news as $k => $v)
        {
            # check if something was returned
            if( isset($v['CsComment']) && !empty($v['CsComment']) )
            {
                # add to $newslist array, the numbers of news_comments for each news_id
                $news[$k]['nr_news_comments'] = count($v['CsComment']);
            }
            else
            {
                # nothing was returned, so we set 0
                $news[$k]['nr_news_comments'] = 0;
            }
        }

        # Get Render Engine
        $smarty = $this->getView();

        // Assign $news array to Smarty for template output
        // Also pass the complete pager object to smarty (referenced to save memory - no extra vars needed) => assign_by_ref()
        // Another way (and much more flexible one) is via register_object()
        // SEE: http://www.smarty.net/manual/en/advanced.features.php
        // TODO: Can we get the news object by reference into smarty too ? register_object() should be essential
        $smarty->assign('news', $news);
        $smarty->assign_by_ref('pager', $pager);
        $smarty->assign_by_ref('pager_layout', $pager_layout);

        /*
        // Displaying page links: [1][2][3][4][5]
        // With links in all pages, except the $currentPage (our example, page 1)
        // display 2 parameter = true = only return, not echo the pager template.
        $smarty->assign('pagination_links',$pager_layout->display('',true));
        $smarty->assign('pagination_needed',$pager->haveToPaginate());          #   Return true if it's necessary to paginate or false if not
        $smarty->assign('paginate_totalitems',$pager->getNumResults());         #   total number of items found on query search
        $smarty->assign('paginate_resultsinpage',$pager->getResultsInPage());   #   current Page
        $smarty->assign('paginate_lastpage',$pager->getLastPage());             #   Return the total number of pages
        $smarty->assign('paginate_currentpage',$pager->getPage());              #   Return the current page
        */

        // Specifiy the template manually
        // !! Template is set by parameter 'action' coming from the URI, so no need for manually set of tpl !!
        //$this->setTemplate('news/show.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * createFeed
     *
     * URL-Parameters: ?items=15 or 30
     */
    public function createFeed()
    {
        # Require Feedcreator Class
        if(!class_exists('UniversalFeedCreator'))
        {
            require ROOT_LIBRARIES . 'feedcreator/feedcreator.class.php';
        }

        /**
         * Get Number of Feed Items to create
         */
        $feed_items = (int) $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('items');

        # Set Number of Items Range 0<15 || MAX 30
        if($feed_items == null or $feed_items < 15)   { $feed_items = 15;  }
        elseif($feed_items > 15 )                     { $feed_items = 30;  }

        /**
         * Create Main Feed Object
         */
        $rss = new UniversalFeedCreator();
        $rss->useCached(); // use cached version if age<1 hour
        $rss->title = "PHP news";
        $rss->description = "daily news from the clanwebsites world";

        # optional
        $rss->descriptionTruncSize = 500;
        $rss->descriptionHtmlSyndicated = true;

        $rss->link = "http://www.clanswebsite.net/news";
        $rss->syndicationURL = "http://www.clanwebsite.net/".$_SERVER["PHP_SELF"];

        /**
         * Create Feed Image Object
         */
        $image = new FeedImage();
        $image->title = "clanwebsite.net logo";
        $image->url = "http://www.clanwebsite.net/images/logo.gif";
        $image->link = "http://www.clanwebsite.net";
        $image->description = "Feed provided by clanwebsite.net. Click to visit.";

        # optional
        $image->descriptionTruncSize = 500;
        $image->descriptionHtmlSyndicated = true;

        # Set Feed Image Object to Main Feed Object
        $rss->image = $image;

        # Fetch News via Doctrine
        $news = Doctrine_Query::create()
                        ->select('n.*,
                                  u.nick, u.user_id, u.email, u.country,
                                  c.name, c.image, c.icon, c.color,
                                  nc.*,
                                  ncu.nick, ncu.email, ncu.country')
                        ->from('CsNews n')
                        ->leftJoin('n.CsUser u')
                        ->leftJoin('n.CsCategory c')
                        ->leftJoin('n.CsComment nc')
                        ->leftJoin('nc.CsUser ncu')
                        #->where('c.module_id = 7')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->fetchArray();

        /**
         * Loop over Dataset
         */
        foreach ($news as $k => $v)
        {
            /**
             * Create Feed Item Object
             */
            $item = new FeedItem();
            $item->title = $data->title;
            $item->link = $data->url;
            $item->description = $data->short;

            # optional
            $item->descriptionTruncSize = 500;
            $item->descriptionHtmlSyndicated = true;

            $item->date = $data->newsdate;
            $item->source = "http://www.clanwebsite.net";
            $item->author = "John 'wanker' Vain";

            # Set Feed Item Object to Main Feed Object
            $rss->addItem($item);
        }

        /**
         * Set Feed Format and save to file
         *
         * Valid format strings are:
         * RSS0.91, RSS1.0, RSS2.0, PIE0.1 (deprecated),
         * MBOX, OPML, ATOM, ATOM0.3, HTML, JS
         */
        $rss->saveFeed('RSS2.0', ROOT_MOD . 'news/feed-'.$feed_items.'.xml');
    }

     /**
      * module news action_showone()
      *
      * Show one single news with comments
      *
      */
     public function action_showone()
     {
        # Get Render Engine
        $smarty = $this->getView();

        $news_id = (int) $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('id');
        if($news_id == null) { $news_id = 1;  }

        $single_news = Doctrine_Query::create()
                        ->select('n.*,
                                  u.nick, u.user_id, u.email, u.country,
                                  c.name, c.image, c.icon, c.color,
                                  nc.*,
                                  ncu.nick, ncu.email, ncu.country')
                        ->from('CsNews n')
                        ->leftJoin('n.CsUser u')
                        ->leftJoin('n.CsCategory c')
                        ->leftJoin('n.CsComment nc')
                        ->leftJoin('nc.CsUser ncu')
                        #->where('c.module_id = 7')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->where('news_id = ' . $news_id)
                        ->fetchArray();

        #var_dump($single_news);

        # if a news was found
        if(!empty($single_news) && is_array($single_news))
        {
            // Set Pagetitle and Breadcrumbs
            Clansuite_Trail::addStep( _('Viewing Single News: ') . $single_news['0']['news_title'] , '/index.php?mod=news&amp;action=show');

            # Assign News
            $smarty->assign('news', $single_news);

            /**
             * Check if this news_id has comments and assign them to an extra smarty var
             * {$news_comments.} for easier access on template side
             * (yes, it's doubled: you could also access the values via {$news.} )
             */
            if ( !empty($single_news['0']['CsComment']) )
            {
                # Assign News
                $smarty->assign('news_comments', $single_news['0']['CsComment']);
            }
            else
            {
                $smarty->assign('news_comments', array());
            }
        }
        else # no news found for this id
        {
            $this->setTemplate('newsnotfound.tpl');
        }

        # Prepare Output
        $this->prepareOutput();
     }


    /**
     * module news action_archive()
     *
     * 1. Get news with nick of author and category
     * 2. Add general data of comments for each news
     * 3. Paginate
     * 4. news_status:
     *      1: draft
     *      2: published
     *      3: unpublished
     *      4: archive
     *
     * @output: $news ( array for smarty template output )
     */
    public function action_archive()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Archive'), '/index.php?mod=news&amp;action=archive');

        // Defining initial variables
        $currentPage = $this->injector->instantiate('Clansuite_HttpRequest')->getParameter('page');
        $resultsPerPage = 3;

        // Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('n.*, u.nick, u.user_id, c.name, c.image')
                                    ->from('CsNews n')
                                    ->leftJoin('n.CsUser u')
                                    ->leftJoin('n.CsCategory c')
                                    ->where('n.news_status = 4')
                                    #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->orderby('n.news_id DESC'),
                                 # The following is Limit  ?,? =
                                 $currentPage, // Current page of request
                                 $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5
                             )),
                             '?mod=news&amp;action=archive&amp;page={%page}'
                             );

        // Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        // Fetch the related COUNT on news_comments and the author of the latest!
        // a) Count all news
        // b) Get the nickname of the last comment for certain news_id
        // TODO: One query possible with some joins ?
        $stmt1 = Doctrine_Query::create()
                         ->select('nc.*, u.nick as lastcomment_by, COUNT(nc.comment_id) as nr_news_comments')
                         ->from('CsNewsComments nc')
                         ->leftJoin('nc.CsUser u')
                         ->groupby('nc.news_id')
                         #->setHydrationMode(HYDRATE_NONE)
                         ->where('nc.news_id = ?');

        foreach ($news as $k => $v)
        {
            // add to $newslist array, the numbers of news_comments for each news_id
            $cs_news_comments_array = $stmt1->execute(array( $v['news_id'] ), Doctrine::HYDRATE_ARRAY);
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

        # Get Render Engine
        $smarty = $this->getView();

        // Assign $news array to Smarty for template output
        // Also pass the complete pager object to smarty (referenced to save memory - no extra vars needed) => assign_by_ref()
        // Another way (and much more flexible one) is via register_object()
        // SEE: http://www.smarty.net/manual/en/advanced.features.php
        // TODO: Can we get the news object by reference into smarty too ? register_object() should be essential
        $smarty->assign('news', $news);
        $smarty->assign_by_ref('pager', $pager);
        $smarty->assign_by_ref('pager_layout', $pager_layout);

        /*
        // Displaying page links: [1][2][3][4][5]
        // With links in all pages, except the $currentPage (our example, page 1)
        // display 2 parameter = true = only return, not echo the pager template.
        $smarty->assign('pagination_links',$pager_layout->display('',true));
        $smarty->assign('pagination_needed',$pager->haveToPaginate());          #   Return true if it's necessary to paginate or false if not
        $smarty->assign('paginate_totalitems',$pager->getNumResults());         #   total number of items found on query search
        $smarty->assign('paginate_resultsinpage',$pager->getResultsInPage());   #   current Page
        $smarty->assign('paginate_lastpage',$pager->getLastPage());             #   Return the total number of pages
        $smarty->assign('paginate_currentpage',$pager->getPage());              #   Return the current page
        */

        // Specifiy the template manually
        // !! Template is set by parameter 'action' coming from the URI, so no need for manually set of tpl !!
        //$this->setTemplate('news/show.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * widget_news
     *
     * Displayes the specified number of news in the news_widget.tpl.
     * This is called from template-side by adding:
     * {load_module name="news" action="widget_news" items="2"}
     *
     * @param $numberNews Number of Newsitems to fetch
     * @param $smarty Smarty Render Engine Object
     * @returns content of news_widget.tpl
     */
    public function widget_news($numberNews)
    {
        # get smarty as the view
        $smarty = $this->getView();

        parent::initRecords('users');

        # fetch news via doctrine query
        $news = Doctrine_Query::create()
                              ->select('n.*, u.nick, u.user_id, c.name, c.image')
                              ->from('CsNews n')
                              ->leftJoin('n.CsUser u')
                              ->leftJoin('n.CsCategory c')
                              ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                              ->orderby('n.news_id DESC')
                              ->limit($numberNews)
                              ->execute( array() );

        #var_dump($news);

        # assign the fetched news to the view
        $smarty->assign('news_widget', $news);
    }
}
?>