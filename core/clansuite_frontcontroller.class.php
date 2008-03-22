<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
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
    * @copyright  Jens-Andre Koch (2005-2008)
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
 * Interface for Action/Command Controller Resolving ( Request to Command/Action )
 *
 * @package clansuite
 * @subpackage controller
 * @category interfaces
 *//*
interface Clansuite_ActionControllerResolver_Interface
{
    public function getActionController(httprequest $request);
}

class Clansuite_ActionControllerResolver implements Clansuite_ActionControllerResolver_Interface
{
    private $defaultAction;     # holds the name of the defaultAction
    public static $actionName = null;         # holds the Action of the Module

    public function __construct($defaultAction)
    {
        $this->defaultAction    = (string) strtolower($defaultAction);   # set defaultAction
    }

    public function getActionController(httprequest $request)
    {
        # Check if action is set and exists as module_class->action
        # if positive, set the action as ModuleAction, else set the standard action
        # @todo: filter this? (only chars+undescore: like action_names) !
        $action = $request->getParameter('action');
        if(isset($action) && !empty($action) && method_exists($class,$action))
        {
            self::setModuleAction($action);
        }
        else
        {
            self::setModuleAction($this->defaultAction);
        } 
    }

    /**
     * Method to set the Action
     *
     * @access private
     *//*
    public static function setModuleAction($actionName)
    {
        self::$actionName = (string) $actionName;
    }

    /**
     * Method to get the Action
     *
     * @access public
     * @return $string
     *//*
    public static function getModuleAction()
    {
        return self::$actionName;
    }
}*/

/**
 * Interface for Module Controller Resolving ( Request to Module )
 *
 * @package clansuite
 * @subpackage controller
 * @category interfaces
 */
interface Clansuite_ModuleControllerResolver_Interface
{
    public function getModuleController(httprequest $request);
}

/**
 * Clansuite Controller Resolver ( Request to Command )
 *
 * This Class
 * 1. extracts infos about the module (and action) from REQUEST
 * 2. and returns the appropriate $module_controller Modul Object (and Command)
 *
 * @implements ControllerResolverInterface
 *
 * @package     clansuite
 * @subpackage  controller
 * @category    core
 */
class Clansuite_ModuleControllerResolver implements Clansuite_ModuleControllerResolver_Interface
{
    private $defaultModule;     # holds the name of the defaultModule
    public static $moduleName = null;         # holds the Name of the Module

    public function __construct($defaultModule)
    {
        $this->defaultModule    = (string) strtolower($defaultModule);   # set defaultModule
    }

    /**
     * Clansuite_ControllerResolver->getController()
     *
     * @param $requet input REQUEST-Object
     * @return object controller (module)
     * deprecated v0.1 -> load modul -> instantiate modul -> modul::auto_run();
     */
    public function getModuleController(httprequest $request)
    {
        # ModulName is either the requested modulename or the defaultModule
        $module_name = (isset($request['mod']) && !empty($request['mod'])) ? $request->getParameter('mod') : $this->defaultModule;

        # Load Modul (require) based on requested module_name
        if(clansuite_loader::loadModul($module_name) == true)
        {
            # Set the module name
            $required_modulname = $module_name;
        }
        else
        {
            # Load Default Module as Fallback (require), because the requested module may not exist!
            clansuite_loader::loadModul($this->defaultModule);
            # Set the module name
            $required_modulname = $this->defaultModule;
        }

        # Set the modulename and moduleaction as public static class variables
        $this->setModuleName($required_modulname);

        # Construct Classname to instantiate the required Module
        $class = 'module_' . strtolower($required_modulname);

        # Instantiate and Return the Module Object
        $controller = new $class();
        return $controller;
    }

    /**
     * Method to set the ModuleName
     *
     * @access private
     */
    private static function setModuleName($moduleName)
    {
        self::$moduleName = (string) $moduleName;
    }

    /**
     * Method to get the ModuleName
     *
     * @access public
     * @return $string
     */
    public static function getModuleName()
    {
        return self::$moduleName;
    }
}

/**
 * Interface for FrontController
 *
 * Frontcontroller has to define processRequest()
 *
 * @package     clansuite
 * @subpackage  controller
 * @category    interfaces
 */
interface Clansuite_FrontController_Interface
{
    public function processRequest(httprequest $request, httpresponse $response);
    public function addPreFilter(FilterInterface $filter);
    public function addPostFilter(FilterInterface $filter);
}

/**
 * Clansuite FrontController
 *
 * Is basically a FrontController (which should better be named RequestController)
 * with fassade (addPreFilter, addPostFilter) to both filtermanagers / filterchains on top
 *
 * 1. Intercepts all requests made by the client to the web server made through central "index.php"
 * 2. gets all needed things like Auth, Sessions, Logging, whatever... pluggable or not.
 * 3. decides then which PageController (which module) must be called to process the request:
 *    via a path in directory structure.
 * That means: we are dynamically invoking the pagecontroller of the module.
 *
 * @implements ControllerCommandInterface
 * @package     clansuite
 * @category    core
 * @subpackage  controller
 */
class Clansuite_FrontController implements Clansuite_FrontController_Interface
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
    public function __construct(Clansuite_ModuleControllerResolver_Interface $resolver, Phemto $injector)
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
     * 1. get the modulecontroller via clansuite_controllerresolver
     * 2. execute preFilters
     * 3. set Injector to modulecontroller
     * 4. execute modulecontroller
     * 5. execute postFilters
     * 6. fetches view / implicit getRenderEngine
     * 7. assign view to response / implicit getTemplate
     * 8. flush response
     *
     */
    public function processRequest(httprequest $request, httpresponse $response)
    {
        # 1)
        $moduleController = $this->resolver->getModuleController($request);

        # 2)
        $this->pre_filtermanager->processFilters($request, $response);

        # 3)
        $moduleController->setInjector($this->injector);

        # 4)
        $moduleController->execute($request, $response);

        # 5)
        $this->post_filtermanager->processFilters($request, $response);

        // moved to controller_base::prepareOutput
        # 6)
        #$view = $moduleController->getRenderEngine();

        # 7) pushes RenderEngine generated Output to the response
        #$response->setContent($view->render($moduleController->getTemplateName()));

        # 8)
        $response->flush();
    }
}
?>