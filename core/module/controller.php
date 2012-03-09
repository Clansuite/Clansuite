<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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

namespace Koch\Module;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * ModuleController
 *
 * Is an abstract class (parent class) to share some common features for all (Module/Action)-Controllers.
 * You could call it ModuleController and ActionController.
 * It`s abstract because it should only be extended, not instantiated.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Modulecontroller
 */
abstract class Controller
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
     * @var Koch_HttpResponse \Koch\Core\HttpResponse
     */
    public $response = null;

    /**
     * @var Koch_HttpRequest \Koch\Core\HttpRequest
     */
    public $request = null;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $doctrine_em = null;

    /**
     * @var array The Module Configuration Array
     */
    public static $moduleconfig = null;

    public function __construct(Koch_HttpRequest $request, Koch_HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->doctrine_em = Clansuite_CMS::getEntityManager();
    }

    /**
     * Returns the Doctrine Entity Manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getDoctrineEntityManager()
    {
        return $this->doctrine_em;
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
     * Proxy/Convenience Getter Method for the Repository of the current Module.
     *
     *
     * @param string $entityName Name of an Entity, like "\Entities\User".
     * @return Doctrine\ORM\EntityRepository
     */
    public function getModel($entityName = null)
    {
        if(null === $entityName)
        {
            $entityName = $this->getEntityNameFromClassname();
        }

        return $this->doctrine_em->getRepository($entityName);
    }

    /**
     * Saves this and all others models (calls persist + flush)
     * Save (save one)
     * Flush (save all)
     *
     * @param object  $model Entity.
     * @param boolean $flush Uses flush on true, save on false. Defaults to flush (true).
     */
    public function saveModel(\Doctrine\ORM\Mapping\Entity $model, $flush = true)
    {
        $this->doctrine_em->persist($model);

        if($flush === true)
        {
            $this->doctrine_em->flush();
        }
        else
        {
            $this->doctrine_em->save();
        }
    }

    /**
     * Initializes the model (active records/entities/repositories) of the module
     *
     * @param $modulename Modulname
     * @param $recordname Recordname
     */
    public static function setModel($modulename = null, $entity = null)
    {
        $module_models_path = '';

        /**
         * Load the Records for the current module, if no modulename is specified.
         * This is for lazy usage in the modulecontroller: $this->initModel();
         */
        if($modulename === null)
        {
            $modulename = Koch_HttpRequest::getRoute()->getModuleName();
        }

        $module_models_path = ROOT_MOD . mb_strtolower($modulename) . DS . 'model' . DS;

        # check if the module has a models dir
        if(is_dir($module_models_path) === true)
        {
           if(isset($entity) === true)
           {
               # use second parameter of method
               $entity = $module_models_path . 'entities' . DS . ucfirst($entity) . '.php';
           }
           else
           {
               # build entity filename by modulename
               $entity = $module_models_path . 'entities' . DS . ucfirst($modulename) . '.php';
           }

           if(is_file($entity) === true and class_exists('Entities\\' . ucfirst($modulename), false) === false)
           {
               include $entity;
           }

           $repos = $module_models_path . 'repositories' . DS . ucfirst($modulename) . 'Repository.php';

           if(is_file($repos) === true and class_exists('Entities\\' . ucfirst($modulename), false) === false)
           {
               include $repos;
           }
        }
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
        $config = self::getInjector()->instantiate('Koch_Config');

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
     * @param mixed $default_one A default value returned, when keyname was not found.
     * @param mixed $default_two A default value returned, when keyname was not found and default_one is null.
     * @return mixed
     */
    public static function getConfigValue($keyname, $default_one = null, $default_two = null)
    {
        # if we don't have a moduleconfig array yet, get it
        if(self::$moduleconfig === null)
        {
            self::$moduleconfig = self::getModuleConfig();
        }

        # try a lookup of the value by keyname
        $value = Koch_Functions::array_find_element_by_key($keyname, self::$moduleconfig);

        # return value or default
        if(empty($value) === false)
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
     * Get view returns the render engine
     *
     * @param string $renderEngineName Name of the render engine, like smarty, phptal.
     * @return Returns the View Object (Rendering Engine)
     */
    public function getView($renderEngineName = null)
    {
        # set the renderengine name
        if(isset($renderEngineName) === true)
        {
            $this->setRenderEngine($renderEngineName);
        }

        # if already set, get the rendering engine from the view variable
        if(isset($this->view) === true)
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
        Koch_HttpRequest::getRoute()->setRenderEngine($renderEngineName);
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
        if(empty($this->renderEngineName) === true)
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
        return Koch_Renderer_Factory::getRenderer($this->getRenderEngineName(), self::getInjector());
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
     *
     * @return string LAYOUT|NOLAYOUT
     */
    public function getRenderMode()
    {
        if(empty($this->getView()->renderMode) === true)
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
     * @param $templates mixed|array|string Array with keys 'layout_template' / 'content_template' and templates as values or just content template name.
     */
    public function display($templates = null)
    {
        # get the view
        $this->view = $this->getView();

        # get the view mapper
        $view_mapper = $this->view->getViewMapper();

        # set layout and content template by parameter array
        if(is_array($templates) === true)
        {
            if(isset($templates['layout_template']) === true)
            {
                $view_mapper->setLayoutTemplate($templates['layout_template']);
            }

            if(isset($templates['content_template']) === true)
            {
                $view_mapper->setTemplate($templates['content_template']);
            }
        }

        # only the "content template" is set
        if(is_string($templates)) { $view_mapper->setTemplate($templates); }

        # get the templatename
        $template = $view_mapper->getTemplateName();

        # Debug display of Layout Template and Content Template
        #Koch_Debug::firebug('Layout/Wrapper Template: ' . $this->view->getLayoutTemplate() . '<br />');
        #Koch_Debug::firebug('Template Name: ' . $templatename . '<br />');

        # render the content / template
        $content = $this->view->render($template);

        # push content to the response object
        $this->response->setContent($content);

        unset($content, $template);
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
            $module = Koch_HttpRequest::getRoute()->getModuleName();
         }

        if(null === $action)
        {
            $action = Koch_HttpRequest::getRoute()->getActionName();
        }

        if(null === $formname)
        {
            # construct formname like "news"_"action_admin_show"
            $formname  = ucfirst($module) . '_' . ucfirst($action);
        }

        # construct formname, classname, filename, load file, instantiate the form
        $classname = 'Koch_Form_' . $formname;
        $filename  = mb_strtolower($formname) . '.form.php';
        $directory = ROOT_MOD . mb_strtolower($module) . DS . 'form/';
        Koch_Loader::requireFile( $directory . $filename, $classname );

        # form preparation stage (combine description and add additional formelements)
        $form = new $classname;

        # assign form object directly to the view or return to work with it
        if($assign_to_view === true)
        {
            # do not call $form->render(), it's already done
            $this->getView()->assign('form', $form);
        }
        else
        {
            return $form;
        }
    }

    /**
     * Redirect to Referer
     */
    public function redirectToReferer()
    {
        $referer = self::getHttpRequest()->getReferer();

        # we have a referer in the environment
        if(empty($referer) === false)
        {
            $this->redirect(SERVER_URL . $referer);
        }
        else # build referer on base of the current module
        {
            $route = Koch_HttpRequest::getRoute();

            # we use internal rewrite style here: /module/action
            $redirect_to = '/' . $route->getModuleName();
            $submodule = $route->getSubModuleName();

            if(empty($submodule) === false)
            {
                $redirect_to .= '/'. $submodule;
            }

            # redirect() builds the url
            $this->getHttpResponse()->redirect($redirect_to);
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
        $this->getHttpResponse()->redirect($url, $time, 404, _('The URL you requested is not available.'));
    }

    /**
     * Redirects to a new URL.
     * It's a proxy method using the HttpResponse Object.
     *
     * @param string $url Redirect to this URL.
     * @param int $time Seconds before redirecting (for the html tag "meta refresh")
     * @param int $statusCode Http status code, default: '303' => 'See other'
     * @param string $message Text of redirect message.
     * @param string $mode The redirect mode: LOCATION, REFRESH, JS, HTML.
     */
    public function redirect($url, $time = 0, $statusCode = 303, $message = null, $mode = null)
    {
        $this->getHttpResponse()->redirect($url, $time, $statusCode, $message, $mode);
    }

    /**
     * addEvent (shortcut for usage in modules)
     *
     * @param string Name of the Event
     * @param object Eventobject
     */
    public function addEvent($eventName, Koch_Event $event)
    {
        Koch_Eventdispatcher::instantiate()->addEventHandler($eventName, $event);
    }

    /**
     * triggerEvent is shortcut/convenience method for Koch_Eventdispatcher->triggerEvent
     *
     * @param mixed (string|object) $event Name of Event or Event object to trigger.
     * @param object $context Context of the event triggering, often the object from where we are calling ($this). Default Null.
     * @param string $info Some pieces of information. Default Null.
     */
    public function triggerEvent($event, $context = null, $info = null)
    {
        Koch_Eventdispatcher::instantiate()->triggerEvent($event, $context = null, $info = null);
    }

    /**
     * Shortcut to set a Flashmessage
     *
     * @see Koch_Flashmessages::setMessage()
     * @param string $type string error, warning, notice, success, debug
     * @param string $message string A textmessage.
     */
    public static function setFlashmessage($type, $message)
    {
        Koch_Flashmessages::setMessage($type, $message);
    }

    /**
     * Shortcut to get the HttpRequest Object
     *
     * @return \Koch\Core\HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->request;
    }

    /**
     * Shortcut to get the HttpResponse Object
     *
     * @return \Koch\Core\HttpResponse
     */
    public function getHttpResponse()
    {
        /* @var \Koch\Core\HttpResponse */
        return $this->response;
    }
}


/**
 * Interface for all modules which implement a specific action structure.
 * Inspired by Sinatra.
 *
 * Force classes implementing the interface to define this (must have) methods!
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Module
 */
interface Koch_Module_Interface
{
    public function action_list();     # GET     /foos
    public function action_show();     # GET     /foos/:foo_id
    public function action_new();      # GET     /foos/new
    public function action_edit();     # GET     /foos/:foo_id/edit
    public function action_create();   # POST    /foos
    public function action_update();   # PUT     /foos/:foo_id
    public function action_destroy();  # DELETE  /foos/:foo_id
}

interface Koch_AdminModule_Interface
{
    public function action_admin_list();     # GET     /foos
    public function action_admin_show();     # GET     /foos/:foo_id
    public function action_admin_new();      # GET     /foos/new
    public function action_admin_edit();     # GET     /foos/:foo_id/edit
    public function action_admin_insert();   # POST    /foos
    public function action_admin_update();   # PUT     /foos/:foo_id
    public function action_admin_delete();   # DELETE  /foos/:foo_id
}
?>