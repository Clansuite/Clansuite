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

namespace Koch\Datagrid;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Datagrid Renderer
 *
 * Generates html-code based upon the grid settings.
 *
 * @author Florian Wolf <xsign.dll@clansuite.com>
 */
class Renderer
{
    /**
     * The datagrid
     *
     * @var Clansuite_Datagrid $_Datagrid
     */
    private static $_datagrid;

    /**
     * The look of the links of the pager
     *
     * @var string
     */
    # static
    /**
     * The items for results per page
     *
     * @var array
     */
    private static $_resultsPerPageItems = array(5, 10, 20, 50, 100);

    /**
     * Instantiate renderer and attach Datagrid to it
     *
     * @param Clansuite_Datagrid $_Datagrid
     */
    public function __construct($_Datagrid)
    {
        $this->setDatagrid($_Datagrid);

        return $this;
    }

    /**
     * Set the datagrid object
     *
     * @param Clansuite_Datagrid $_Datagrid
     */
    public static function setDatagrid($_Datagrid)
    {
        self::$_datagrid = $_Datagrid;
    }

    /**
     * Get the Datagrid object
     *
     * @return Clansuite_Datagrid $_Datagrid
     */
    public static function getDatagrid()
    {
        return self::$_datagrid;
    }

    /**
     * Set the items for the dropdownbox of results per page
     *
     * @param array $_Items
     */
    public static function setResultsPerPageItems(array $_Items)
    {
        self::$_resultsPerPageItems = $_Items;
    }

    public static function setPagerCssClass($pagerCssClass)
    {
        self::$pagerCssClass = $pagerCssClass;
    }

    public static function getPagerCssClass()
    {
        return self::$pagerCssClass;
    }

    /**
     * getFirstPage
     *
     * Returns the first page
     *
     * @return int first page
     */
    public static function getFirstPage()
    {
        return 1;
    }

    public static function getPreviousPage()
    {
        return max(self::getDatagrid()->getPage() - 1, self::getFirstPage());
    }

    /**
     * getNextPage
     *
     * Returns the next page
     *
     * @return int next page
     */
    public static function getNextPage()
    {
        return min(self::getDatagrid()->getPage() + 1, self::getLastPage());
    }

    /**
     * getLastPage
     *
     * Returns the last page
     *
     * @return int last page
     */
    public static function getLastPage()
    {
        return max(1, ceil(self::getDatagrid()->getTotalResultsCount() / self::getDatagrid()->getResultsPerPage()));
    }

    public static function getOffset()
    {
        return (self::getDatagrid()->getPage() - 1) * self::getDatagrid()->getResultsPerPage();
    }

    /**
     * getFirstIndice
     *
     * Return the first indice number for the current page
     *
     * @return int First indice number
     */
    public static function getFirstIndice()
    {
        return (self::getDatagrid()->getPage() - 1) * self::getDatagrid()->getResultsPerPage() + 1;
    }

    /**
     * getLastIndice
     *
     * Return the last indice number for the current page
     *
     * @return int Last indice number
     */
    public static function getLastIndice()
    {
        return min(self::getDatagrid()->getTotalResultsCount(), (self::getDatagrid()->getPage() * self::getDatagrid()->getResultsPerPage()));
    }

    /**
     * haveToPaginate
     *
     * Return true if it's necessary to paginate or false if not
     *
     * @return bool true if it is necessary to paginate, false otherwise
     */
    public function haveToPaginate()
    {
        return self::getDatagrid()->getTotalResultsCount() > self::getDatagrid()->getResultsPerPage();
    }

    /**
     * getNumberOfPages
     *
     * Return the number of pages ( total results / results per page )
     * e.g.: 40 pages = 1000 results / 25 results per page.
     *
     * @return int Return the number of pages
     */
    public static function getNumberOfPages()
    {
        $number_of_pages = (self::getDatagrid()->getTotalResultsCount() / self::getDatagrid()->getResultsPerPage());

        if($number_of_pages > (int) $number_of_pages)
        {
            $number_of_pages = (int) ($number_of_pages) + 1;
        }

        return $number_of_pages;
    }

