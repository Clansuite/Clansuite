<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
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
 * DoorKeeper
 *
 * These are security-methods to keep the entrance to Clansuite clean.
 * They can be called from httpRequest _constructor or httpRequest could be extended.
 * We have several things handled here:
 * (1) PHP IDS Init (2) Proxy Blocking
 *
 * @author     Jens-Andr� Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-Andr� Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Doorkeeper
 */
class Clansuite_DoorKeeper
{
    /**
     * Initialize phpIDS and run the IDS-Monitoring on all incomming arrays
     *
     * Smoke Example:
     * Apply to URL "index.php?theme=drahtgitter%3insert%00%00.'AND%XOR%XOR%.'DROP WHERE user_id='1';"
     */
    public function runIDS()
    {
        // prevent redeclaration
        if (false === class_exists('IDS_Monitor',false))
        {
            # Additional ini path for PHPDEBUG
            set_include_path(ROOT_LIBRARIES . PATH_SEPARATOR. get_include_path());

            # load ids init
            include ROOT_LIBRARIES . 'IDS/Init.php';

            # Setup the $_GLOBALS to monitor

            $request = array( 'GET'     => $_GET,
                              'POST'    => $_POST,
                              'COOKIE'  => $_COOKIE,
                              'REQUEST' => $_REQUEST ); # @todo determine, if $_REQUEST is redundant and already in $_GET + $_POST

            # We have to setup some defines here, which are used by parse_ini_file to replace values in config.ini

            define( 'IDS_FILTER_PATH',  ROOT_LIBRARIES . 'IDS/default_filter.xml');
            define( 'IDS_TMP_PATH',     ROOT_CACHE);
            define( 'IDS_LOG_PATH',     ROOT . 'logs/phpids_log.txt');
            define( 'IDS_CACHE_PATH',   ROOT_CACHE . '/default_filter.cache');

            # Initialize the System with the configuration values
            $init = IDS_Init::init( ROOT_CONFIG . 'phpids_config.ini');

            # Get IDS Monitor: and analyse the Request with Config applied
            $ids = new IDS_Monitor($request, $init);

            # Get Results
            $monitoring_result = $ids->run();

            #var_dump($monitoring_result);

            # if no results, everything is fine
            if ( ( !$monitoring_result->isEmpty() ) or ( $monitoring_result->getImpact() > 1 ) )
            {
                $access_block_message = 'Access Violation Detected by IDS! Execution stopped!';

                if ( DEBUG == true )
                {
                    $access_block_message .= ' <br /> Monitor:'. $monitoring_result;
                }

                # @todo Use the IDS Logger or our own?
                # require_once 'IDS/Log/File.php';
                # require_once 'IDS/Log/Email.php';
                # require_once 'IDS/Log/Composite.php';
                # $compositeLog = new IDS_Log_Composite();
                # $compositeLog->addLogger(IDS_Log_Email::getInstance($init),IDS_Log_File::getInstance($init));
                # $compositeLog->execute($result);

                # Stop the execution of the application.
                # @todo advanced intrustion handling system (logs, blocking etc.)
                # test the system by adding: &id=17281 union select concat(version(),0x3a,database(),0x3a,user()),2,3--
                exit($access_block_message);
            }
        }
    }

    /**
     * Block Requests from Proxies
     */
    public static function blockProxies()
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