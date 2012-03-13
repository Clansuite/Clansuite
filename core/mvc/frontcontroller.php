<?php
   /**
    * Koch Framework
    * Jens-Andr� Koch � 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\MVC;

use Koch\Filter\FilterInterface;
use Koch\View\Helper\Breadcrumb;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Interface for FrontController
 *
 * The Frontcontroller has to implement the following methods.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  FrontController
 */
interface FrontControllerInterface
{
    public function __construct(HttpRequestInterface $request, HttpResponseInterface $response);
    public function processRequest();
    public function addPreFilter(FilterInterface $filter);
    public function addPostFilter(FilterInterface $filter);
}

/**
 * Koch FrameworkFrontController
 *
 * It's basically a FrontController (which should better be named RequestController)
 * with fassade (addPreFilter, addPostFilter) to both filtermanagers / filterchains on top.
 *
 * It's tasks are:
 * 1. intercepts all requests made by the client to the web server through central "index.php"
 * 2. gets all needed "pre action processing" things like Auth, Sessions, Logging, whatever... pluggable or not.
 * 3. decides then, which ModuleController we must dynamically invoking to process the request
 *
 * The constructor takes the ModuleController_Resolver_Interface (so it's an implementation against an interface),
 * the ActionController_Resolver_Interface (so it's again an implementation against an interface)
 * and the Dependency Injector.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  FrontController
 */
class FrontController implements FrontControllerInterface
{
    /**
     * @var object \Koch_HttpRequest
     */
    private $request;

    /**
     * @var object \Koch_HttpResponse
     */
    private $response;

    /**
     * @var object \Koch_Router
     */
    private $router;

    /**
     * @var object \Koch_FilterManager for Prefilters
     */
    private $pre_filtermanager;

    /**
     * @var object \Koch_FilterManager for Postfilters
     */
    private $post_filtermanager;

    /**
     * @var object \Koch_EventDispatcher
     */
    private $event_dispatcher;

    /**
     * Constructor
     */
    public function __construct(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        $this->request            = $request;
        $this->response           = $response;
        $this->pre_filtermanager  = new \Koch\Filter\Manager();
        $this->post_filtermanager = new \Koch\Filter\Manager();
        $this->event_dispatcher   = \Koch\Event\Dispatcher::instantiate();
        $this->router             = new \Koch\Router\Router($this->request);

        $this->router->route();
    }

    /**
     * Add a Prefilter
     *
     * This filter is processed *before* the Controller->Action is executed.
     *
     * @param object $filter Object implementing the Koch_Filter_Interface.
     */
    public function addPreFilter(FilterInterface $filter)
    {
        $this->pre_filtermanager->addFilter($filter);
    }

    /**
     * Add a Postfilter
     *
     * This filter is processed *after* Controller->Action was executed.
     *
     * @param object $filter Object implementing the Koch_Filter_Interface.
     */
    public function addPostFilter(FilterInterface $filter)
    {
        $this->post_filtermanager->addFilter($filter);
    }

    /**
     * Koch_Front_Controller::processRequest() = dispatch()
     *
     * Speaking in very basic concepts: this is a RequestHandler.
     * The C in MVC. It handles the dispatching of the request.
     * Calls/executes the apropriate controller and returns a response.
     */
    public function processRequest()
    {
        $this->pre_filtermanager->processFilters($this->request, $this->response);

        $this->event_dispatcher->triggerEvent('onBeforeDispatcherForward');

        $this->forward($this->request, $this->response);

        $this->event_dispatcher->triggerEvent('onAfterDispatcherForward');

        $this->post_filtermanager->processFilters($this->request, $this->response);

        $this->response->sendResponse();
    }

    /**
     * The dispatcher accepts the found route from the router/mapper and
     * invokes the correct controller and method.
     *
     * Workflow
     * 1. fetches Route Object
     * 2. extracts info about correct controller, correct method with correct parameters
     * 3. tries to call the method "initializeModule" on the controller
     * 4. finally tries to call the controller with method(parms)!
     *
     * The dispatcher forwards to the pagecontroller = modulecontroller + moduleaction
     */
    public function forward($request, $response)
    {
        # fetch the target route from the request
        $route = $request->getRoute();

        if($route === null)
        {
            throw new \Exception('The dispatcher is unable to forward. No route object given.', 99);
        }

        $classname    = $route::getClassname();
        $method       = $route::getMethod();
        $parameters   = $route::getParameters();
        #$request_meth = Koch_HttpRequest::getRequestMethod();
        #$renderengine = $route::getRenderEngine();

        #$this->event_dispatcher->addEventHandler('onBeforeControllerMethodCall', new Koch_Event_InitializeModule());

        $route::dispatchable();

        $controllerInstance = new $classname($request, $response);

        /**
         * Initialize the Module
         *
         * by calling the "_initializeModule" method on the controller.
         * A module might(!) implement this method for initialization of helper objects.
         *
         * Note the underscore! The method name is intentionally underscored.
         * This places the method on top in the method navigator of your IDE.
         */
        if(true === method_exists($controllerInstance, '_initializeModule'))
        {

            $controllerInstance->_initializeModule();
        }

        Breadcrumb::initialize($route->getModuleName(), $route->getSubmoduleName());

        /**
         * Finally: dispatch to the requested controller method
         */
        if(true === method_exists($controllerInstance, $method))
        {
            $controllerInstance->$method($parameters);
        }
    }
}
?>