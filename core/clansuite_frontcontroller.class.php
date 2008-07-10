<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @copyright  Jens-Andre Koch (2005 - onwards)
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
 * Interface for Action/Command Controller Resolving
 *
 * The ModuleController_Resolver has to implement the following methods,
 * to resolve the Request to a Action/Command.
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
 * The ModuleController_Resolver has to implement the following methods.
 *
 * @package clansuite
 * @subpackage controller
 * @category interfaces
 */
interface Clansuite_ModuleController_Resolver_Interface
{
    public function getModuleController(httprequest $request);
}

/**
 * Clansuite Controller Resolver
 *
 * This Class is a Resolver Class for Modules and their Actions.
 * 1. extracts infos about the module and action from the REQUEST object
 * 2. via method getModuleController() it returns the appropriate $module_controller module object
 * 3. via method getActionCommand() it returns the appropriate $action_command action object
 *
 * @implements ControllerResolverInterface
 *
 * @package     clansuite
 * @subpackage  controller
 * @category    core
 */
class Clansuite_ModuleController_Resolver implements Clansuite_ModuleController_Resolver_Interface
{
    private $_defaultModule;             # holds the name of the defaultModule
    private static $_ModuleName = null;   # holds the Name of the Module
    private static $_SubModuleName = null;   # holds the Name of the SubModule

    public function __construct($defaultModule)
    {
        $this->_defaultModule    = (string) strtolower($defaultModule);   # set defaultModule
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
        # ModuleName is either the requested modulename or the defaultModule or a set module name
        if( isset(self::$_ModuleName) )
        {
            $module_name = self::getModuleName();
        }
        else
        {
            $module_name = (isset($request['mod']) && !empty($request['mod'])) ? $request->getParameter('mod') : $this->_defaultModule;
        }
       
        # When SubModulName exists, attached to the ModuleName
        if(isset($module_name{1}) && isset($request['sub']) && !empty($request['sub']))
        {
            # get SubModuleName from Request
            if( isset(self::$_SubModuleName) )
            {
                $submodule_name = self::getSubModuleName();
            }
            else
            {
                $submodule_name = $request->getParameter('sub');
            }
            
            # Set the modulename as public static class variables
            self::setSubModuleName($submodule_name);

            # SubModulName is attached to the ModuleName
            $module_name .= '_'. $submodule_name;
            #echo "Module + Submodule => ModuleController => $module_name <br>";
        }

        # Load Modul (require) based on requested module_name
        if(clansuite_loader::loadModul($module_name) == true)
        {
            # Set the module name
            $required_modulename = $module_name;
            #var_dump($module_name);
        }
        else
        {
            # @todo: throw correct Status Header via httprequest - if not found and redirect to default
            
            # Trigger a error to show, that the required module does not exist
            trigger_error('Module does not exist: ' . $module_name);
            #die(var_dump($module_name));
            exit();
            
            # Load Default Module as Fallback (require), because the requested module may not exist!
            clansuite_loader::loadModul($this->_defaultModule);
            # Set the module name
            $required_modulename = $this->_defaultModule;
        }

        # Set the modulename as public static class variables
        $this->setModuleName($required_modulename);

        # Construct Classname to instantiate the required Module
        $class = 'module_' . $required_modulename;

        # Instantiate and Return the Module Object
        $controller = new $class();
        return $controller;
    }

    /**
     * Fire Action !
     *
     * @param string $requested_action the requested action as string
     */
    /*
    public function getActionController($request)
    {
        # get action parameter from URL
        $action = $request->getParameter('action');

        # the pseudo-namesspace prefix 'action_' is used for all actions.
        # this is also a way to ensure some kind of whitelisting via namespacing.
        $method = 'action_'.$action;

        # ensure action is (a) set and (b) not empty and (c) check if method action_$actionxxx
        # exists in the main module class (the one extending this class)
        if(isset($action) && !empty($action) && method_exists($this,$method))
        {
            # set the used action name
            $this->action_name = $action;
            # call the method !
            $this->{$method}();
        }
        # check if the method exists as a drop in action in the module directory
        /*elseif
        {
            if is_file() ....

        }*/
        /*
        else
        {
            # set the used action name
            $this->action_name = $this->config['defaults']['default_action'];
            # set the method name
            $method = 'action_'.$this->config['defaults']['default_action'];
            # call the method !
            $this->{$method}();
        }
    }
    */

    /**
     * Method to set the ModuleName
     *
     * @access private
     */
    public static function setModuleName($moduleName)
    {
        self::$_ModuleName = (string) $moduleName;
    }

    /**
     * Method to get the ModuleName
     *
     * @access public
     * @return $string
     */
    public static function getModuleName()
    {
        #explode("_",self::$_ModuleName);
        return self::$_ModuleName;
    }

    /**
     * Method to set the SubModuleName
     *
     * @access private
     */
    private static function setSubModuleName($SubModuleName)
    {
        self::$_SubModuleName = (string) $SubModuleName;
    }

    /**
     * Method to get the SubModuleName
     *
     * @access public
     * @return $string
     */
    public static function getSubModuleName()
    {
        return self::$_SubModuleName;
    }
}

/**
 * Interface for FrontController
 *
 * The Frontcontroller has to implement the following methods.
 *
 * @package     clansuite
 * @subpackage  controller
 * @category    interfaces
 */
interface Clansuite_FrontController_Interface
{
    public function processRequest(httprequest $request, httpresponse $response);
    public function addPreFilter(Filter_Interface $filter);
    public function addPostFilter(Filter_Interface $filter);
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
 * The constructor takes the Controller_Resolver_Interface (so it's an implementation against an interface)
 * and the Dependency Injector.
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
    public function __construct(Clansuite_ModuleController_Resolver_Interface $resolver, Phemto $injector)
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
    public function addPreFilter(Filter_Interface $filter)
    {
        $this->pre_filtermanager->addFilter($filter);
    }

    /**
     * Method to add a Postfilter
     * Filter is processed after Controller->Action was executed
     */
    public function addPostFilter(Filter_Interface $filter)
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