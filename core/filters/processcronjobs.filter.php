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
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Filter - Process Cronjobs
 *
 * Purpose: processes regular jobs (cron-daemon like)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_ProcessCronjobs implements Clansuite_Filter_Interface
{
    private $config     = null;
    private $cronjobs    = null;

    public function __construct(Clansuite_Config $config, Clansuite_Cronjobs $cronjobs)
    {
        $this->config   = $config;
        $this->cronjobs = $cronjobs;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        // take the initiative, if cronjob processing is enabled in Clansuite_Config
        // or pass through (do nothing)
        if($this->config['cronjobs']['enabled'] == 1)
        {
            $this->cronjobs->execute();
        }// else => bypass
    }
}
?>