    public static function renderPager()
    {
        $selected_page = self::getDatagrid()->getPage();
        $results_count = self::getDatagrid()->getTotalResultsCount();
        $results_per_page = self::getDatagrid()->getResultsPerPage();
        $number_of_pages = self::getNumberOfPages();

        $min_page_number = (int) ($selected_page - 5);
        $max_page_number = (int) ($selected_page + 5);

        if($min_page_number < 1)
        {
            $min_page_number = 1;
            $max_page_number = 11;
            $max_page_number = ($max_page_number > $number_of_pages) ? $number_of_pages : 11;
        }

        if($max_page_number > $number_of_pages)
        {
            $max_page_number = $number_of_pages;
            $min_page_number = $max_page_number - 10;
            $min_page_number = ($min_page_number < 1) ? 1 : $max_page_number - 10;
        }

        if($results_count <= $results_per_page)
        {
            $min_page_number = 1;
            $max_page_number = $number_of_pages;
        }

        # init
        $pages = $first = $prev = $next = $last = '';

        # pager render modes:
        # a) [1][2][3][4][5]
        # b) first [1][2][3][4][5] last
        # c) first prev [1][2][3][4][5] next last

        if($selected_page > 1 and $number_of_pages > 1)
        {
            $url = self::getURLForPageInRange(self::getFirstPage());
            $first = '<li class="previous"><a href="' . $url . '" title="First Page">&laquo First Page</a></li>';

            $url = self::getURLForPageInRange(self::getPreviousPage());
            $prev = '<li class="previous"><a href="' . $url . '" title="Previous Page">&laquo Previous</a></li>';
        }

        $pages = self::renderPageRangeAroundPage($selected_page, $min_page_number, $max_page_number);

        if($selected_page < $number_of_pages and $number_of_pages > 1)
        {

            $url = self::getURLForPageInRange(self::getNextPage());
            $next = '<li class="next"><a href="' . $url . '" title="Next Page">Next &raquo;</a></li>';

            $url = self::getURLForPageInRange(self::getLastPage());
            $last = '<li class="next"><a href="' . $url . '" title="Last Page">Last Page &raquo;</a></li>';
        }

        /**
         * Internal Debug Display for the pager.
         */
        $info = '';
        $info .= 'Total Records' . $results_count;
        $info .= 'Number of pages' . $number_of_pages;
        $info .= 'Current Page' . $selected_page;
        $info .= 'Min Page Index' . $min_page_number;
        $info .= 'Max Page Index' . $max_page_number;
        $info .= 'Results per page' . $results_per_page;
        #Clansuite_Debug::firebug($info);

        $html = '';
        $html .= '<ul class="pagination3">';
        # $html .= self::getPaginationCSSdynamically();
        $html .= "$first$prev$pages$next$last";
        $html .= '</ul>';

        return $html;
    }

