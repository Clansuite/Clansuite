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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
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
abstract class Clansuite_Module_Controller
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
     * @var object The Http_Response Object
     */
    public $response = null;

    /**
     * @var object The Http_Request Object
     */
    public $request = null;

    /**
     * @var object \Doctrine\ORM\EntityManager
     */
    public $doctrine_em = null;

    /**
     * @var array The Module Configuration Array
     */
    public static $moduleconfig = null;

    public function __construct(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->doctrine_em = Clansuite_CMS::getEntityManager();
    }

    /**
     * The name of the entity extracted from the classname
     *
     * @todo isn't this the pure "mod" name?
     *
     * @param string Classname
     * @return string The name of the entity extracted from classname
     */
    public function getEntityNameFromClassname()
    {
        $entityNameArray = explode('_', get_called_class());

        # add entities namespace prefix
        $this->entityName = 'Entities\\' . $entityNameArray[2];

        return $this->entityName;
    }

    /**
     * Proxy/Convenience Getter Method for the current Repository
     *
     * @param string $entityName
     * @return object Doctrine Repository
     */
    public function getModel($entityName = null)
    {
        if(null === $entityName)
        {
            $entityName = $this->getEntityNameFromClassname();
        }
        #Clansuite_Debug::firebug($entityName);
        return $this->doctrine_em->getRepository($entityName);
    }

    /**
     * Initalize the records of the module
     *
     * @param $modulename Modulname
     * @param $recordname Recordname
     */
    public static function initModel($modulename = null)
    {        
        #$module_models_path = '';

        /**
         * Load the Records for the current module, if no modulename is specified.
         * This is for lazy usage in the modulecontroller: $this->initModel();
         */
        #if($modulename === null)
        #{
        #    $modulename = Clansuite_HttpRequest::getRoute()->getModuleName();
        #}

        #$module_models_path = realpath(ROOT_MOD . mb_strtolower($modulename) . DS . 'model') . DS;

        #if(is_dir($module_models_path))
        #{
            #set_include_path($module_models_path . PS . get_include_path());
            #Clansuite_Debug::firebug($module_models_path);

          # require $models_path . 'entities' . DS . ucfirst($modulename) . '.php';
          # require $models_path . 'repositories' . DS . ucfirst($modulename) . '.php';          
        #}
        # else Module has no Model Data
    }

    /**
     * Gets a Module Config
     *
     * @param string $modulename Modulename.
     * @return array configuration array of module
     */
    public static function getModuleConfig($modulename = null)
    {
        $config = self::getInjector()->instantiate('Clansuite_Config');
        return self::$moduleconfig = $config->readModuleConfig($modulename);
    }

    /**
     * Proxy/convenience method: returns the Clansuite Configuration as array
     *
     * @return $array Clansuite Main Configuration (/configuration/clansuite.config.php)
     */
    public function getClansuiteConfig()
    {
        return $this->config = Clansuite_CMS::getClansuiteConfig();
    }

    /**
     * Gets a Config Value or sets a default value
     *
     * @example
     * Usage for one default variable:
     * self::getConfigValue('items_newswidget', '8');
     * Gets the value for the key items_newswidget from the moduleconfig or sets the value to 8.
     *
     * Usage for two default variables:
     * self::getConfigValue('items_newswidget', $_GET['numberNews'], '8');
     * Gets the value for the key items_newswidget from the moduleconfig or sets the value
     * incomming via GET, if nothing is incomming, sets the default value of 8.
     *
     * @param string $keyname The keyname to find in the array.
     * @param mixed $default_one A default value, which is returned, if the keyname was not found.
     * @param mixed $default_two A default value, which is returned, if the keyname was not found and default_one is null.
     */
    public static function getConfigValue($keyname, $default_one = null, $default_two = null)
    {
        # if we don't have a moduleconfig array yet, get it
        if(self::$moduleconfig === null)
        {
            self::$moduleconfig = self::getModuleConfig();
        }

        # try a lookup of the value by keyname
        $value = Clansuite_Functions::array_find_element_by_key($keyname, self::$moduleconfig);

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
        return Clansuite_CMS::getInjector();
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
    public function getView($renderEngineName = null)
    {
        # set the renderengine name
        if(isset($renderEngineName))
        {
            $this->setRenderEngine($renderEngineName);
        }

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
        Clansuite_HttpRequest::getRoute()->setRenderEngine($renderEngineName);
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
        if(self::getHttpRequest()->isAjax() === true)
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

    /**
     * Ensures the template extension is correct.
     *
     * @param string $template The template filename.
     */
    public static function checkTemplateExtension($template)
    {
        # get extension of template
        $template_extension = mb_strtolower(pathinfo($template, PATHINFO_EXTENSION));

        # whitelist definition for listing all allowed template filetypes
        $allowed_extensions = array('html','php','tpl');

        # check if extension is one of the allowed ones
        if (false == in_array($template_extension, $allowed_extensions))
        {
            $message = 'Template Extension invalid <strong>'.$template_extension.'</strong> on <strong>'.$template.'</strong>';
            trigger_error($message, E_USER_NOTICE);
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
        $template = Clansuite_TargetRoute::getActionName() . '.tpl';
        $this->setTemplate($template);
    }

    /**
     * Sets the Render Mode
     *
     * @param string $mode The RenderModes are LAYOUT or NOLAYOUT.
     */
    public function setRenderMode($mode)
    {
        $this->getView()->renderMode = $mode;
    }

    /**
     * Get the Render Mode
     */
    public function getRenderMode()
    {
        if(empty($this->getView()->renderMode))
        {
            $this->getView()->renderMode = 'LAYOUT';
        }
        return $this->getView()->renderMode;
    }

    /**
     * modulecontroller->display();
     *
     * All Output is done via the Response Object.
     * ModelData -> View -> Response Object
     *
     * 1. getTemplateName() - get the template to render.
     * 2. getView() - gets an instance of the render engine.
     * 3. assign model data to that view object (a,b,c)
     * 5. set data to response object
     *
     * @param $templates array with keys 'layout_template' / 'content_template' and templates as values
     */
    public function display(array $templates = null)
    {
        # set layout and content template by parameter array
        if(is_array($templates))
        {
            if(isset($templates['layout_template']))
            {
                $this->setLayoutTemplate($templates['layout_template']);
            }

            if(isset($templates['content_template']))
            {
                $this->setTemplate($templates['content_template']);
            }
        }


        # get the templatename
        $templatename = $this->getTemplateName();

        # get the view
        $this->view = $this->getView();

        # Debug display of Layout Template and Content Template
        if(DEBUG == true)
        {
            #Clansuite_Debug::firebug('Layout/Wrapper Template: ' . $this->view->getLayoutTemplate() . '<br />');
            #Clansuite_Debug::firebug('Template Name: ' . $templatename . '<br />');
        }

        # render the content / template
        $content = $this->view->render($templatename);

        # push content to the response object
        $this->response->setContent($content);

        unset($content, $templatename);
    }

    /**
     * This loads and initializes a formular from the module directory.
     *
     * @param string $formname The name of the formular.
     * @param string $controller The name of the module.
     * @param string $module The name of the action.
     * @param boolean $assign_to_view If true, the form is directly assigned as formname to the view
     */
    public function loadForm($formname = null, $module = null, $action = null, $assign_to_view = true)
    {
        if(null === $module)
        {
            $module = Clansuite_HttpRequest::getRoute()->getModuleName();
         }

        if(null === $action)
        {
            $action = Clansuite_HttpRequest::getRoute()->getActionName();
        }

        if(null === $formname)
        {
            # construct formname like "news"_"action_admin_show"
            $formname  = ucfirst($module) . '_' . ucfirst($action);
        }

        # construct formname, classname, filename, load file, instantiate the form
        $classname = 'Clansuite_Form_' . $formname;
        $filename  = mb_strtolower($formname) . '.form.php';
        $directory = ROOT_MOD . mb_strtolower($module) . DS . 'form/';
        Clansuite_Loader::requireFile( $directory . $filename, $classname );

        # form preparation stage (combine description and add additional formelements)
        $form = new $classname;

        # assign form object directly to the view or return to work with it
        if($assign_to_view === true)
        {
            $this->getView()->assign('form', $form->render());
        }
        else
        {
            return $form;
        }
    }

    /**
     * Redirect (shortcut for usage in modules)
     *
     * @param string $url Redirect to this URL
     * @param int    $time seconds before redirecting (for the html tag "meta refresh")
     * @param int    $statusCode http status code, default: '302' => 'Found'
     * @param string $message redirect text
     */
    public function redirect($url, $time = 0, $statusCode = 302, $message = '')
    {
        self::getHttpResponse()->redirect($url, $time, $statusCode, $message);
    }

    /**
     * Redirect to Referer
     */
    public function redirectToReferer()
    {
        $referer = '';
        $referer = self::getHttpRequest()->getReferer();

        # we have a referer in the environment
        if(empty($referer) === false)
        {
            $this->redirect(SERVER_URL . $referer);
        }
        else # build referer on base of the current module
        {
            $route = Clansuite_HttpRequest::getRoute();

            $redirect_to = '/';
            $redirect_to .= $route->getModuleName();

            $submodule = $route->getSubModuleName();
            if(empty($submodule) === false)
            {
                $redirect_to .= '/'. $submodule;
            }

            $this->redirect($redirect_to);
        }
    }

    /**
     * Shortcut for Redirect with an 404 Response Code
     *
     * @param string $url Redirect to this URL
     * @param int    $time seconds before redirecting (for the html tag "meta refresh")
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
     * @param mixed (string|object) $event Name of Event or Event object to trigger.
     * @param object $context Context of the event triggering, often the object from where we are calling ($this). Default Null.
     * @param string $info Some pieces of information. Default Null.
     */
    public function triggerEvent($event, $context = null, $info = null)
    {
        Clansuite_Eventdispatcher::instantiate()->triggerEvent($event, $context = null, $info = null);
    }

    /**
     * Shortcut to set a Flashmessage
     *
     * @see Clansuite_Flashmessages::setMessage()
     * @param string $type string error, warning, notice, success, debug
     * @param string $message string A textmessage.
     */
    public static function setFlashmessage($type, $message)
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
        return $this->request;
    }

    /**
     * Shortcut to get the HttpResponse Object
     *
     * @return HttpResponse Object
     */
    public function getHttpResponse()
    {
        return $this->response;
    }
}
?>