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
 * Interface for all modules
 *
 * Force classes implementing the interface to define this (must have) methods!
 *
 * @package     clansuite
 * @subpackage  controller
 * @category    interfaces
 */
interface Clansuite_Module_Interface
{
    # always needed is the main execute() method
    function execute(httprequest $request, httpresponse $response);
}

/**
 * ModuleController
 *
 * Is an abstract class (parent class) to share some common features
 * for all (Module/Action)-Controllers.
 * You could call it ModuleController and ActionController.
 * It`s abstract because it should only extended, not instantiated.
 *
 * 1. saves a copy of the cfg class
 * 2. makes sure that controllers have an index() and execute() method
 * 3. provide access to create_global_view
 *
 */
abstract class ModuleController extends Clansuite_ModuleController_Resolver
{
    /**
     * Variable $output contains the output (view-data) of the module
     * @todo output should be in response object or in a composite structured output class.
     * @access protected
     */
    protected $output = null;

    // Variable contains the rendering engine (view object)
    public $view = null;

    // Variable contains the name of the rendering engine
    public $renderEngineName = null;

    // Variable contains the name of the template
    public $template = null;

    // Variable contains the Dependecy Injector
    public $injector = null;                    # dynamic
    static $static_injector = null;             # static

    // Variable contains the Configuration Object
    public $config = null;

    // Variable contains the Name of the Action
    public $action_name = null;

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {

    }

    /**
     * Set dependency injector (SetterInjection)
     * Type Hint set to only accept Phemto
     *
     * @param object $injector Dependency Injector (Phemto)
     * @access public
     * @TODO: move config injection somewhere else
     */
    public function setInjector(Phemto $injector)
    {
        # Set the incomming $injector
        # a) as a static var
        # b) as a dynamic var
    	self::$static_injector = $this->injector = $injector;
    	# fetch config from dependency injector
    	$this->config = $this->injector->instantiate('Clansuite_Config');
    }

    /**
     * Gets the Module Config
     *
     * Reads the config for the requested module as default
     * or the config file specified by $filename.
     *
     * @param string $filename configuration ini-filename to read
     * @access public
     */
    public function getModuleConfig($filename = null)
    {
        if(is_null($filename))
        {
            return $this->config->readConfig(Clansuite_ModuleController_Resolver::getModuleName());
        }
        else
        {
            return $this->config->readConfig($filename);
        }
    }

    /**
     * Get the dependency injector
     *
     * @access public
     *
     * @return Returns a static reference to the Dependency Injector
     */
    public static function getInjector()
    {
        return self::$static_injector;
    }

    /**
     * Fire Action !
     *
     * @access public
     * @param string $requested_action the requested action as string
     */
    public function processActionController($request)
    {
        # get action parameter from URL
        $action = $request->getParameter('action');

        # get submodule parameter from URL
        $submodule = Clansuite_ModuleController_Resolver::getSubModuleName();

        # the pseudo-namesspace prefix 'action_' is used for all actions.
        # this is also a way to ensure some kind of whitelisting via namespacing.
        $methodname = 'action';

        /**
        * @desc Check for submodule
        */
        if(isset($submodule) && !empty($submodule))
        {
            $methodname .= '_' . $submodule;
        }

        /**
        * @desc Check for a action
        */
        if(isset($action) && !empty($action) && method_exists($this,$methodname . '_' . $action))
        {
            # set the used action name
            $this->action_name = $action;
            # set the method name
            $methodname .= '_' . $action;
        }
        else // action not set or method not existing
        {
            # set the used action name
            $this->action_name = $this->config['defaults']['default_action'];
            # set the method name
            $methodname .= '_' . $this->config['defaults']['default_action'];
        }

        #print $methodname;

        /**
         * Handle Method
         *
         * 1) check if method exists in module, then call it
         * 2) check if method exists in module/actions path, then call it
         * 3) if not found display error
         *
         */
        if(method_exists($this,$methodname))
        {
            # 1. call the method on module!
            $this->{$methodname}();
        }
        /**
        elseif
        {
            callFileActions
        }
        */
        else
        {
            # 3. error
            trigger_error('Action does not exist: ' . $methodname);
            exit();
        }
    }

    /**
     * Set view
     *
     * @access public
     *
     * @param object $view RenderEngine Object
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Get view
     *
     * @access public
     *
     * @return Returns the View Object (Rendering Engine)
     */
    public function getView()
    {
        # if already set, get the rendering engine from the view variable
        if (isset($this->view))
        {
            return $this->view;
        }
        # else, set the RenderEngine to the view variable and return it
        else
        {
            $this->view = $this->getRenderEngine();
            return $this->view;
        }
    }

    /**
     * sets the Rendering Engine
     *
     * @access public
     * @param string $renderEngineName Name of the RenderEngine
     */
    public function setRenderEngine($renderEngineName)
    {
        $this->renderEngineName = $renderEngineName;
    }

    /**
     * Returns the Name of the Rendering Engine.
     * Returns Smarty if no rendering engine is set
     *
     * @access public
     *
     * @return renderengine object, smarty as default
     */
    public function getRenderEngineName()
    {
        if(empty($this->renderEngineName))
        {
            $this->setRenderEngine('smarty');
        }
        return $this->renderEngineName;
    }

    /**
     * Returns the Rendering Engine Object via view_factory
     *
     * view_factory::getRenderer() has following parameters:
     * param1 getRenderEngineName looks up the Renderer-Name
     * param2 pass injector to renderer
     *
     * @access public
     * @return renderengine object
     */
    public function getRenderEngine()
    {
        return view_factory::getRenderer($this->getRenderEngineName(), $this->injector);
    }

