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

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * interface FilterInterface
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filter
 */
interface Clansuite_Filter_Interface
{
    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response);
}

/**
 * FilterManager
 * - is a Intercepting-Filter / FilterChain
 *
 * - $filters is an array containing the filters to be processed
 * - addFilter method, adds them to the array
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filter
 */
class Clansuite_FilterManager
{
    private $filters = array();

    /**
     * addFilter method
     * $filter is type-hinted, to ensure that the array filter only contains Filter-Objects
     *
     * @param object $filter
     */
    public function addFilter(Clansuite_Filter_Interface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * processFilters executes each filter of the filters-array
     *
     * @param request object
     * @param response object
     */
    public function processFilters(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        foreach ($this->filters as $filter)
        {
            $filter->executeFilter($request, $response);
        }
    }
}
?>