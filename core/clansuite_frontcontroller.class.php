<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2007
    * http://www.clansuite.com/
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * abstract interface for Controller Resolving ( Request to Command )
 */
interface ControllerResolverInterface
{
    public function getController(httprequest $request);
}

/**
 * Class Controller Resolving ( Request to Command )
 *
 * 1. extracts infos about mod (and action) from REQUEST
 * 2. returns the appropriate $modulecontroller Modul Object (and Command)
 *
 * @implements ControllerResolverInterface
 */
class clansuite_controllerresolver implements ControllerResolverInterface
{
    private $defaultModule;
    private $defaultAction;

    public function __construct($defaultModule, $defaultAction)
    {
        $this->defaultModule    = $defaultModule;
        $this->defaultAction    = $defaultAction;
    }

    /**
     * clansuite_core::get_controller()
     *
     * @param $requet input REQUEST-Object
     * @return object controller (module)
     * deprecated-> load modul -> instantiate modul -> modul::auto_run();
     */
    public function getController(httprequest $request)
    {
        $module_name = (isset($request['mod']) && !empty($request['mod'])) ? $request['mod'] : $this->defaultModule;

        // Load Modul
        if(clansuite_loader::loadModul($module_name) == true)
        {
            // Construct Classname
            $class = 'module_' . strtolower($module_name);
        }
        else
        {
            // Load Default Module as Fallback
            clansuite_loader::loadModul($this->defaultModule);

            // Construct Classname
            $class = 'module_' . strtolower($this->defaultModule);
        }

        # Return Module Object
        $controller = new $class();
        return $controller;
    }

}

/**
 * abstract interface for ControllerCommands
 * Modules must have an execute function
 * (this was auto_run in Clansuite v0.1 , it's now deprecated)
 *
 */
interface ControllerCommandInterface
{
    public function processRequest(httprequest $request, httpresponse $response);
}

/**
 * clansuite_frontcontroller
 *
 * Is basically a FrontController (which should better be named RequestController)
 * with fassade (addPreFilter, addPostFilter) to both filtermanagers / filterchains on top
 *
 * 1. Intercepts all requests made by the client to the web server made through central "index.php"
 * 2. gets all needed things like Auth, Sessions, Logging, whatever... pluggable or not.
 * 4. decides then which PageController (which module) must be called to process the request: via a path in directory structure.
 *    that means we are dynamically invoking the pagecontroller of the module.
 *
 * @implements ControllerCommandInterface
 */
class clansuite_frontcontroller implements ControllerCommandInterface
{
    /**
     * Private Variables containing
     * the resolver
     * the injector
     * and the PRE and POST Filtermanager Objects
     */
    private $resolver;
    private $injector;
    private $pre_filtermanager;
    private $post_filtermanager;

    /**
     * Constructor
     * 1. assign the resolver object
     * 2  assign the injector
     * 3. instantiate pre/post-filter objects
     */
    public function __construct(ControllerResolverInterface $resolver, $injector)
    {
           $this->resolver = $resolver;
           $this->injector = $injector;
           $this->pre_filtermanager  = new filtermanager();
           $this->post_filtermanager = new filtermanager();
    }

    /**
     * Method to add a Prefilter
     * Filter is processed before Controller->Action is executed
     */
    public function addPreFilter(FilterInterface $filter)
    {
        $this->pre_filtermanager->addFilter($filter);
    }

    /**
     * Method to add a Postfilter
     * Filter is processed after Controller->Action was executed
     */
    public function addPostFilter(FilterInterface $filter)
    {
        $this->post_filtermanager->addFilter($filter);
    }

    /**
     * clansuite_frontcontroller::processRequest()
     *
     * Speaking in very basic concepts: this is a RequestHandler.
     * It handles the dispatching of the request.
     * Calls/executes the apropriate controller
     * and returns a response.
     *
     * Processing:
     * 1. execute preFilters
     * 2. get the modulecontroller via clansuite_controllerresolver
     * 3. set Injector to modulecontroller
     * 4. execute modulecontroller
     * 5. execute postFilters
     * 6. fetches view / getRendererEngine
     * 7. assign view to response / getTemplate
     * 8. flush response
     *
     */
    public function processRequest(httprequest $request, httpresponse $response)
    {
        # 1)
    	$this->pre_filtermanager->processFilters($request, $response);

        # 2)
        $moduleController = $this->resolver->getController($request);

        # 3)
        $moduleController->setInjector($this->injector);

        # 4)
        $moduleController->execute($request, $response);

        # 5)
        $this->post_filtermanager->processFilters($request, $response);

        # 6)
        $view = $moduleController->getRenderEngine();

        # 7) pushes RenderEngine generated Output to the response
        $response->setContent($view->render($moduleController->getTemplateName()));

        # 8)
        $response->flush();
    }
}
?>