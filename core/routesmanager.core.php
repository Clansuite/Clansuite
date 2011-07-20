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
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Routes Management
 *
 * On Installation new routes are added via the method addRoutesOfModule($modulename).
 * On Deinstallation the routes are removed via method delRoutesOfModlee($modulename).
 */
class Clansuite_Routesmanager
{
    public function addRoutesOfModule($modulename)
    {
        self::updateApplicationRoutes($modulename);
    }

    public function delRoutesOfModule($modulename)
    {
        # @todo
        $module_routes_file = ROOT_MOD . $modulename . '/' . $modulename . '.routes.php';
        $module_routes = $this->loadRoutesFromConfig($module_routes_file);

        # load main routes file
        $application_routes = $this->loadRoutesFromConfig();

        # subtract the $module_routes from $application_routes array
        $this->deleteRoute($route_name);

        # update / write merged content to application config

    }

    public function deleteRoute($route_name)
    {
        $routes_count = count($this->routes);

        # loop over all routes
        for($i == 0; $i < $routes_count; $i++)
        {
            # check if there is a route with the given name
            if($this->routes[$i]['name'] == $route_name)
            {
                # got one? then remove it from the routes array and stop
                array_splice($this->routes, $i, 1);
                break;
            }
        }

        return $this->routes;;
    }

    /**
     * Registers routing for all activated modules
     *
     * @param string $modulename Name of module
     */
    public function updateApplicationRoutes($modulename = null)
    {
        $activated_modules = array();

        if($modulename === null)
        {
            $activated_modules[] = array($modulename);
        }
        else # get all activated modules
        {
            # $activated_modules =
        }

        foreach($activated_modules as $modulename)
        {
            # load module routing file
            $module_routes_file = ROOT_MOD . $modulename . '/' . $modulename . '.routes.php';
            $module_routes = $this->loadRoutesFromConfig($module_routes_file);

            # load main routes file
            $application_routes = $this->loadRoutesFromConfig();

            # merge the content of modules into application
            # @todo: consider using array_merge_recursive_distinct /unique ?
            $combined_routes = array_merge_recursive($module_routes, $application_routes);

            # update / write merged content to application config
        }
    }

    /**
     * Load Routes from Configuration File
     *
     * @param string Path to a (module) Routing Configuration File.
     * @return array Array of Routes.
     */
    public static function loadRoutesFromConfig($routes_config_file = null)
    {
        $routes = array();

        if($routes_config_file === null)
        {
            # load common routes configuration
            # includes array $routes
            include ROOT_CONFIG . 'routes.config.php';
        }
        else
        {
            # load specific routes config file
            include ROOT . $routes_config_file;
        }

        return (array) $routes;
    }
}
?>