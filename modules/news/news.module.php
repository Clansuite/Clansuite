<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
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
        # read module config
        $this->getModuleConfig();

        # initialize related active-records
        parent::initRecords('news');
        parent::initRecords('users');
        parent::initRecords('categories');
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
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=news&amp;action=show');

        # get resultsPerPage from ModuleConfig
        $resultsPerPage = $this->getConfigValue('resultsPerPage_show', '3');

        # Defining initial variables
        $currentPage = (int) $this->getHttpRequest()->getParameter('page');
        $category    = (int) $this->getHttpRequest()->getParameter('cat');

        # if cat is no set, we need a query to show all news regardless which category,
        if(empty($category))
        {
            $newsQuery = Doctrine::getTable('CsNews')->fetchAllNews($currentPage, $resultsPerPage);
        }
        else # else we need a qry with the where(cat) statement
        {
            $newsQuery = Doctrine::getTable('CsNews')->fetchNewsByCategory($category, $currentPage, $resultsPerPage);
        }

        # get news, pager, pager_layout
        #clansuite_xdebug::printR($newsQuery['pager_layout']);
        #extract($newsQuery);
        #unset($newsQuery);

        $news           = $newsQuery['news'];
        $pager          = $newsQuery['pager'];
        $pager_layout   = $newsQuery['pager_layout'];

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
        $feed_items = (int) $this->getHttpRequest()->getParameter('items');

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

        $newsQuery = Doctrine::getTable('CsNews')->fetchNewsForFeed();

        $news           = $newsQuery['news'];
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

        $news_id = (int) $this->getHttpRequest()->getParameter('id');
        if($news_id == null) { $news_id = 1;  }

        $newsQuery = Doctrine::getTable('CsNews')->fetchSingleNews($news_id);

        $single_news = $newsQuery['single_news'];

        #clansuite_xdebug::printR($single_news);

        # if a news was found
        if(!empty($single_news) && is_array($single_news))
        {
            // Set Pagetitle and Breadcrumbs
            Clansuite_Trail::addStep( _('Viewing Single News: ') . $single_news['0']['news_title'] , '/index.php?mod=news&amp;action=show');

            # Assign News
            $smarty->assign('news', $single_news);

            /**
             * Check if this news_id has comments and assign them to an extra smarty variable
             * {$news_comments.} for easier access on template side.
             * Notice: if unset is not commented, the comments array is doubled:
             * you could also access the values via {$news.} in the tpl.
             */
            if ( !empty($single_news['0']['CsComments']) )
            {
                # Assign News
                $smarty->assign('news_comments', $single_news['0']['CsComments']);

                # unsetting the $single_news['0']['CsComments'] to save memory
                unset($single_news['0']['CsComments']);
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
     *      ??? 4: archive
     *
     */
    public function action_archive()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Archive'), '/index.php?mod=news&amp;action=archive');

        // Defining initial variables
        $currentPage = (int) $this->getHttpRequest()->getParameter('page');
        $date        = $this->getHttpRequest()->getParameter('date');

        # if date is an string
        if($date != null)
        {
            # check if only year is given
            if(strpos($date, '-') === false)
            {
                # convert date string like "2008"
                $startdate  = date('Y-m-d', strtotime($date . '-01-01'));
                $enddate    = date('Y-m-d', strtotime($date . '-01-01 + 1 year'));
            }
            else # the string is a year-month combination
            {
                # convert date string like "2008-Jul" to "2008-07-01"
                $startdate  = date('Y-m-d', strtotime($date));
                $enddate    = date('Y-m-d', strtotime($date . '+ 1 month'));
            }
        }
        else # set custom starting and ending date
        {
            $startdate = '1980-04-19';
            $enddate = date('Y-m-d');
        }

        # get resultsPerPage from ModuleConfig
        $resultsPerPage = $this->getConfigValue('resultsPerPage_archive', '3');

        #Fetch News for Archiv with Doctrine
        $newsQuery = Doctrine::getTable('CsNews')->fetchNewsForArchiv($startdate, $enddate, $currentPage, $resultsPerPage);

        # get news, pager, pager_layout
        #clansuite_xdebug::printR($newsQuery['pager_layout']);
        #extract($newsQuery);
        #unset($newsQuery);

        $news           = $newsQuery['news'];
        $pager          = $newsQuery['pager'];
        $pager_layout   = $newsQuery['pager_layout'];

        #clansuite_xdebug::printR($news);

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

        #clansuite_xdebug::printR($news);

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * module news action_fullarchive()
     *
     * 1. Get news with nick of author and category
     * 2. Add general data of comments for each news
     * 3. Paginate
     * 4. news_status:
     *      1: draft
     *      2: published
     *      3: unpublished
     *      ??? 4: archive
     *
     */
    public function action_fullarchive()
    {
        // Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Archiv'), '/index.php?mod=news&amp;action=fullarchive');

        // Defining initial variables
        $currentPage = (int) $this->getHttpRequest()->getParameter('page');

        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'n.created_at', 'n.news_title', 'c.cat_id', 'u.user_id', 'nr_news_comments');
        // Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('n.created_at', 'desc');
        // Get sort order from columnsort
        $sortorder = $columnsort->sortOrder(); // Returns 'name ASC' as default

        # set custom starting and ending date
        $startdate = '1980-04-19';
        $enddate   = date('Y-m-d');

        # get resultsPerPage from ModuleConfig
        $resultsPerPage = $this->getConfigValue('resultsPerPage_fullarchive', '25');

        #Fetch News for Archiv with Doctrine
        $newsQuery = Doctrine::getTable('CsNews')->fetchNewsForFullArchiv($sortorder, $startdate, $enddate, $currentPage, $resultsPerPage);

        # get news, pager, pager_layout
        #clansuite_xdebug::printR($newsQuery['pager_layout']);
        #extract($newsQuery);
        #unset($newsQuery);

        $news           = $newsQuery['news'];
        $pager          = $newsQuery['pager'];
        $pager_layout   = $newsQuery['pager_layout'];


        #clansuite_xdebug::printR($news);

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

        #clansuite_xdebug::printR($news);

        # Prepare the Output
        $this->prepareOutput();
    }

    /**
     * widget_latestnews
     *
     * Displayes the specified number of news in the latestnews_widget.tpl.
     * This is called from template-side by adding:
     * {load_module name="news" action="widget_news" items="2"}
     *
     * @param $numberNews Number of Newsitems to fetch
     * @param $smarty Smarty Render Engine Object
     * @returns content of news_widget.tpl
     */
    public function widget_latestnews($numberNews)
    {
        /**
         * get the incomming value for the number of items to display
         * we have the following order:
         * 1) specified by {load_module name="" action="" items="2"}
         * 2) modulecfg
         * 3) hardcoded defaultvalue
         */
        $numberNews = $this->getConfigValue('items_newswidget', $numberNews, '8');

        # get smarty as the view
        $smarty = $this->getView();

        parent::initRecords('users');
        parent::initRecords('categories');

        # fetch news via doctrine query
        $latestnews = Doctrine::getTable('CsNews')->fetchLatestNews($numberNews);

        # assign the fetched news to the view
        $smarty->assign('widget_latestnews', $latestnews);
    }
    
    /**
     * Widget for displaying NewsCategories in List-Style
     */
    public function widget_newscategories_list()
    {
        # get smarty as the view
        $smarty = $this->getView();

        parent::initRecords('categories');

        $newscategories_list = Doctrine::getTable('CsNews')->fetchNewsCategoriesList();

        # assign the fetched news to the view
        $smarty->assign('widget_newscategories_list', $newscategories_list);
    }
    
    /**
     * Widget for displaying NewsCategories in Dropdown-Style
     */
    public function widget_newscategories_dropdown()
    {
        # get smarty as the view
        $smarty = $this->getView();

        # initialize the records of other modules
        parent::initRecords('categories');

        # get catdropdown options from database
        $newscategories_dropdown = Doctrine::getTable('CsNews')->fetchNewsCategoriesDropdown();

        # assign the fetched news to the view
        $smarty->assign('widget_newscategories_dropdown', $newscategories_dropdown);
    }

    public function widget_archive()
    {
        #get smarty as view
        $smarty = $this->getView();

        # fetch all newsentries, ordered by creation date ASCENDING
        # get catdropdown options from database
        $widget_archive = Doctrine::getTable('CsNews')->fetchNewsArchiveWidget();

        # init a new array, to assign the year-month structured entries to
        $archive = array();

        # loop over all entries
        foreach($widget_archive as $entry)
        {
            # extract year and month from created_at
            $year  = date('Y',strtotime($entry['created_at']));
            $month = date('M',strtotime($entry['created_at']));

            # use extracted year and month to build up the new array
            # and reassign the entry itself
            $archive[$year][$month][] = $entry;

            #$archive['years'][$year]['months'][$month]['entries'][] = $entry;
        }

        #assign the fetched news to the view
        $smarty->assign('widget_archive', $archive);
    }
}
?>