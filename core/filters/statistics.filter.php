<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id: view_smarty.class.php 2530 2008-09-18 23:12:04Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite Filter - Update Visitor Statistics
 *
 * Purpose: this updates the statistics with the data of the current visitor
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class statistics implements Clansuite_Filter_Interface
{
    private $config     = null;
    private $statistics = null;

    function __construct(Clansuite_Config $config, Clansuite_Statistics $statistics)
    {
       $this->config     = $config;
       $this->statistics = $statistics;
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        // take the initiative or pass through (do nothing)
        if($this->config['statistics']['enabled'] == 1)
        {
            # aquire pieces of informtion from current visitor

            /**
             * Determine the client's browser and system information based on the HTTP
             * with PHPSniff by Roger Raymond.
             *
             * @link http://phpsniff.sourceforge.net/
             * @link http://phpsniff.sourceforge.net/docs/
             */

            # load library
            require_once ROOT_LIBRARIES . '/phpSniffer/phpSniff.class.php';

            # instantiate phpsniff
            #$phpSniff = new phpSniff($_SERVER["HTTP_USER_AGENT"]);
            #var_dump($phpSniff);
            #exit;

            # Get the browser type and version
            #$browserShorthand = $phpSniff->property('browser');
            #$browserVersion   = $phpSniff->property('maj_ver').$phpSniff->property('min_ver');

            # and store it to DB

        }// else => bypass
    }
}
?>