    /**
     * Inserts a javascript, which will insert "pagination.css"
     * to the head of the page, after(!) the page is ready.
     *
     * @return string
     */
    public static function getPaginationCSSdynamically()
    {
        $html = '<script>$(document).ready(function(){';
        $html .= '$("<link/>", {
                       rel: "stylesheet",
                       type: "text/css",
                       href: "themes/core/css/pagination.css"
                    }).appendTo("head");';
        $html .= '});</script>';
        return $html;
    }

    public static function renderPageRangeAroundPage($selected_page, $min_page_number, $max_page_number)
    {
        $currentPageString = '<li class="active">{$page}</span>';
        $PageInRangeString = '<li><a href="{$url}">{$page}</a></li>';

        $url = self::getURLForPageInRange($selected_page);

        $html = '';
        for($p = $min_page_number; $p <= $max_page_number; $p++)
        {
            if($p != $selected_page)
            {
                $html .= str_replace(array('{$url}', '{$page}'), array($url, $p), $PageInRangeString);
            }
            else # render the current page
            {
                $html .= str_replace('{$page}', $p, $currentPageString);
            }
        }
        return $html;
    }

    public static function getURLForPageInRange($page)
    {
        $url = self::getDatagrid()->getBaseURL();
        $alias = self::getDatagrid()->getParameterAlias('Page');

        if(defined('MOD_REWRITE'))
        {
            $url .= '/' . $alias . '/' . $page;
        }
        else
        {
            $url .= '&' . $alias . '=' . $page;
        }

        return $url;
    }

    /**
     * Get the items for the dropdownbox of results per page
     *
     * @return string
     */
    public static function getResultsPerPageItems()
    {
        return self::$_resultsPerPageItems;
    }

    /**
     * Render the datagrid table
     *
     * @param string The html-code for the table
     * @return string Returns the html-code of the datagridtable
     */
    private static function renderTable()
    {
        $table_sprintf = '<table class="DatagridTable DatagridTable-%s" cellspacing="0" cellpadding="0" border="0" id="%s">' . CR;
        $table_sprintf .= CR . '%s' . CR ;
        $table_sprintf .= '</table>' . CR;

        $tableContent = '';
        $tableContent .= self::renderTableCaption();
        $tableContent .= self::renderTableBody('one');   # search + pagination
        $tableContent .= self::renderTableHeader();      # this isn't a <thead> tag, but <tbody>
        $tableContent .= self::renderTableBody('two');
        $tableContent .= self::renderTableBody('three');
        $tableContent .= self::renderTableFooter();

        $html = sprintf($table_sprintf, self::getDatagrid()->getAlias(), self::getDatagrid()->getId(), $tableContent);

        return $html;
    }

    /**
     * Render the label
     *
     * @return string Returns the html-code for the label if enabled
     */
    private static function renderLabel()
    {
        if(self::getDatagrid()->isEnabled('Label'))
        {
            return '<div class="DatagridLabel DatagridLabel-' . self::getDatagrid()->getAlias() . '">' . CR . self::getDatagrid()->getLabel() . CR . '</div>';
        }
    }

    /**
     * Render the description
     *
     * @return string Returns the html-code for the description
     */
    private static function renderDescription()
    {
        if(self::getDatagrid()->isEnabled('Description'))
        {
            return '<div class="DatagridDescription DatagridDescription-' . self::getDatagrid()->getAlias() . '">' . CR . self::getDatagrid()->getDescription() . CR . '</div>';
        }
    }

    /**
     * Render the caption
     *
     * @return string Returns the html-code for the caption
     */
    private static function renderTableCaption()
    {
        $html = '';

        if(self::getDatagrid()->isEnabled('Caption'))
        {
            $html .= '<caption>';
            #$html .= self::getDatagrid()->getCaption();
            $html .= self::renderLabel();
            $html .= self::renderDescription();
            $html .= '</caption>' . CR;
        }

        return $html;
    }

    /**
     * Represents a sortstring for a-Tags
     *
     * @param string SortKey
     * @param string SortOrder
     * @return string Returns a string such as index.php?mod=news&action=admin&sortc=Title&sorto=DESC
     */
    private static function getURLStringWithSorting($_SortColumn, $_SortOrder)
    {
        $url_string = sprintf('?%s=%s&%s=%s', self::getDatagrid()->getParameterAlias('SortColumn'), $_SortColumn, self::getDatagrid()->getParameterAlias('SortOrder'), $_SortOrder
        );

        return self::getDatagrid()->appendUrl($url_string);
    }

    /**
     * Render the header
     *
     * @return string Returns the html-code for the header
     */
    private static function renderTableHeader()
    {
        $html = '';

        if(self::getDatagrid()->isEnabled('Header'))
        {
            $html .= '<tbody>' . CR; # @todo OH MY GODDON! <thead> is not working here
            $html .= '<tr>' . CR;
            $html .= self::renderTableRowsHeader();
            $html .= '</tr>' . CR;
            $html .= '</tbody>' . CR; # @todo OMG^2
        }

        return $html;
    }

    /**
     * Render the header of the rows
     *
     * @return string Returns the html-code for the rows-header
     */
    private static function renderTableRowsHeader()
    {
        $html = '';

        foreach(self::getDatagrid()->getColumns() as $column)
        {
            $html .= self::renderTableColumn($column);
        }

        return $html;
    }

    /**
     * Render the pagination for the datagrid
     *
     * @param boolean $_ShowResultsPerPage If true, the drop-down for maximal results per page is shown. Otherwise the total number of items.
     * @return string Returns the html-code for the pagination row
     */
    private static function renderTablePagination($_ShowResultsPerPage = true)
    {
        #Clansuite_Debug::printR('Pagination: ' . self::renderPager());
        $html = '';
        if(self::getDatagrid()->isEnabled('Pagination'))
        {
            $html .= '<tr>';
            #$html .= '<td colspan="1">';
            #$html .= _('Page: ');
            #$html .= '</td>';
            $html .= '<td colspan="' . (self::getDatagrid()->getColumnCount()) . '">';


            $html .= self::renderPager();

            # results per page drop down
            if($_ShowResultsPerPage)
            {
                $html .= '<div class="ResultsPerPage">';
                $html .= '<select name="' . self::getDatagrid()->getParameterAlias('ResultsPerPage') . '" onchange="this.form.submit();">';
                $_ResultsPerPageItems = self::getResultsPerPageItems();
                foreach($_ResultsPerPageItems as $item)
                {
                    $html .= '<option value="' . $item . '" ' . ((self::getDatagrid()->getResultsPerPage() == $item) ? 'selected="selected"' : '') . '>' . $item . '</option>';
                }
                $html .= '</select>';
                $html .= '</div>';
            }
            else # show total number of items in results set
            {
                $html .= '<div class="ResultsPerPage">';
                $html .= self::getDatagrid()->getTotalResultsCount() . _(' items');
                $html .= '</div>';
            }

            $html .= '</td></tr>';
        }
        return $html;
    }

    /**
     * Renders the table body.
     *
     * Render Types:
     * One:     will render Search + Pagination
     * Two:     will render Table Rows
     * Three:   will render Batch Actions + Pagination
     *
     * @see renderTable()
     * @param $type Render type toggle (one, two, three)
     * @return string Returns the html-code for the table body
     */
    private static function renderTableBody($type = 'one')
    {
        $html = '';

        if($type == 'one')
        {
            #$html .= self::renderTableActions();
            $html .= self::renderTableSearch();
            $html .= self::renderTablePagination();
        }

        if($type == 'two' or $type == 'three')
        {
            $html .= '<tbody>';

            if($type == 'two')
            {
                #$html .= self::renderTableActions();
                $html .= self::renderTableRows();
            }

            if($type == 'three')
            {
                $html .= self::renderTableBatchActions();
                $html .= self::renderTablePagination(false);
            }

            $html .= '</tbody>';
        }

        return $html;
    }

    /**
     * Render the actions of the rows
     *
     * @return string Returns the html-code for the actions
     */
    private static function renderTableBatchActions()
    {
        $_BatchActions = self::getDatagrid()->getBatchActions();
        $html = '';

        if(count($_BatchActions) > 0 && self::getDatagrid()->isEnabled('BatchActions'))
        {
            $config = null;
            $config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config')->toArray();

            $html .= '<tr>';
            $html .= '<td class="DatagridBatchActions"><input type="checkbox" class="DatagridSelectAll" /></td>';

            $html .= '<td colspan=' . (self::getDatagrid()->getColumnCount() - 1) . '>';
            $html .= '<select name="action" id="BatchActionId">';
            $html .= '<option value="' . $config['defaults']['action'] . '">' . _('(Choose an action)') . '</option>';
            foreach($_BatchActions as $BatchAction)
            {
                $html .= '<option value="' . $BatchAction['Action'] . '">' . $BatchAction['Name'] . '</option>';
            }
            $html .= '</select>';
            $html .= '<input class="ButtonOrange" type="submit" value="' . _('Execute') . '" />';
            $html .= '</td>';

            $html .= '</tr>';
        }

        return $html;
    }

    /**
     * Render all the rows
     *
     * @return string Returns the html-code for all rows
     */
    private static function renderTableRows()
    {
        $html = '';
        $rowKey = null;

        $rows = self::getDatagrid()->getRows();

        $i = 0;
        foreach($rows as $rowKey => $row)
        {
            $i++;
            # @todo consider removing the css alternating code, in favor of css3 tr:nth-child
            $html .= self::renderTableRow($row, !($i % 2));
        }

        # render a "no results" row
        if($html == '')
        {
            $html .= '<tr class="DatagridRow DatagridRow-NoResults">';
            $html .= '<td class="DatagridCell DatagridCell-NoResults" colspan="' . self::getDatagrid()->getColumnCount() . '">';
            $html .= _('No Results');
            $html .= '</td>';
            $html .= '</tr>';
        }

        unset($rowKey, $rows, $i, $row);

        return $html;
    }

    /**
     * Render a single row
     * HTML: <tr>(.*)</tr>
     *
     * @param $row Clansuite_Datagrid_Row
     * @param $alternate row alternating toggle
     * @return string Returns the html-code for a single row
     */
    private static function renderTableRow($row, $alternate)
    {
        $_alternateClass = '';

        if($alternate === true)
        {
            $_alternateClass = 'Alternate';
        }

        # @todo consider removing the css alternating code, in favor of css3 tr:nth-child
        $html = null;
        $html = '<tr class="DatagridRow DatagridRow-' . $row->getAlias() . ' ' . $_alternateClass . '">';

        $cells = $row->getCells();
        foreach($cells as $oCell)
        {
            $html .= self::renderTableCell($oCell);
        }

        $html .= '</tr>';

        unset($cells, $_alternateClass);

        return $html;
    }

    /**
     * Render a single cell
     * * HTML: <td>(.*)</td>
     *
     * @param Clansuite_Datagrid_Cell
     * @return string Return the html-code for the cell
     */
    private static function renderTableCell($_oCell)
    {
        $html = '';
        $html .= '<td class="DatagridCell DatagridCell-Cell_' . $_oCell->getColumn()->getPosition() . '">';
        $html .= $_oCell->render() . '</td>';

        return $html;
    }

    /**
     * Renders a column
     *
     * <th>ColumnOne</th>
     *
     * - Id Tag
     * - Common and individual Css class tag
     * - displays column name
     * - if sorting is enabled, displayes sort order toggle
     * - sort order toggle is text or icon
     *
     * @param Clansuite_Datagrid_Column
     * @return string Returns the html-code for a single column
     */
    private static function renderTableColumn($columnObject)
    {
        $html = '';
        $html .= '<th id="ColHeaderId-' . $columnObject->getAlias() . '" class="ColHeader ColHeader-' . $columnObject->getAlias() . '">';
        $html .= $columnObject->getName();

        if($columnObject->isEnabled('Sorting'))
        {
            $html .= '&nbsp;<a href="';
            $html .= self::getURLStringWithSorting($columnObject->getAlias(), self::getDatagrid()->getSortDirectionOpposite($columnObject->getSortOrder()));
            $html .= '">';
            $html .= _($columnObject->getSortOrder());
            $html .= '</a>';
        }

        $html .= '</th>';

        return $html;
    }

    /**
     * Render the footer
     *
     * @return string Returns the html-code for the footer
     */
    private static function renderTableFooter()
    {
        if(self::getDatagrid()->isEnabled('Footer'))
        {
            $html = '';
            $html .= '<tfoot>' . CR;
            # @todo getter for footer html
            $html .= '</tfoot>' . CR;
            return $html;
        }
        else
        {
            return; #'<tfoot>&nsbp;</tfoot>';
        }
    }

    /**
     * Renders the search element of the table
     *
     * @return $string HTML Representation of the Table Search Element
     */
    private static function renderTableSearch()
    {
        $html = '';
        if(self::getDatagrid()->isEnabled('Search'))
        {
            $html .= '<tr><td colspan="' . self::getDatagrid()->getColumnCount() . '">';
            $html .= _('Search: ');
            $html .= '<input type="text" value="' . htmlentities($_SESSION['Datagrid_' . self::getDatagrid()->getAlias()]['SearchForValue']) . '" name="' . self::getDatagrid()->getParameterAlias('SearchForValue') . '" />';
            $html .= ' <select name="' . self::getDatagrid()->getParameterAlias('SearchColumn') . '">';
            $columnsArray = self::getDatagrid()->getColumns();
            foreach($columnsArray as $columnObject)
            {
                if($columnObject->isEnabled('Search'))
                {
                    $selected = '';
                    if($_SESSION['Datagrid_' . self::getDatagrid()->getAlias()]['SearchColumn'] == $columnObject->getAlias())
                    {
                        $selected = ' selected="selected"';
                    }
                    $html .= '<option value="' . $columnObject->getAlias() . '"' . $selected . '>' . $columnObject->getName() . '</option>';
                }
            }
            $html .= '</select>';
            $html .= ' <input type="submit" class="ButtonGreen" value="' . _('Search') . '" />';
            $html .= '</td></tr>';
        }

        return $html;
    }

    /**
     * Renders the datagrid table
     *
     * @return string Returns the HTML representation of the datagrid table
     */
    public static function render()
    {
        # Build htmlcode
        $html = '';

        $html .= '<link rel="stylesheet" type="text/css" href="' . WWW_ROOT_THEMES_CORE . 'css/pagination.css" />' . CR;
        $html .= '<link rel="stylesheet" type="text/css" href="' . WWW_ROOT_THEMES_CORE . 'css/datagrid.css" />' . CR;
        $html .= '<script src="' . WWW_ROOT_THEMES_CORE . 'javascript/datagrid.js" type="text/javascript"></script>' . CR;

        $html .= '<form action="' . self::getDatagrid()->getBaseURL() . '" method="post" name="Datagrid-' . self::getDatagrid()->getAlias() . '" id="Datagrid-' . self::getDatagrid()->getAlias() . '">' . CRT;

        #$_htmlCode .= '<input type="hidden" name="action" value="' . Clansuite_Action_Controller_Resolver::getDefaultActionName() . '" />';
        #$_htmlCode .= '<input type="hidden" name="action" id="ActionId" value="' . ((isset($_REQUEST['action'])&&preg_match('#^[0-9a-z_]$#i',$_REQUEST['action']))?$_REQUEST['action']:'show') . '" />';

        /**
         * Add hidden input fields to store the parameters of the datagrid between requests.
         */
        $input_field_sprintf = '<input type="hidden" name="%s" value="%s" />';
        $html .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('Page'), self::getDatagrid()->getPage());
        $html .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('ResultsPerPage'), self::getDatagrid()->getResultsPerPage());
        $html .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('SortColumn'), self::getDatagrid()->getSortColumn());
        $html .= sprintf($input_field_sprintf, self::getDatagrid()->getParameterAlias('SortOrder'), self::getDatagrid()->getSortOrder());

        $html .= '<div class="Datagrid ' . self::getDatagrid()->getClass() . '">' . CR;

        /**
         * Main Method - This will render the table.
         */
        $html .= self::renderTable();

        $html .= '</div>' . CR;

        $html .= '</form>' . CR;

        return $html;
    }

    public function __toString()
    {
        return self::render();
    }

}

?>
