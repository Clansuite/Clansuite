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
    * @copyright  Jens-André Koch (2005-onwards)
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
    /**
     * The dispatcher forwards to the pagecontroller = modulecontroller + moduleaction
     */
    public static function forward()
    {
        $route = Clansuite_HttpRequest::getRoute();

         #$event = Clansuite_EventDispatcher::instantiate();
        #$event->addEventHandler('onBeforeControllerMethodCall', new Clansuite_Event_InitializeModule());

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

        Clansuite_Debug::firebug($route);
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
            # instantiate the module controller and pass request and response object to it
            $controllerInstance = new $classname(
                Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpRequest'),
                Clansuite_CMS::getInjector()->instantiate('Clansuite_HttpResponse')
            );
        }
        else
        {
            throw new Clansuite_Exception('There was no controller named "' . $classname . '".');
        }

        /**
         * Initialize the Module
         */
        if(true === method_exists($controllerInstance, 'initializeModule'))
        {
            $controllerInstance->initializeModule();
        }

        # @todo fix wrong position
        Clansuite_Breadcrumb::initBreadcrumbs();

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
    }
}
?>