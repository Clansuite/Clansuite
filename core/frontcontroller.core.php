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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Interface for Action/Command Controller Resolving
 *
 * The ModuleController_Resolver has to implement the following methods
 * to resolve the Request to a Module and the Action/Command.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  ActionController
 */
interface Clansuite_ActionController_Resolver_Interface
{
    public function processActionController(Clansuite_HttpRequest $request, Clansuite_Module_Interface $module);
}

class Clansuite_ActionController_Resolver implements Clansuite_ActionController_Resolver_Interface
{
    private $_defaultAction;             # holds the name of the defaultAction

    public static $actionName = null;   # holds the Action of the Module

    public function __construct($defaultAction)
    {
        $this->_defaultAction = (string) strtolower($defaultAction);
    }

    /**
    * Maps the action to an method name.
    * The pseudo-namesspace prefix 'action_' is used for all actions.
    * Example: action_show()
    * This is also a way to ensure some kind of whitelisting via namespacing.
    *
    * The use of submodules like News_Admin is also supported.
    * In this case the actionname is action_admin_show().
    *
    * @param  string  the action
    * @param  string  the submodule
    * @return string  the mapped method name
    */
    private function mapAction($action, $submodule = null)
    {
        # action not set by URL, so we set default_action from config
        if( !isset( $action ) )
        {
            # set the method name
            $action = $this->_defaultAction;
        }

        # if $module is set, use it as a prefix on $action
        if( isset($submodule) && ($submodule !== null) )
        {
            $action = $submodule .'_'. $action;
        }

        # Debug Display
        #echo '<br>Methodname of Action: action_'. $action .'<br>';

        # return the complete methodname
        return 'action_' . $action;
    }

    public function processActionController(Clansuite_HttpRequest $request, Clansuite_Module_Interface $module)
    {
        /**
         * construct correct methodname from URI-Parameters
         */
        $methodname = $this->mapAction($request['action'], $request['sub']);

        /**
         * Handle Method
         *
         * 1) check if method exists in module, then call it
         * 2) check if method exists in module/actions path, then call it
         * 3) if not found display error
         *
         */
        if(method_exists($module,$methodname))
        {
            # set the used action name
            self::setActionName($methodname);

            # call the method on module!
            $module->{$methodname}();
        }
        /*
        elseif
        {
            @todo callFileActions
            # example: 'modulename/commands/action_show.php'
            return 'modulename/commands/'$methodname.'.php';
        }
        */
        else # error
        {

            throw new Clansuite_Exception('Action does not exist: ' . $methodname, 1);
        }
    }

    /**
     * Method to set the Action
     *
     * @access private
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
        return self::$actionName;
    }
}

/**
 * Interface for Module Controller Resolving ( Request to Module )
 *
 * The ModuleController_Resolver has to implement the following methods
 * to resolve the Request to a Module.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  ModuleController
 */
interface Clansuite_ModuleController_Resolver_Interface
{
    public function getModuleController(Clansuite_HttpRequest $request);
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
 * @category    Clansuite
 * @package     Core
 * @subpackage  ModuleController
 */
class Clansuite_ModuleController_Resolver implements Clansuite_ModuleController_Resolver_Interface
{
    private $_defaultModule;                # defaultModule

    private static $_ModuleName = null;     # mod
    private static $_SubModuleName = null;  # sub

    public function __construct($defaultModule)
    {
        $this->_defaultModule = (string) strtolower($defaultModule);
    }

    private function mapModuleController($request)
    {
        /**
         * ModuleName is either
         * 1) internally set (in case of internal forward)
         * 2) requested by URL
         * 3) the defaultModule by config value
         */
        if( isset(self::$_ModuleName) ) # internally set
        {
            $module_name = self::getModuleName();
        }
        elseif ( !empty($request['mod']) ) # set by URL
        {
            $module_name = $request->getParameter('mod');
        }
        else # take the default module defined by config
        {
            $module_name = $this->_defaultModule;
        }

        # When SubModulName exists, attach to the ModuleName
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
            $required_modulename = $module_name . '_'. $submodule_name;
            #echo "Module + Submodule => ModuleController => $module_name <br>";
        }
        else
        {
            $required_modulename = $module_name;
        }

        # Construct Classname to instantiate the required Module
        return 'module_' .$required_modulename;
    }

