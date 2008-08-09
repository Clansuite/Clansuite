<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch � 2005 - onwards
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
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * DoorKeeper
 *
 * These are security-methods to keep the entrance to Clansuite clean.
 * They can be called from httpRequest _constructor or httpRequest could be extended.
 * We have several things handled here:
 * (1) PHP IDS Init (2) Proxy Blocking
 *
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
 *
 * @package     clansuite
 * @subpackage  core
 * @category    httprequest
 */
class Clansuite_DoorKeeper
{
    /**
     * DoorKeeper Constructor
     */
    public function __construct()
    {

    }

    /**
     * Initialize phpIDS and run the IDS-Monitoring on all incomming arrays
     *
     * Smoke Example:
     * Apply to URL "index.php?theme=drahtgitter%3insert%00%00.'AND%XOR%XOR%.'DROP WHERE user_id='1';"
     */
    public function runIDS()
    {
        # Set Path and Require IDS
        set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_LIBRARIES );

        # load ids init
        require_once ROOT_LIBRARIES . 'ids/Init.php';

        # Setup the $_GLOBALS to monitor
        $request = array('GET' => $_GET, 'POST' => $_POST, 'COOKIE' => $_COOKIE);

        # We have to setup some defines here, which are used by parse_ini_file to replace values in config.ini
        define( 'IDS_FILTER_PATH',  ROOT_LIBRARIES . 'ids/default_filter.xml');
        define( 'IDS_TMP_PATH',     ROOT_LIBRARIES . 'ids/tmp/');
        define( 'IDS_LOG_PATH',     ROOT . 'logs/phpids_log.txt');
        define( 'IDS_CACHE_PATH',   ROOT_LIBRARIES . 'ids/tmp/default_filter.cache');

        # Initialize the System with these values
        $init = IDS_Init::init( ROOT_LIBRARIES . 'ids/Config/Config.ini');

        # Get IDS Monitor
        $ids = new IDS_Monitor($request, $init);

        # Get Results
        $monitoring_result = $ids->run();

        #var_dump($monitoring_result);

        # if no results, everything is fine
        if (!$monitoring_result->isEmpty())
        {
           # Take a look at the result object
           exit('Access Blocked by IDS! <br /> Monitor:'. $monitoring_result);
        }
    }

    /**
     * Block Requests from Proxies
     */
    static public function blockProxies()
    {
        $request_headers = array(
                                 'CLIENT_IP',
                                 'FORWARDED',
                                 'FORWARDED_FOR',
                                 'FORWARDED_FOR_IP',
                                 'HTTP_CLIENT_IP',
                                 'HTTP_FORWARDED',
                                 'HTTP_FORWARDED_FOR',
                                 'HTTP_FORWARDED_FOR_IP',
                                 'HTTP_PROXY_CONNECTION',
                                 'HTTP_VIA',
                                 'HTTP_X_FORWARDED',
                                 'HTTP_X_FORWARDED_FOR',
                                 'VIA',
                                 'X_FORWARDED',
                                 'X_FORWARDED_FOR'
                                );

        foreach($request_headers as $request_header)
        {
            if( array_key_exists($request_header,$_SERVER) )
            {
                die('Access Blocked for Proxies!');
            }
        }
    }
}
?>