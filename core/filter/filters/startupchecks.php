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
 * Koch Framework - Filter performing Startup Checks.
 *
 * Purpose: Perform Various Startup Check before running a Koch Framework Module.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Filters
 */
class StartupChecks implements FilterInterface
{
    public function executeFilter(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        # ensure smarty "tpl_compile" folder exists
        if(false === is_dir(ROOT_CACHE . 'tpl_compile') and
          (false === @mkdir(ROOT_CACHE .'tpl_compile', 0755, true)))
        {
            throw new Koch_Exception('Smarty Template Directories not existant.', 9);
        }

        # ensure smarty "cache" folder exists
        if(false === is_dir(ROOT_CACHE . 'tpl_cache') and
          (false === @mkdir(ROOT_CACHE .'tpl_cache', 0755, true)))
        {
            throw new Koch_Exception('Smarty Template Directories not existant.', 9);
        }

        # ensure smarty folders are writable
        if(false === is_writable(ROOT_CACHE . 'tpl_compile') or
          (false === is_writable(ROOT_CACHE . 'tpl_cache')))
        {
            # if not, try to set writeable permission on the folders
            if((false === chmod(ROOT_CACHE . 'tpl_compile', 0755)) and
               (false === chmod(ROOT_CACHE . 'tpl_cache', 0755)))
            {
                throw new Koch_Exception('Smarty Template Directories not writable.', 10);
            }
        }
    }
}
?>