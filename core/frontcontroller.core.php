<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false) { die('Clansuite not loaded. Direct Access forbidden.'); }

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
interface Clansuite_Action_Controller_Resolver_Interface
{
    public function processActionController(Clansuite_HttpRequest $request, Clansuite_Module_Interface $module);
}

class Clansuite_Action_Controller_Resolver implements Clansuite_Action_Controller_Resolver_Interface
{
    /**
     * @var string holds the name of the defaultAction
     */
    private $_defaultAction = '';

    /**
     * @var string holds the Action of the Module
     */
    public static $actionName = null;

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
        # action not set by URL, so we set action from config
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
            @todo call to a single command = ActionController
            # example: 'modulename/controller/commands/action_show.php'
            return 'modulename/controller/commands/'$methodname.'.php';
        }
        */
        else # error
        {

            throw new Clansuite_Exception('Action does not exist: ' . $methodname, 2);
        }
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
        return self::$actionName;
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
interface Clansuite_Module_Controller_Resolver_Interface
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
 * @implements Controller_ResolverInterface
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  ModuleController
 */
class Clansuite_Module_Controller_Resolver implements Clansuite_Module_Controller_Resolver_Interface
{
    /**
     * @var string Name of the Default Module
     */
    private $_defaultmodule;

    /**
     * @var string Modulename
     */
    private static $_modulename = null;

    /**
     * @var string SubModuleName
     */
    private static $_submodulename = null;

    public function __construct($defaultmodule)
    {
        $this->_defaultmodule = (string) strtolower($defaultmodule);
    }

    /**
     * @param <type> $request
     * @return string "module_"
     */
    private function mapModuleController($request)
    {
        $module_name = '';
        $submodule_name = '';
        $required_modulename = '';

        /**
         * ModuleName is either
         * 1) internally set (in case of internal forward)
         * 2) requested by URL
         * 3) the defaultmodule by config value
         */
        if( isset(self::$_modulename) ) # internally set
        {
            $module_name = self::getModuleName();
        }
        elseif ( !empty($request['mod']) ) # set by URL
        {
            $module_name = $request->getParameter('mod');
        }
        else # take the default module defined by config
        {
            $module_name = $this->_defaultmodule;
        }

        # When submodulename exists, attach to the ModuleName
        if(isset($module_name{1}) && isset($request['sub']) && !empty($request['sub']))
        {
            # get SubModuleName from Request
            if( isset(self::$_submodulename) )
            {
                $submodule_name = self::getSubModuleName();
            }
            else
            {
                $submodule_name = $request->getParameter('sub');
            }

            # Set the modulename as public static class variables
            self::setSubModuleName($submodule_name);

            # submodulename is attached to the modulename
            $required_modulename = $module_name . '_'. $submodule_name;
            #echo "Module + Submodule => ModuleController => $module_name <br>";
        }
        else
        {
            $required_modulename = $module_name;
        }

        # Construct Classname to instantiate the required Module
        return 'clansuite_module_' .$required_modulename;
    }

    /**
     * Clansuite_Controller_Resolver->getController()
     *
     * @param $requet input REQUEST-Object
     * @return object controller (module)
     */
    public function getModuleController(Clansuite_HttpRequest $request)
    {
        $module = $this->mapModuleController($request);

        # load modul (require) based on requested module_name
        if(Clansuite_Loader::loadModul($module) == true)
        {
            # Set the modulename as public static class variables
            # like request mod = without "clansuite_module_" prefix and without "_admin"
            $this->setModuleName(Clansuite_Functions::cut_string_backwards(substr($module, 17),'_admin'));

            # instantiate and return the module object
            $controller = new $module();

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
        if(empty(self::$_modulename))
        {
            self::setModuleName(self::$_defaultmodule);
        }

        return self::$_modulename;
    }

    /**
     * Method to set the SubModuleName
     */
    private static function setSubModuleName($SubModuleName)
    {
        self::$_submodulename = (string) $SubModuleName;
    }

    /**
     * Method to get the SubModuleName
     *
     * @return $string
     */
    public static function getSubModuleName()
    {
        return self::$_submodulename;
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
interface Clansuite_Front_Controller_Interface
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
 * @implements  Clansuite_Front_Controller_Interface
 * @category    Clansuite
 * @package     Core
 * @subpackage  FrontController
 */
class Clansuite_Front_Controller implements Clansuite_Front_Controller_Interface
{
    /**
     * @var object actionResolver
     */
    private $actionResolver;

    /**
     * @var object moduleResolver
     */
    private $moduleResolver;

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
     *
     * 1. assign the action resolver object
     * 2. assign the module resolver object
     * 3. assign the injector
     * 4. instantiate pre-filter objects
     * 5. instantiate post-filters objects
     */
    public function __construct(Clansuite_Module_Controller_Resolver_Interface $moduleResolver,
                                Clansuite_Action_Controller_Resolver_Interface $actionResolver)
    {
           $this->actionResolver = $actionResolver;
           $this->moduleResolver = $moduleResolver;
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
     * Clansuite_Front_Controller::processRequest()
     *
     * Speaking in very basic concepts: this is a RequestHandler.
     * The C in MVC. It handles the dispatching of the request.
     * Calls/executes the apropriate controller and returns a response.
     *
     * Processing:
     * 1. get the modulecontroller via Clansuite_Module_Controller_Resolver
     * 2. process preFilters
     * #3. decorate the module with additional actions (plugins)
     * 4. execute initialize module (common tasks for all action of a module)
     * 5. -> execute ACTION <-
     * 6. process postFilters
     * #7. fetches view / implicit getRenderEngine
     * #8. assign view to response / implicit getTemplate
     * 9. flush response
     *
     */
    public function processRequest(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # 1) initialize Module
        $moduleController = $this->moduleResolver->getModuleController($request);

        # 2) process Prefilters
        $this->pre_filtermanager->processFilters($request, $response);

        /**
         * 3) Decorate a Module with a Plugins
         *
         * At this point, we don't want to extend the ModuleController or Module itself,
         * but add functionality at runtime. So we wrap the object into an Decorator (Wrapper)
         * and add plugins.
         */
        #$moduleController = $this->moduleControllerDecorator->decorate($moduleController);

        /**
         * 4) Module initialization
         *
         * You can place common tasks in the initialization method of a module,
         * because this step is performed before the Action Controller is executed.
         * For instance, loading of a library which is used by a majority of actions of that module.
         * If the method initializeModule() is not present, it won't get triggered.
         * 
         * Note, that you are still able to use __construct with dependency injection
         * to fetch whichever object you like in the modulecontroller.
         */
        if(method_exists($moduleController, 'initializeModule'));
        {
            $moduleController->initializeModule($request, $response);
        }

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