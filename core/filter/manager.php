<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Filter;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Interface Koch_Filter_Interface
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filter
 */
interface Filter
{
    public function executeFilter(Koch_HttpRequest $request, Koch_HttpResponse $response);
}

/**
 * Koch_FilterManager
 *
 * Is a Intercepting-Filter (FilterChain).
 * The var $filters is an array containing the filters to be processed.
 * The method addFilter() adds filters to the array.
 * A filter has to implement the Koch_Filter_Interface,
 * it has to provide the executeFilter() method.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filter
 */
class Manager
{
    private $filters = array();

    /**
     * addFilter method
     * $filter is type-hinted, to ensure that the array filter only contains Filter-Objects
     *
     * @param object $filter
     */
    public function addFilter(Koch_Filter_Interface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * processFilters executes each filter of the filters-array
     *
     * @param request object
     * @param response object
     */
    public function processFilters(Koch_HttpRequest $request, Koch_HttpResponse $response)
    {
        foreach ($this->filters as $filter)
        {
            $filter->executeFilter($request, $response);
        }
    }
}
?>