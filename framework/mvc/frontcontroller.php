<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\MVC;

use Koch\Filter\FilterInterface;
use Koch\View\Helper\Breadcrumb;

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
 * Koch Framework FrontController
 *
 * It's basically a FrontController (which should better be named RequestController)
 * with fassade to both filtermanagers (addPreFilter, addPostFilter) on top.
 *
 * It's tasks are:
 * 1. to intercept all requests made by the client to the web server through central "index.php".
 * 2. to get all needed "pre action processing" done; things like Auth, Sessions, Logging, whatever... pluggable or not.
 * 3. to decide then, which ModuleController we must dynamically invoking to process the request.
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
    private $pre_filter_manager;

    /**
     * @var object \Koch_FilterManager for Postfilters
     */
    private $post_filter_manager;

    /**
     * @var object \Koch_EventDispatcher
     */
    private $event_dispatcher;

    /**
     * Constructor
     */
    public function __construct(HttpRequestInterface $request, HttpResponseInterface $response)
    {
        $this->request             = $request;
        $this->response            = $response;
        $this->pre_filter_manager  = new \Koch\Filter\Manager();
        $this->post_filter_manager = new \Koch\Filter\Manager();
        $this->event_dispatcher    = \Koch\Event\Dispatcher::instantiate();
        $this->router              = new \Koch\Router\Router($this->request);
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
        $this->pre_filter_manager->addFilter($filter);
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
        $this->post_filter_manager->addFilter($filter);
    }

    /**
     * Front_Controller::processRequest() = dispatch()
     *
     * Speaking in very basic concepts: this is a RequestHandler.
     * The C in MVC. It handles the dispatching of the request.
     * Calls/executes the apropriate controller and returns a response.
     */
    public function processRequest()
    {
        $this->router->route();

        $this->pre_filter_manager->processFilters($this->request, $this->response);

        $this->event_dispatcher->triggerEvent('onBeforeDispatcherForward');

        $this->forward($this->request, $this->response);

        $this->event_dispatcher->triggerEvent('onAfterDispatcherForward');

        $this->post_filter_manager->processFilters($this->request, $this->response);

        $this->response->sendResponse();
    }

    /**
     * The dispatcher accepts the found route from the route mapper and
     * invokes the correct controller and method.
     *
     * Workflow
     * 1. fetches Route Object
     * 2. extracts info about correct controller, correct method with correct parameters
     * 3. tries to call the method "initializeModule" on the controller
     * 4. finally tries to call the controller with method(parms)!
     *
     * The dispatcher forwards to the pagecontroller = modulecontroller + moduleaction.
     */
    public function forward($request, $response)
    {
        // fetch the target route from the request
        $route = $request->getRoute();

        if ($route === null) {
            throw new \Exception('The dispatcher is unable to forward. No route object given.', 99);
        }

        //$route::debug();

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
        if (true === method_exists($controllerInstance, '_initializeModule')) {

            $controllerInstance->_initializeModule();
        }

        /**
         * "Before Module Filter" is a prefilter on the module controller level.
         *
         *
         * It calls the "_beforeFilter" method on the module controller.
         * A module might(!) implement this method for initialization of helper objects.
         * Example usage: login_required.
         *
         * Note the underscore! The method name is intentionally underscored.
         * This places the method on top in the method navigator of your IDE.
         */
        if (true === method_exists($controllerInstance, '_beforeFilter')) {

            $controllerInstance->_beforeFilter();
        }

        // @todo move into a prefilter / and consider the request being ajax :) = no breadcrumbs
        Breadcrumb::initialize($route->getModuleName(), $route->getSubmoduleName());

        /**
         * Finally: dispatch to the requested controller method
         */
        if (true === method_exists($controllerInstance, $method)) {

            $controllerInstance->$method($parameters);
        }

         /**
         * "After Module Filter" is a postfilter on the module controller level.
         *
         * It calls the "_afterFilter" method on the module controller.
         * A module might(!) implement this method for running further processing
         * on reponse data.
         *
         * Note the underscore! The method name is intentionally underscored.
         * This places the method on top in the method navigator of your IDE.
         */
        if (true === method_exists($controllerInstance, '_afterFilter')) {

            $controllerInstance->_afterFilter();
        }
    }
}
