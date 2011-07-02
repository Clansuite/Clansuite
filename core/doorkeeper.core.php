<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * DoorKeeper
 *
 * These are security-methods to keep the entrance to Clansuite clean.
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Doorkeeper
 */
class Clansuite_DoorKeeper
{
    /**
     * Constant string with the uri to the current PHPIDS default filter for download
     */
    const PHPIDS_DEFAULT_FILTER_URI = 'http://dev.itratos.de/projects/php-ids/repository/raw/trunk/lib/IDS/default_filter.xml';

    /**
     * Constant string with the uri to the current Converter.php for download
     */
    const PHPIDS_CONVERTER_URI = 'http://dev.itratos.de/projects/php-ids/repository/raw/trunk/lib/IDS/Converter.php';

    /**
     * Note: content PHPIDS_UPDATE_XML_URI is currently not used for sha1 checking
     */
    #const PHPIDS_UPDATE_XML_URI = http://www.itratos.de/phpids_versions/phpids_update.xml

    /**
     * Initialize phpIDS and run the IDS-Monitoring on all incomming arrays
     *
     * Smoke Example:
     * Apply to URL "index.php?theme=drahtgitter%3insert%00%00.'AND%XOR%XOR%.'DROP WHERE user_id='1';"
     */
    public function runIDS()
    {
        // prevent redeclaration
        if(false === class_exists('IDS_Monitor', false))
        {
            # load ids init
            include ROOT_LIBRARIES . 'IDS/Init.php';

            # Setup the $_GLOBALS to monitor

            $request = array('GET' => $_GET,
                'POST' => $_POST,
                'COOKIE' => $_COOKIE);

            # We have to setup some defines here, which are used by parse_ini_file to replace values in config.ini

            define('IDS_FILTER_PATH', ROOT_LIBRARIES . 'IDS/default_filter.xml');
            define('IDS_TMP_PATH', ROOT_CACHE);
            define('IDS_LOG_PATH', ROOT_LOGS . 'phpids_log.txt');
            define('IDS_CACHE_PATH', ROOT_CACHE . 'phpids_defaultfilter.cache');

            # the following lines have to remain, till PHP_IDS team fixes their lib
            # in order to create the cache file automatically
            if(false === is_file(IDS_CACHE_PATH))
            {
                $filehandle = @fopen(IDS_CACHE_PATH, 'w+');
                if(false === $filehandle)
                {
                    throw new Clansuite_Exception('PHP IDS Cache file couldn\'t be created.', 11);
                }
                fclose($filehandle);
            }

            # autoupdate
            #self::updateIDSFilterRules();

            # Initialize the System with the configuration values
            $init = IDS_Init::init(ROOT_CONFIG . 'phpids_config.ini');

            # Get IDS Monitor: and analyse the Request with Config applied
            $ids = new IDS_Monitor($request, $init);

            # Get Results
            $monitoring_result = $ids->run();

            #var_dump($monitoring_result);
            # if no results, everything is fine
            if(( $monitoring_result->isEmpty() === false ) or ( $monitoring_result->getImpact() > 1 ))
            {
                $access_block_message = 'Access Violation Detected by IDS! Execution stopped!';

                if(DEBUG == true)
                {
                    $access_block_message .= ' <br /> Monitor:' . $monitoring_result;
                }

                # Stop the execution of the application.
                exit($access_block_message);
            }
        }
    }

    public static function updateIDSFilterRules()
    {
        Clansuite_Remotefetch::updateFileIfDifferent(self::PHPIDS_DEFAULT_FILTER_URI, IDS_FILTER_PATH);
        Clansuite_Remotefetch::updateFileIfDifferent(self::PHPIDS_CONVERTER_URI, ROOT_LIBRARIES . 'IDS/Converter.php');
    }

}

?>