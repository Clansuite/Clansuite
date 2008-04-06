<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Filter - Set Breadcrumbs
 *
 * Purpose: Sets the Breadcrumbs
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class set_breadcrumbs implements Filter_Interface
{
    private $config     = null;     # holds instance of config
    private $trail      = null;     # holds instance of trail

    function __construct(configuration $config, trail $trail)
    {
       $this->config    = $config;      # set instance of config to class
       $this->trail     = $trail;      # set instance of trail to class
    }

    public function executeFilter(httprequest $request, httpresponse $response)
    {
        $moduleName = $request->getParameter('mod');
        $actionName = $request->getParameter('action');

        // Set Pagetitle and Breadcrumbs
        $this->trail->addStep( T_( ucfirst($moduleName) ), '/index.php?mod='. $moduleName .'&action'. $actionName );
    }
}
?>