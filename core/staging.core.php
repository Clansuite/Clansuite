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
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Staging
 *
 * @author     Paul Brand
 * @author     Jens-André Koch
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Config
 */
class Clansuite_Staging
{
    /**
     * Loads a staging configuration file and overloads the given array
     *
     * @param array the array to overload
     * @return array Merged configuration.
     */
    public static function overloadWithStagingConfig($array_to_overload)
    {
        # load staging config
        $staging_config = Clansuite_Config_INI::readConfig(self::getFilename());

        # combine arrays
        return $array_to_overload += $staging_config;
    }

    /**
     * Getter for the staging config filename, which is determined by the servername
     *
     * @return string filename of staging config
     */
    public static function getFilename()
    {
        switch($_SERVER['SERVER_NAME'])
        {
            # development configuration
            case "localhost":
            case "intranet":
            case 'clansuite-dev.com':
            case 'www.clansuite-dev.com':
                $filename = 'development.config.php';
                break;

            # staging configuration
            case 'clansuite-stage.com':
            case 'www.clansuite-stage.com':
                $filename = 'staging.config.php';
                break;

            # intern configuration
            case 'clansuite-intern.com':
            case 'www.clansuite-intern.com':
                $filename = 'intern.config.php';
                break;

            default:
                $filename = 'production.config.php';
        }

        # return full path to the staging config file
        return ROOT . 'configuration' . DS . 'staging' . DS . $filename;
    }
}
?>