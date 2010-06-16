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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Copyleft: All rights reserved. Jens-André Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Dispatcher
 *
 * The dispatcher accepts the found route from the router/mapper and
 * invokes the correct controller and method.
 *
 * Workflow
 * 1. inject Route Object
 * 2. extract infos about correct controller, correct method with correct parameters
 * 3. finally call controller->method(parms)!
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Dispatcher
 */
class Clansuite_Dispatcher
{
    private static $found_route;

    private static $actionname;
    private static $modulename;
    private static $submodulename;

    /**
     * The dispatcher forwards to the pagecontroller = modulecontroller + moduleaction
     */
    public function forward()
    {
        $route = $this->getFoundRoute();

        if($route === null)
        {
            throw new Clansuite_Exception('The dispatcher is unable to forward. No route object given.', 99);
        }

        $filename    = $route->getFilename();
        $classname   = $route->getClassname();
        $method      = $route->getAction();
        $parameters  = $route->getParameters();

        unset($route);

        if(false === is_file($filename) )
        {
            throw new Exception('File not found ' . $filename);
        }
        else
        {
            include $filename;
        }

        if(true === class_exists($classname, false))
        {
            $controllerInstance = new $classname();
        }
        else
        {
            throw new Exception('There was no controller named ' . $classname);
        }

        /**
         * Handle Method
         *
         * 1) check if method exists in module -> CALL
         * 2) check if method exists in module/actions path -> CALL
         * 3) if not found display error -> ERROR
         */
        if(true === method_exists($controllerInstance, $action))
        {
            # set the used action name
            $this->setAction($methodname);

            # call the method on module!
            $controllerInstance->$action($parameters);
        }
        /*
        elseif(is_file(ROOT_MOD . $modulename.'/controller/commands/'.$methodname.'.php') === true)
        {
            # command controller factory ,)
            # create command (by including the file of the actioncontroller)
            # example: 'modulename/controller/commands/action_show.php'
            return ROOT_MOD . $modulename.'/controller/commands/'.$methodname.'.php';
        }*/
        else # error
        {
            throw new Clansuite_Exception('There was no action named ' . $action, 2);
        }

        #Clansuite_Loader::callClassMethod($class, $method, $parameters);
    }

    /**
     * Sets the matching route object to the dispatcher
     *
     * @param object $route Route Object implementing Clansuite_Route_Interface
     * @return object Clansuite_Dispatcher
     */
    public function setFoundRoute(Clansuite_Route_Interface $route)
    {
        self::$found_route = $route;

        return $this;
    }

    /**
     * Gets the matching route object from the dispatcher
     *
     * @return $route Route Object implementing Clansuite_Route_Interface
     */
    public function getFoundRoute()
    {
        return self::$found_route;
    }

    /**
     * Getter for Controller/Modulename
     *
     * @return $string
     */
    public static function getModuleName()
    {
        return self::$modulename;
    }

    /**
     * Getter for SubModulename
     *
     * @return $string
     */
    public static function getSubModuleName()
    {
        return self::$submodulename;
    }

    /**
     * Getter for ActioName
     *
     * @return $string
     */
    public static function getActionName()
    {
        return self::$modulename;
    }
}
?>