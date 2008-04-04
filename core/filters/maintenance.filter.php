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
/**
 * Clansuite Filter - Maintenace Mode
 *
 * Purpose: Display Maintenace Template
 * When config parameter 'maintenance' is set, the maintenance template will be displayed
 *
 * @package clansuite
 * @subpackage filters
 * @implements FilterInterface
 */
class maintenance implements Filter_Interface
{
    private $config     = null;     # holds instance of config

    function __construct(configuration $config)
    {
        $this->config    = $config;      # set instance of config to class
    }

    public function executeFilter(httprequest $request, httpresponse $response)
    {
        /**
         * take the initiative,
         * if maintenance is enabled in CONFIG
         * or pass through (do nothing)
         */
        if($this->config['maintenance'] == 1)
        {
            # @todo: a) create template?
            # @todo: b) create override of maintenance mode, in case it's an admin user?
            $response->setContent($this->config['maintenance_reason']);
            $response->flush();
            exit();
        }
    } // else => bypass
}
?>