    /**
     * Clansuite_ControllerResolver->getController()
     *
     * @param $requet input REQUEST-Object
     * @return object controller (module)
     */
    public function getModuleController(Clansuite_HttpRequest $request)
    {
        $module = $this->mapModuleController($request);

        # Load Modul (require) based on requested module_name
        if(clansuite_loader::loadModul($module) == true)
        {
            # Set the modulename as public static class variables
            # like request mod = without "module_" prefix and without "_admin"
            $this->setModuleName(Clansuite_Functions::cut_string_backwards(substr($module, 7),'_admin'));

            # Instantiate and Return the Module Object
            $controller = new $module();
            #var_dump($controller);
            return $controller;
        }
        else
        {
            /**
             * the module was not found. we cannot create it, because system is not in debug/dev mode.
             * @todo throw correct Status Header via httprequest - if not found and redirect to default
             */
            throw new Clansuite_Exception('Module does not exist: ' . $module, 3);
        }
    }

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
     * @return $string
     */
    public static function getModuleName()
    {
        if(empty(self::$_ModuleName))
        {
            self::setModuleName($_defaultModule);
        }
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
 * @category    Clansuite
 * @package     Core
 * @subpackage  FrontController
 */
interface Clansuite_FrontController_Interface
{
    public function processRequest(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response);
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
 * @implements  Clansuite_FrontController_Interface
 * @category    Clansuite
 * @package     Core
 * @subpackage  FrontController
 */
class Clansuite_FrontController implements Clansuite_FrontController_Interface
{
    /**
     * Private Variables containing
     * the resolver
     * the injector
     * and the PRE and POST Filtermanager Objects
     */
    private $actionResolver;
    private $moduleResolver;
    private $injector;
    private $pre_filtermanager;
    private $post_filtermanager;

    /**
     * Constructor
     *
     * 1. assign the action resolver object
     * 2. assign the module resolver object
     * 3. assign the injector
     * 4. instantiate pre-filter objects
     * 5. instantiate post-filters objects
     */
    public function __construct(Clansuite_ModuleController_Resolver_Interface $moduleResolver,
                                Clansuite_ActionController_Resolver_Interface $actionResolver,
                                Phemto $injector)
    {
           $this->actionResolver = $actionResolver;
           $this->moduleResolver = $moduleResolver;
           $this->injector = $injector;
           $this->pre_filtermanager  = new Clansuite_Filtermanager();
           $this->post_filtermanager = new Clansuite_Filtermanager();
    }

    /**
     * Method to add a Prefilter
     * Filter is processed before Controller->Action is executed
     */
    public function addPreFilter(Clansuite_Filter_Interface $filter)
    {
        $this->pre_filtermanager->addFilter($filter);
    }

    /**
     * Method to add a Postfilter
     * Filter is processed after Controller->Action was executed
     */
    public function addPostFilter(Clansuite_Filter_Interface $filter)
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
     * 2. process preFilters
     * 3. set Injector to modulecontroller
     * 4. execute modulecontroller
     * 5. execute action
     * 6. process postFilters
     * 7. fetches view / implicit getRenderEngine
     * 8. assign view to response / implicit getTemplate
     * 9. flush response
     *
     */
    public function processRequest(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # 1) initialize Module
        $moduleController = $this->moduleResolver->getModuleController($request);

        # 2) process Prefilters
        $this->pre_filtermanager->processFilters($request, $response);

        # 3) insert Injector
        $moduleController->setInjector($this->injector);

        # 4) Module execute (pre_processActionController)
        $moduleController->execute($request, $response);

        /**
         * Plugins for Modules
         * At this point, we don't want to extend the ModuleController or Module itself,
         * but add functionality at runtime. So we wrap the object into an Decorator (Wrapper)
         * and add plugins.
         */
        #$moduleController = $this->moduleControllerDecorator->decorate($moduleController);

        # 5) Fire Action !
        $this->actionResolver->processActionController($request, $moduleController);

        # 6) process Postfilters
        $this->post_filtermanager->processFilters($request, $response);

        // moved to controller_base::prepareOutput
        # 7)
        #$view = $moduleController->getRenderEngine();

        # 8) pushes RenderEngine generated Output to the response
        #$response->setContent($view->render($moduleController->getTemplateName()));

        # 9) flush response
        $response->flush();
    }
}
?>