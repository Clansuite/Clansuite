<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * interface FilterInterface
 *
 * @package clansuite
 * @subpackage core
 * @category interfaces
 */
interface Filter_Interface
{
    public function executeFilter(httprequest $request, httpresponse $response);
}

/**
 * FilterManager
 * - is a Intercepting-Filter / FilterChain
 *
 * - $filters is an array containing the filters to be processed
 * - addFilter method, adds them to the array
 *
 * @package clansuite
 * @subpackage core
 * @category filters
 */
class FilterManager
{
    private $filters = array();
      
    /**
     * addFilter method 
     * $filter is type-hinted, to ensure that the array filter only contains Filter-Objects
     *
     * @param object $filter
     * @access public
     */    
    public function addFilter(Filter_Interface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * processFilters executes each filter of the filters-array
     *
     * @param request object
     * @param response object
     * @access public
     */
    public function processFilters(httprequest $request, httpresponse $response)
    {
        foreach ($this->filters as $filter)
        {
            $filter->executeFilter($request, $response);   
        }   
    }
}
?>