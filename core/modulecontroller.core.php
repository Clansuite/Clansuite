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

# Security Handler
if(defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Interface for all modules
 *
 * Force classes implementing the interface to define this (must have) methods!
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Module
 */
interface Clansuite_Module_Interface
{
    # always needed is the main initializeModule() method
    function initializeModule(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response);
}

/**
 * ModuleController
 *
 * Is an abstract class (parent class) to share some common features for all (Module/Action)-Controllers.
 * You could call it ModuleController and ActionController.
 * It`s abstract because it should only be extended, not instantiated.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Modulecontroller
 */
abstract class Clansuite_Module_Controller extends Clansuite_Module_Controller_Resolver
{
    /**
     * @var object The rendering engine / view object
     */
    public $view = null;

    /**
     * @var string Name of the rendering engine
     */
    public $renderEngineName = null;

    /**
     * @var string The name of the template to render
     */
    public $template = null;

    /**
     * @var object Dependecy Injector (static)
     */
    public static $static_injector = null;

    /**
     * @var object The Configuration Object
     */
    public $config = null;

    /**
     * @var object The Http_Response Object
     */
    public $response = null;

    /**
     * @var array The Module Configuration Array
     */
    public $moduleconfig = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setInjector(Clansuite_CMS::getInjector());

        # fetch config from dependency injector
        $this->config = self::getInjector()->instantiate('Clansuite_Config');
    }

    /**
     * Initalize the records of the module
     *
     * @param $modulename Modulname
     * @param $recordname Recordname
     */
    public static function initModel($modulename = null, $recordname = null)
    {
        $models_path = '';

        /**
         * Load the Records for the current module, if no modulename is specified.
         * This is for lazy usage in the modulecontroller: $this->initModel();
         */
        if(is_null($modulename))
        {
            $modulename = Clansuite_Module_Controller_Resolver::getModuleName();
        }

        /**
         * If no recordname is given, the path to records stored in the modulefolder is set.
         */
        if(is_null($recordname))
        {
            $models_path = ROOT_MOD . strtolower($modulename) . DS . 'model/records';
        }
        /*else
        {
            /**
             * Modulename and Recordname differ! Like "modulemanager" asmodulename and "CsModules" = "modules" as recordname.
        *//*
            $models_path = ROOT_MOD . strtolower($modulename) . DS . 'model/records';
        }  */

        if( is_dir($models_path) )
        {
            Doctrine::loadModels($models_path);
        }
        # else Module has no Doctrine Records (Models)
    }

    /**
     * Set dependency injector (SetterInjection)
     * as a static var self::$injector
     * Type Hint set to only accept Phemto
     *
     * @param object $injector Dependency Injector (Phemto)
     * @todo move config injection somewhere else
     */
    public function setInjector(Phemto $injector)
    {
        self::$static_injector = $injector;
    }

    /**
     * Gets the Module Config
     *
     * Reads the config for the requested module as default
     * or the config file specified by $filename.
     *
     * @example
     * Its an replacement function for the following call in an action controller:
     * $this->moduleconfig = $this->config->readConfig( ROOT_MOD . 'mod/mod.config.php');
     * var_dump($this->moduleconfig);
     *
     * @param string $filename configuration ini-filename to read
     * @return array moduleconfig['modulename'] configuration array of module
     */
    public function getModuleConfig($filename = null)
    {
        return self::getInjector()->instantiate('Clansuite_Config')->readConfigForModule($filename);
    }

    /**
     * Returns the Clansuite Configuration as Array
     *
     * We are fetching the Clansuite Configuration Object from the injector.
     * So we have to determine first, if it is called from an static background
     * or if the injector is available as an object. For instance from inside a module widget,
     * the injector would only be available via static call.
     *
     * @return $array Clansuite Main Configuration (/configuration/clansuite.config.php)
     */
    public function getClansuiteConfig()
    {
        return self::getInjector()->instantiate('Clansuite_Config')->toArray();
    }

    /**
     * Gets a Config Value or sets a default value
     *
     * @example
     * Usage for one default variable:
     * $this->getConfigValue('items_newswidget', '8');
     * Gets the value for the key items_newswidget from the moduleconfig or sets the value to 8.
     *
     * Usage for two default variables:
     * $this->getConfigValue('items_newswidget', $_GET['numberNews'], '8');
     * Gets the value for the key items_newswidget from the moduleconfig or sets the value
     * incomming via GET, if nothing is incomming, sets the default value of 8.
     *
     * @param $keyname The keyname to find in the array.
     * @param $default_one A default value, which is returned, if the keyname was not found.
     * @param $default_two A default value, which is returned, if the keyname was not found and default_one is null.
     */
    public function getConfigValue($keyname, $default_one = null, $default_two = null)
    {
        # if we don't have a moduleconfig array yet, get it
        if($this->moduleconfig == null)
        {
            $this->moduleconfig = $this->getModuleConfig();
        }

        # try a lookup of the value by keyname
        $value = Clansuite_Functions::array_find_element_by_key($keyname, $this->moduleconfig);

        # return value or default
        if(empty($value) == false)
        {
            return $value;
        }
        elseif( $default_one != null )
        {
            return $default_one;
        }
        elseif( $default_two != null )
        {
            return $default_two;
        }
        else
        {
            return null;
        }
    }

    /**
     * Get the dependency injector
     *
     * @return Returns a static reference to the Dependency Injector
     */
    public static function getInjector()
    {
        return self::$static_injector;
    }

    /**
     * Set view
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
     * @param string $renderEngineName Name of the RenderEngine
     */
    public function setRenderEngine($renderEngineName)
    {
        $this->renderEngineName = $renderEngineName;
    }

    /**
     * Returns the Name of the Rendering Engine.
     * Returns Json if an XMLHttpRequest is given.
     * Returns Smarty as default if no rendering engine is set.
     *
     * @return renderengine object, smarty as default
     */
    public function getRenderEngineName()
    {
        # check if the requesttype is xmlhttprequest (ajax) is incomming, then we will return data in json format
        if(self::getHttpRequest()->isxhr() === true)
        {
            $this->setRenderEngine('json');
        }

        # use smarty as default, if renderEngine is not set and it's not an ajax request
        if(empty($this->renderEngineName))
        {
            $this->setRenderEngine('smarty');
        }

        return $this->renderEngineName;
    }

    /**
     * Returns the Rendering Engine Object via view_factory
     *
     * @return renderengine object
     */
    public function getRenderEngine()
    {
        return Clansuite_Renderer_Factory::getRenderer($this->getRenderEngineName(), self::getInjector());
    }

    /**
     * Set the template name
     *
     * @param string $template Name of the Template with full Path
     */
    public function setTemplate($template)
    {
        #self::checkTemplateExtension($template);
        $this->template = $template;
    }

    public static function checkTemplateExtension($template)
    {
        # get extension of template
        $template_extension = strtolower(pathinfo($template, PATHINFO_EXTENSION));

        # whitelist definition for listing all allowed template filetypes
        $allowed_extensions = array('html','php','tpl');

        # check if extension is one of the allowed ones
        if (false == in_array($template_extension, $allowed_extensions))
        {
            trigger_error('Template Extension invalid <strong>'.$template_extension.'</strong> on <strong>'.$template.'</strong>',
                    E_USER_NOTICE);
        }
    }

    /**
     * Returns the Template Name
     *
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
     * When this method is called, the templateName was not set manually.
     * We construct the template name with the informations we got about the module and action and assign it via setTemplate.
     */
    private function constructTemplateName()
    {
        $template = Clansuite_Action_Controller_Resolver::getActionName() . '.tpl';
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
     */
    public function prepareOutput()
    {
        # 1) get the Response Object
        $response = self::getInjector()->instantiate('Clansuite_HttpResponse');

        # 2) get the view
        $view = $this->getView();


        # 3) get the layout (like admin/index.tpl)
        #echo 'Layout/Wrapper Template: ' . $view->getLayoutTemplate() . '<br />';

        # Debug
        #echo 'Template Name: ' .$this->getTemplateName() . '<br />';

        /**
         * 4+5) Set Content on the Response Object
         *
         * Content comes from:
         *
         * a) directly assigned output via string-variable $this->output
         * b) Render Engine -> method fetch() which returns a fetch template (without layout/mainframe)
         * c) Render Engine -> method render() which returns a complete layout (rendered mainframe)
         */
        # a)$response->setContent($this->output);

        #var_dump( $view->render($this->getTemplateName()) );

        # b)
        # $response->setContent($view->fetch($this->getTemplateName()));
        # c)
        $response->setContent($view->render($this->getTemplateName()));
    }

    /**
     * ModuleController->addError();
     * is a call for errorhandler::addError
     *
     * This passes the errormessage and errorcode to the errorhandler.
     */
    public function addError($errormessage, $errorcode)
    {
        # pass variables to errorhandler
        Clansuite_Errorhandler::addError($errormessage, $errorcode);
    }

    /**
     * ModuleController->forward();
     * is as substitute for getAction
     *
     * This forwards from one controller function to
     * another function of the same controller
     * or to functions of an different controller.
     *
     */
    public function forward($class, $method, array $arguments = array())
    {
        # forward another controller-name + controller-action
        Clansuite_Loader::callMethod($class, $method, $arguments);
    }

    /**
     * Redirect (shortcut for usage in modules)
     *
     * @param string Redirect to this URL
     * @param int    seconds before redirecting (for the html tag "meta refresh")
     * @param int    http status code, default: '302' => 'Not Found'
     * @param string redirect text
     */
    public function redirect($url, $time = 0, $statusCode = 302, $text = '')
    {
        self::getHttpResponse()->redirect($url, $time, $statusCode, $text);
    }

    /**
     * Redirect to Referer
     */
    public function redirectToReferer()
    {
        $referer = '';
        $referer = self::getHttpRequest()->getReferer();

        if(empty($referer) == false)
        {
            $this->redirect($referer);
        }
        else
        {
            $this->redirect( WWW_ROOT . Clansuite_Module_Controller_Resolver::getModuleName() );
        }
    }

    /**
     * Shortcut for Redirect with an 404 Response Code
     *
     * @param string Redirect to this URL
     * @param int    seconds before redirecting (for the html tag "meta refresh")
     */
    public function redirect404($url, $time = 5)
    {
        $this->redirect($url, $time, 404, _('The URL you requested is not available.'));
    }

    /**
     * addEvent (shortcut for usage in modules)
     *
     * @param string Name of the Event
     * @param object Eventobject
     */
    public function addEvent($eventName, Clansuite_Event $event)
    {
        Clansuite_Eventdispatcher::instantiate()->addEventHandler($eventName, $event);
    }

    /**
     * triggerEvent is shortcut/convenience method for Clansuite_Eventdispatcher->triggerEvent
     *
     * @param $event mixed (string|object) Name of Event or Event object to trigger.
     * @param $context object Context of the event triggering, often the object from where we are calling ($this). Default Null.
     * @param $info string Some pieces of information. Default Null.
     */
    public function triggerEvent($event, $context = null, $info = null)
    {
        Clansuite_Eventdispatcher::instantiate()->triggerEvent($event, $context = null, $info = null);
    }

    /**
     * Shortcut to set a Flashmessage
     *
     * @see Clansuite_Flashmessages::setMessage()
     * @param $type string error, warning, notice, success, debug
     * @param $message string A textmessage.
     */
    public function setFlashmessage($type, $message)
    {
        Clansuite_Flashmessages::setMessage($type, $message);
    }

    /**
     * Shortcut to get the HttpRequest Object
     *
     * @return HttpRequest Object
     */
    public function getHttpRequest()
    {
        return self::getInjector()->instantiate('Clansuite_HttpRequest');
    }

    /**
     * Shortcut to get the HttpResponse Object
     *
     * @return HttpResponse Object
     */
    public function getHttpResponse()
    {
        return self::getInjector()->instantiate('Clansuite_HttpResponse');
    }
}
?>