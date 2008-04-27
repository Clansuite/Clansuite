<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         userslist.module.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Module Class - userslist
    *               userslist
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
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      Userslist
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-onwards)
 * @link
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    module
 * @subpackage  module_userslist
 */
class Module_Userslist extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Userslist -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }

    /**
     * Action -> Show
     * URL = index.php?mod=userslist&action=show
     *
     */
    function action_show()
    {
        // Set Pagetitle and Breadcrumbs
        trail::addStep( _('Show'), '/index.php?mod=userlist&amp;action=show');

        // Defining initial variables
        $currentPage = $this->injector->instantiate('httprequest')->getParameter('page');
        $resultsPerPage = 25;

        // Load DBAL
        $this->injector->instantiate('clansuite_doctrine')->doctrine_initialize();

        /* @todo: news with status: draft, published, private, private+protected*/
        // Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('u.user_id, u.nick, u.email, u.joined,, ug.*, g.name, g.color')
                                    ->from('CsUsers u')
                                    ->leftJoin('u.CsUserGroups ug')
                                   ->leftJoin('ug.CsGroups g')                                    
                                   #->setHydrationMode(Doctrine::HYDRATE_NONE)
                                   ->orderby('u.user_id ASC'),
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
        $userslist = $pager->execute(array(), Doctrine::FETCH_ARRAY);

        // Get Number of Rows
        $count = count($userslist);
        // DEBUG - show total numbers of last Select
        // echo 'Found Rows: ' . $count;

        # Get Render Engine
        $smarty = $this->getView();

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

         // give $newslist array to Smarty for template output
        $smarty->assign('userslist', $userslist);

        # specifiy the template manually
        #$this->setTemplate('userslist/show.tpl');
        # Prepare the Output
        $this->prepareOutput();
    }
}
?>