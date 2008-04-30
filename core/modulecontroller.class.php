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

    // note by vain: check if these are still needed -> from v0.1 now deprecated?
    // positional check: this is view-related!
    protected $additional_head  = null;
    protected $suppress_wrapper = null;

    // Variable contains the rendering engine (view object)
    public $view = null;

    // Variable contains the name of the rendering engine
    public $renderEngineName = null;

    // Variable contains the name of the template
    public $templateName = null;

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
     */
    public function setInjector(Phemto $injector)
    {
        # set Injector (dynamic)
    	$this->injector = $injector;
    	# set Injector (static)
    	self::$static_injector = $injector;

    	# fetch config from dependency injector
    	$this->config = $this->injector->instantiate('configuration');
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
        $submodule = $request->getParameter('sub');

        # the pseudo-namesspace prefix 'action_' is used for all actions.
        # this is also a way to ensure some kind of whitelisting via namespacing.
        $methodname = 'action_';

        # check if action (a) set and (b) not empty and (c) check if the action_$actionxxx
        # method exists in the main module class (the one extending this class)
        if(isset($action) && !empty($action))
        {
            # if submodule is found, combine it with action: sub+action = admin_create
            if(isset($submodule) && !empty($submodule))
            {
                # add actionname to the methodname: action_"show"
                $methodname .= $submodule . '_'. $action;
                #echo '1 Submodule was set: '. $methodname;
            }
            else
            {
                $methodname .= $action;
                #echo '2 No Submodule was set. '. $methodname;
            }

            if(method_exists($this,$methodname))
            {
                #echo " Method called : $methodname";
                # set the used action name
                $this->action_name = $action;
                # call the method !
                $this->{$methodname}();
            }
             # check if the method exists as a drop in action in the module directory
            /*elseif
            {
                if is_file() ....

            }*/
            else
            {
                #echo ' Not existing in Class: '. get_class($this) .' - Methodname: '.$methodname;
            }
        }
        # check if the method exists as a drop in action in the module directory
        /*elseif
        {
            if is_file() ....

        }*/
        else
        {
            # if submodule is found, combine it with action: sub+action = admin_create
            if(isset($submodule) && !empty($submodule))
            {
                # add actionname to the methodname: action_"show"
                $methodname .= $submodule . '_'. $action;
                #echo '1 Submodule was set: '. $methodname;
            }
            else
            {
                $methodname .= $action;
                #echo '2 No Submodule was set. '. $methodname;
            }

            #echo Clansuite_ModuleController_Resolver::getModuleName();
            #echo Clansuite_ModuleController_Resolver::getSubModuleName();
            # set the used action name
            $this->action_name = $this->config['default_action'];
            # set the method name
            $methodname .= $this->config['default_action'];
            #echo " No Action was set in the URL, using DefaultAction. Method called : $methodname";
            # call the method !
            $this->{$methodname}();
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
     * Returns the Rendering Engine Object
     * param1 getRenderEngineName looks up the Renderer-Name
     * param2 pass injector to renderer
     *
     * @access public
     * @return renderengine object
     */
    public function getRenderEngine()
    {
        return view_factory::getRenderer($this->getRenderEngineName(), $this->getInjector);
    }

    /**
     * Set the template name
     *
     * @access public
     * @param string $templateName Name of the Template with full Path
     */
    public function setTemplate($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Returns the Template Name
     *
     * @access public
     * @return Returns the templateName as String
     */
    public function getTemplateName()
    {
        # if the templateName was not set, we try to autodetect it
        # by searching through several paths to find the tpl
        if(empty($this->templateName))
        {
            # get modulName and actionName
            $moduleName = Clansuite_ModuleController_Resolver::getModuleName();

            $moduleName = explode("_", $moduleName);
            #echo 'ModuleName : '.$moduleName['0'].' - '.$moduleName['1'].'<br>';

            # @todo?
            #$actionName = Clansuite_ActionControllerResolver::getModuleAction();
            $actionName = $this->action_name;
            #echo 'ActionName : '.$actionName.'<br>';

            $SubModuleName = Clansuite_ModuleController_Resolver::getSubModuleName();
            #echo 'SubModuleName : '.$SubModuleName.'<br>';

            if(strlen($SubModuleName) > 0)
            {
                $tplname = $moduleName['0'].'/'.$SubModuleName.'_'.$actionName.'.tpl';
                #echo 'TPL Name : '.$tplname.'<br>';
            }
            else
            {
                # construct a partial path from moduleName and actionName
                $tplname = $moduleName['0'].'/'.$actionName.'.tpl';
                #echo 'TPL Name : '.$tplname.'<br>';
            }

            # check, if a template-file with $tplname exists in
            # 1. Check, if template exists in current THEME/templates
            # 2. Check, if template exists in standard theme
            # 3. Check, if template exists in module folder / templates
            # 4. Not Existant

            # 1. modules/modulename/templates/actioname.tpl
            # @todo: for renderer related templates we have to add "renderer/", like
            # modules/modulename/templates/renderer/actioname.tpl

            if(is_file( ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $tplname) && isset($_SESSION['user']['theme']) > 0)
            {                
                # 1. Check, if template exists in current THEME/templates
                $this->setTemplate( ROOT_THEMES .'/'. $_SESSION['user']['theme'] .'/'. $tplname);
            }
            /*elseif(is_file( ROOT_THEMES . '/standard/' . $tplname))
            {
                # 3. Check, if template exists in standard theme
                $this->setTemplate( ROOT_THEMES . '/standard/' . $tplname );
            }*/
            elseif(is_file( ROOT_MOD .'/'. $moduleName .'/templates/'. $actionName .'.tpl'))
            {
                # 2. Check, if template exists in module folder / templates
                $this->setTemplate( ROOT_MOD .'/'. $moduleName .'/templates/'. $actionName .'.tpl');
            }         
            else
            {
                # 4. NOT EXISTANT
                $this->setTemplate( ROOT_THEMES . '/core/tplnotfound.tpl' );
            }
        }
        # templateName was set
        else
        {
            # check if is_file

            # walk through smarty paths
            # @todo: wrong position -> move this to view
        }
        return $this->templateName;
    }

    /**
     * controller_base::prepareRendering();
     * returns an instance of the render engine object and prepares it for rendering the output
     *
     * 1. initialize proper viewfactory('smarty, json, rss'); as VIEW
     * 2. assign model data to that view object
     * 3. set data to response object
     *
     * @access public
     */
    public function prepareOutput()
    {
        # get Response Object
        $response = $this->injector->instantiate('httpresponse');

        # get the view
        $view = $this->getView();

        # get the layout
        $view->getLayoutTemplate();

        # set Output of the RenderEngine to the Response Object
        $response->setContent($this->output);   # output directly
        #$response->setContent($view->fetch($this->getTemplateName()));   # some fetched template
        $response->setContent($view->render($this->getTemplateName())); # complete layout / rendered mainframe

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
     * controller_base::redirect();
     *
     *
     * @access public
     */
    public function redirect()
    {
        # redirect to ...

        # event log

    }
}
?>