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
    * @version    SVN: $Id: language_via_get.filter.php 2614 2008-12-05 21:18:45Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite Filter - Startup Checks
 *
 * Purpose: Perform Various Startup Check before running a Clansuite Module.
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class startup_checks implements Filter_Interface
{
    private $config     = null;     # holds instance of config

    function __construct(Clansuite_Config $config)
    {
       $this->config    = $config;      # set instance of config to class
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # Check if Smarty Output Dirs are writable
        if (!is_writable( ROOT . 'cache/templates_c') or !is_writable( ROOT . 'cache/cache' ))
        {

        	throw new Clansuite_Exception('Smarty Template Directories not writable.', 10);
        }
    }
}
?>