    /**
     * Set the template name
     *
     * @access public
     * @param string $template Name of the Template with full Path
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Returns the Template Name
     *
     * @access public
     * @return Returns the templateName as String
     */
    public function getTemplateName()
    {
        # if the templateName was not set manually, we construct it from module/action infos
        if(empty($this->template))
        {
            $this->constructTemplateName();
        }
        return $this->template;
    }

    /**
     * constructTemplateName
     *
     * When this method is called, the templateName was not set manually!
     * We construct the template name with the informations we got about the module and action
     * and assign it via setTemplate!
     */
    private function constructTemplateName()
    {
        # get modulName,  actionName, subModuleName
        $moduleName = Clansuite_ModuleController_Resolver::getModuleName();
        #echo $moduleName;

        $moduleName = split('_', $moduleName);
        #$moduleName = explode("_", $moduleName);
        #echo 'ModuleName : '.$moduleName['0'].' - '.$moduleName['1'].'<br>';
        $moduleName = $moduleName[0];
        #echo 'ModuleName : '.$moduleName.'<br>';

        # @todo?
        #$actionName = Clansuite_ActionControllerResolver::getModuleAction();
        $actionName = $this->action_name;
        #echo 'ActionName : '.$actionName.'<br>';

        $subModuleName = Clansuite_ModuleController_Resolver::getSubModuleName();
        #echo 'SubModuleName : '.$subModuleName.'<br>';

        if( empty($this->template) )
        {
            if(strlen($subModuleName) > 0)
            {
                if(strlen($actionName) > 0 )
                {
                    # news/action_admin_show.tpl
                    $template = $moduleName.DS.'action_'.$subModuleName.'_'.$actionName.'.tpl';
                }
                else
                {
                    # news.tpl
                    $template = $moduleName.'.tpl';
                }
            }
            else
            {
                if(strlen($actionName) > 0)
                {
                    # news/action_show.tpl
                    $template = $moduleName.DS.'action_'.$actionName.'.tpl';
                }
                else
                {
                    # news.tpl
                    $template = $moduleName.'.tpl';
                }
            }
        }
        elseif( !strpos($this->template, DS) )
        {
            $template = $moduleName.DS.$this->template;
        }
        else
        {
            $template = $this->template;
        }
        #echo 'TPL Name : '.$template.'<br>';

        $this->setTemplate($template);
    }

    /**
     * Sets the Render Mode
     *
     * Available Modes: WRAPPED,...
     *
     * @param $mode RenderMode
     */
    public function setRenderMode($mode)
    {
        $this->getView()->renderMode = $mode;
    }

    /**
     * Get the Render Mode
     *
     */
    public function getRenderMode()
    {
        if(empty($this->getView()->renderMode))
        {
            $this->getView()->renderMode = 'WRAPPED';
        }
        return $this->getView()->renderMode;
    }

    /**
     * modulecontroller->renderWidget()
     *
     * Outputs the widget template of a module
     * 1. searches template in theme folder: news\widget_news.tpl
     * 2. searches template in module folder: news\template\widget_news.tpl
     *
     * @param $modulename Modulename of the widget to display
     */
    public function renderWidget(&$smarty)
    {
        $modulename = Clansuite_ModuleController_Resolver::getModuleName();

        # check for theme tpl / else take module tpl
        if($smarty->template_exists( $modulename.DS.'widget_'.$modulename.'.tpl'))
        {
            # Themefolder: news\widget_news.tpl
            echo $smarty->fetch( $modulename.DS.'widget_'.$modulename.'.tpl');
        }
        else
        {
            # Modulefolder: news\templates\widget_news.tpl
            echo $smarty->fetch($modulename.DS.'templates'.DS.'widget_'.$modulename.'.tpl');
        }
    }

    /**
     * modulecontroller->prepareOutput();
     *
     * All Output is done via the Response Object.
     * ModelData -> View -> Response Object
     *
     * 1. This method gets an instance of the Response Object first.
     * 2. Then gets an instance of the render engine.
     *    (if not already instantiated in the module,
     *     initializes proper viewfactory('smarty, json, rss'); as VIEW)
     * 3. getLayoutTemplate
     * 4. assign model data to that view object (a,b,c)
     * 5. set data to response object
     *
     * @access public
     */
    public function prepareOutput()
    {
        # 1) get the Response Object
        $response = $this->injector->instantiate('httpresponse');

        # 2) get the view
        $view = $this->getView();

        # 3) get the layout
        #$view->getLayoutTemplate();

        /**
         * 4+5) Set Content on the Response Object
         *
         * Content comes from:
         *
         * a) directly assigned output via string-variable $this->output
         * b) Render Engine -> method fetch() which returns a fetch template (without layout/mainframe)
         * c) Render Engine -> method render() which returns a complete layout (rendered mainframe)
         */
        # a)
        $response->setContent($this->output);
        # b)
        //$response->setContent($view->fetch($this->getTemplateName()));   # some fetched template
        # c)
        $response->setContent($view->render($this->getTemplateName()));
    }

    /**
     * controller_base::forward();
     * is as substitute for getAction
     *
     * This forwards from one controller function to
     * another function of the same controller
     * or to functions of an different controller.
     *
     * @access public
     */
    public function forward($functionName, $controllerName)
    {
        # forward to controller-name + controller-action

        # event log
    }

    /**
     * Redirect (shortcut for usage in modules)
     *
     * @param string Redirect to this URL
     * @param int    seconds before redirecting (for the html tag "meta refresh")
     * @param int    http status code, default: '302' => 'Not Found'
     * @access public
     */
    public function redirect($url, $time = 0, $statusCode = 302, $text = '')
    {
        $this->injector->instantiate('httpresponse')->redirect($url, $time, $statusCode, $text);
    }
}
?>
