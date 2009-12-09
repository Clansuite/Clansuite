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
    * @version    SVN: $Id: Quotes.module.php 2390 2008-08-04 19:38:54Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Administration Module - Quotes
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 * @version    0.1
 */
class Module_Quotes_Admin extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_Quotes -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
		parent::initRecords('quotes');
    }

    public function action_admin_show()
    {
        # Set Pagetitle and Breadcrumbs
        # Clansuite_Trail::addStep( _('Show'), '/index.php?mod=quotes&amp;sub=admin&amp;action=show');

        # Incoming Variables
        $request = $this->injector->instantiate('Clansuite_HttpRequest');
        $quote_id      = (int) $request->getParameter('id');
        // add cat_id to select statement if set, else empty
        #$sql_cat = $cat_id == 0 ? 0 : $cat_id;
        $currentPage = (int) $request->getParameter('page');
        $resultsPerPage = (int) 10;


        // SmartyColumnSort -- Easy sorting of html table columns.
        require( ROOT_LIBRARIES . '/smarty/libs/SmartyColumnSort.class.php');
        // A list of database columns to use in the table.
        $columns = array( 'q.quote_body', 'q.quote_author', 'q.qoute_source');
        // Create the columnsort object
        $columnsort = new SmartyColumnSort($columns);
        // And set the the default sort column and order.
        $columnsort->setDefault('q.quote_author', 'desc');
        // Get sort order from columnsort; Returns 'name ASC' as default
        $sortorder = $columnsort->sortOrder();

        # Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                        new Doctrine_Pager(
                            Doctrine_Query::create()
                                    ->select('q.*')
                                    ->from('CsQuotes q')
                                    ->orderby($sortorder),
                                     # The following is Limit  ?,? =
                                     $currentPage, // Current page of request
                                     $resultsPerPage // (Optional) Number of results per page Default is 25
                             ),
                             new Doctrine_Pager_Range_Sliding(array(
                                 'chunk' => 5
                             )),
                             '?mod=quotes&sub=admin&action=show&page={%page}'
                             );

        # Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');
        # Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        // Fetching news
        $quotes = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        /**
         * View Assigns
         */
        # Get Render Engine
        $view = $this->getView();

        #$view->assign('news', $news->toArray());
        $view->assign('quotes', $quotes);

        # Return true if it's necessary to paginate or false if not
        $view->assign('pagination_needed', $pager->haveToPaginate());

        # Assign Pagination
        $view->assign_by_ref('pager', $pager);
        $view->assign_by_ref('pager_layout', $pager_layout);

        /**
         * Layout & Output
         */

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_admin_delete()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Delete'), '/index.php?mod=quotes&amp;sub=admin&amp;action=delete');

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_admin_create()
    {
        $quote = new CsQuotes;
        $quote[''] =
        $quote[''] =
        $quote[''] =
        $quote[''] =
        $quote->save();

        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Create'), '/index.php?mod=quotes&amp;sub=admin&amp;action=create');

        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }

    public function action_admin_update()
    {
        # Set Pagetitle and Breadcrumbs
        Clansuite_Trail::addStep( _('Update'), '/index.php?mod=quotes&amp;sub=admin&amp;action=update');




        # Set Layout Template
        $this->getView()->setLayoutTemplate('index.tpl');

        # Prepare the Output
        $this->prepareOutput();
    }
}
?>