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
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Interface for FrontController
 *
 * The Frontcontroller has to implement the following methods.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  FrontController
 */
interface Clansuite_Front_Controller_Interface
{
    public function __construct(Clansuite_Request_Interface $request, Clansuite_Response_Interface $response);
    public function processRequest();
    public function addPreFilter(Clansuite_Filter_Interface $filter);
    public function addPostFilter(Clansuite_Filter_Interface $filter);
}

/**
 * Clansuite FrontController
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
 * @implements  Clansuite_Front_Controller_Interface
 * @category    Clansuite
 * @package     Core
 * @subpackage  FrontController
 */
class Clansuite_Front_Controller implements Clansuite_Front_Controller_Interface
{
     /**
     * @var object Clansuite_Router
     */
    private $router;
    /**
     * @var object Clansuite_Dispatcher
     */
    private $dispatcher;

    /**
     * @var object FilterManager for Prefilters
     */
    private $pre_filtermanager;

    /**
     * @var object FilterManager for Postfilters
     */
    private $post_filtermanager;

    /**
     * Constructor
     */
    public function __construct(Clansuite_Request_Interface $request, Clansuite_Response_Interface $response)
    {
           $this->request            = $request;
           $this->response           = $response;
           $this->dispatcher         = new Clansuite_Dispatcher($request);
           $this->pre_filtermanager  = new Clansuite_Filtermanager();
           $this->post_filtermanager = new Clansuite_Filtermanager();
    }

    /**
     * Add a Prefilter
     *
     * This filter is processed *before* the Controller->Action is executed.
     *
     * @param object $filter Object implementing the Clansuite_Filter_Interface.
     */
    public function addPreFilter(Clansuite_Filter_Interface $filter)
    {
        $this->pre_filtermanager->addFilter($filter);
    }

    /**
     * Add a Postfilter
     *
     * This filter is processed *after* Controller->Action was executed.
     *
     * @param object $filter Object implementing the Clansuite_Filter_Interface.
     */
    public function addPostFilter(Clansuite_Filter_Interface $filter)
    {
        $this->post_filtermanager->addFilter($filter);
    }

    /**
     * Clansuite_Front_Controller::processRequest() = dispatch()
     *
     * Speaking in very basic concepts: this is a RequestHandler.
     * The C in MVC. It handles the dispatching of the request.
     * Calls/executes the apropriate controller and returns a response.
     */
    public function processRequest()
    {
        $this->pre_filtermanager->processFilters($this->request, $this->response);

        $event = Clansuite_EventDispatcher::instantiate();
        $event->triggerEvent('onBeforeDispatcherForward');

        $this->dispatcher->forward();

        $event->triggerEvent('onAfterDispatcherForward');

        $this->post_filtermanager->processFilters($this->request, $this->response);

        $this->response->sendResponse();
    }
}
?>