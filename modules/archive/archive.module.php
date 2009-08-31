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
    * @version    SVN: $Id: news.module.php 2753 2009-01-21 22:54:47Z vain $
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Module - Archive
 * 
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @version    0.1
 */
class Module_Archive extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Archive -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # nothing to do
		parent::initRecords('news');
        parent::initRecords('users');
        parent::initRecords('categories');
    }
    
    public function action_show()
    {
		
		// Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Show'), '/index.php?mod=archive&amp;action=show');

        # @todo get resultsPerPage from ModuleConfig
        $resultsPerPage = 3;

        // Defining initial variables
        // Pager Chapter in Doctrine Manual  -> http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
        $currentPage = (int) $this->getHttpRequest()->getParameter('page');
        $module         = (int) $this->getHttpRequest()->getParameter('module');
		$year         = (int) $this->getHttpRequest()->getParameter('year');
		$month         = (int) $this->getHttpRequest()->getParameter('month');

		# Module Archive for News
        # if module is set to 7 (news)
        if($module == '7')
        {	
			if(empty($year) OR empty($month))
			{
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
												->leftJoin('n.CsCategories c')
												->leftJoin('n.CsComment nc')
												->leftJoin('nc.CsUser ncu')
												->where('c.module_id = 7' AND 'n.news_status = 4')
												->setHydrationMode(Doctrine::HYDRATE_ARRAY)
												->orderby('n.created_at DESC'),
											# The following is Limit  ?,? =
											$currentPage, // Current page of request
											$resultsPerPage, // (Optional) Number of results per page Default is 25
											$year,
											$month
										),
									new Doctrine_Pager_Range_Sliding(array(
										'chunk' => 5
										)),
									'?mod=archive&amp;action=show&amp;page={%page}'
									);
	
				// Assigning templates for page links creation
				$pager_layout->setTemplate('[<a href="{%url}&amp;module='.$module.'&amp;year='.$year.'&amp;month='.$month.'">{%page}</a>]');
			}
			else
			{
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
												->leftJoin('n.CsCategories c')
												->leftJoin('n.CsComment nc')
												->leftJoin('nc.CsUser ncu')
												->where('c.module_id = 7')
												->andWhere('n.created_at = ?', array( $year ))
												->andWhere('n.created_at = ?', array( $month ))
												->andWhere('n.news_status = 4')
												->setHydrationMode(Doctrine::HYDRATE_ARRAY)
												->orderby('n.created_at DESC'),
											# The following is Limit  ?,? =
											$currentPage, // Current page of request
											$resultsPerPage, // (Optional) Number of results per page Default is 25
											$year,
											$month
										),
									new Doctrine_Pager_Range_Sliding(array(
										'chunk' => 5
										)),
									'?mod=archive&amp;action=show&amp;page={%page}'
									);
	
				// Assigning templates for page links creation
				$pager_layout->setTemplate('[<a href="{%url}&amp;module='.$module.'&amp;year='.$year.'&amp;month='.$month.'">{%page}</a>]');
				
			}
			
		$pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        if(!empty($module))
        {
            # Displaying pager links with module added
            $pager_layout->display( array(
                                              'module' => urlencode($module)),
                                              true
                                              );
        }

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
		
		elseif(empty($module))
		{
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
												->leftJoin('n.CsCategories c')
												->leftJoin('n.CsComment nc')
												->leftJoin('nc.CsUser ncu')
												->where('c.module_id = ?', array($module) AND 'n.news_status = 4')
												->setHydrationMode(Doctrine::HYDRATE_ARRAY)
												->orderby('n.created_at ASC'),
											# The following is Limit  ?,? =
											$currentPage, // Current page of request
											$resultsPerPage // (Optional) Number of results per page Default is 25
										),
									new Doctrine_Pager_Range_Sliding(array(
										'chunk' => 5
										)),
									'?mod=archive&amp;action=show&amp;page={%page}'
									);
	
				// Assigning templates for page links creation
				$pager_layout->setTemplate('[<a href="{%url}&amp;module='.$module.'">{%page}</a>]');
		
		$pager_layout->setSelectedTemplate('[{%page}]');

        // Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        if(!empty($module))
        {
            # Displaying pager links with cat added
            $pager_layout->display( array(
                                              'module' => urlencode($module)),
                                              true
                                              );
        }

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
		
       
		
    }

  
}
?>