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
     * Sets the matching route object to the dispatcher
     *
     * @param object $route Route Object implementing Clansuite_Route_Interface
     * @return object Clansuite_Dispatcher
     */
    public function setFoundRoute($route) # (Clansuite_Route_Interface $route)
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
     * The dispatcher forwards to the pagecontroller = modulecontroller + moduleaction
     */
    public function forward()
    {
        $route = $this->getFoundRoute();

        #$event = Clansuite_EventDispatcher::instantiate();
        #$event->addEventHandler('onBeforeControllerMethodCall', new Clansuite_Event_InitializeModule());

        #Clansuite_Debug::firebug($route);

        if($route === null)
        {
            throw new Clansuite_Exception('The dispatcher is unable to forward. No route object given.', 99);
        }

        $filename     = $route->getFilename();
        $classname    = $route->getClassname();
        $method       = $route->getMethod();
        $parameters   = $route->getParameters();
        $request_meth = Clansuite_HttpRequest::getRequestMethod();
        $renderengine = $route->getRenderEngine();

        #Clansuite_Debug::firebug($route);
        #unset($route);

        /**
         * The file we want to call has to exists
         */
        if(false === is_file($filename) )
        {
            throw new Clansuite_Exception('File not found "' . $filename .'".');
        }
        else
        {
            include $filename;
        }

        /**
         * Inside this file, the correct class has to exist
         */
        if(true === class_exists($classname, false))
        {
            $controllerInstance = new $classname();
        }
        else
        {
            throw new Clansuite_Exception('There was no controller named "' . $classname . '".');
        }

        /**
         * Initialize the Module
         */
        $controllerInstance->initializeModule(
                Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpRequest'),
                Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpResponse')
        );

        /**
         * Handle Method
         *
         * 1) check if method exists in class (meaning the module file) -> CALL
         * 2) check if method exists in module/actions path (command factory) -> CALL
         * 3) if not found display error -> ERROR
         */
        if(true === method_exists($controllerInstance, $method))
        {
            $controllerInstance->$method($parameters);
        }
        /*
        elseif(is_file(ROOT_MOD . $modulename.'/controller/commands/'.$methodname.'.php') === true)
        {
            # command controller factory
            # create command (by including the file of the actioncontroller)
            # example: 'modulename/controller/commands/action_show.php'
            return ROOT_MOD . $modulename.'/controller/commands/'.$methodname.'.php';
        }*/
        else # error
        {
            throw new Clansuite_Exception('There was no action named "' . $method . '".', 2);
        }

        #Clansuite_Loader::callClassMethod($class, $method, $parameters);
    }

    /**
     * Method to set the Action
     */
    public static function setActionName($actionName)
    {
        self::$actionName = (string) $actionName;
    }

    /**
     * Method to get the Action
     *
     * @return $string
     */
    public static function getActionName()
    {
        return self::$found_route->getMethod();
    }

    /**
     * Method to get the DefaultAction
     *
     * @return $string
     */
    public static function getDefaultActionName()
    {
        return self::$_defaultAction;
    }

    /**
     * Method to set the ModuleName
     */
    public static function setModuleName($moduleName)
    {
        self::$_modulename = (string) $moduleName;
    }

    /**
     * Method to get the ModuleName
     *
     * @return $string
     */
    public static function getModuleName()
    {
        return self::$found_route->getController();
    }

    /**
     * Method to set the SubModuleName
     */
    private static function setSubModuleName($SubModuleName)
    {
        self::$submodulename = (string) $SubModuleName;
    }

    /**
     * Method to get the SubModuleName
     *
     * @return $string
     */
    public static function getSubModuleName()
    {
        return self::$submodulename;
    }
}
?>