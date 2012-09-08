<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Router;

use Koch\Mvc\Mapper;
use Koch\Http\HttpRequest;

/**
 * Router_TargetRoute (processed RequestObject)
 */
class TargetRoute extends Mapper
{
    public static $parameters = array(
        // File
        'filename'      => null,
        'classname'     => null,
        // Call
        'module'        => 'index',
        'controller'    => 'index',
        'action'        => 'index',
        'method'        => null,
        'params'        => null,
        // Output
        'format'        => 'html',
        'language'      => 'en',
        'request'       => 'get',
        'layout'        => true,
        'ajax'          => false,
        'renderer'      => 'smarty',
        'themename'     => null,
        'modrewrite'    => false
    );

    /**
     * TargetRoute is a Singleton
     *
     * @return instance of Koch_TargetRoute class
     */
    public static function getInstance()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new TargetRoute();
        }

        return $instance;
    }

    public static function setFilename($filename)
    {
        self::$parameters['filename'] = $filename;
    }

    public static function getFilename()
    {
        if (empty(self::$parameters['filename'])) {
            $filename = self::mapControllerToFilename(
                self::getModulePath(self::getModule()), 
                self::getController()
            );
            self::setFilename($filename);
        }

        return self::$parameters['filename'];
    }

    public static function setClassname($classname)
    {
        self::$parameters['classname'] = $classname;
    }

    public static function getClassname()
    {
        if (empty(self::$parameters['classname'])) {
            $classname = self::mapControllerToClassname(self::getModule(), self::getController());

            self::setClassname($classname);
        }

        return self::$parameters['classname'];
    }

    public static function setController($controller)
    {
        self::$parameters['controller'] = ucfirst($controller);
    }

    /**
     * Returns Name of the Controller
     *
     * @return string Controller/Modulename
     */
    public static function getController()
    {
        // the default "controller" name is the "module" name
        // this is the case if a route "/:module" is used
        if (isset(self::$parameters['controller']) === false) {
            self::$parameters['controller'] = self::$parameters['module'];
        }

        return ucfirst(self::$parameters['controller']);
    }

    public static function getModule()
    {
        return ucfirst(self::$parameters['module']);
    }

    public static function setModule($module)
    {
        return self::$parameters['module'] = $module;
    }

    public static function setAction($action)
    {
        self::$parameters['action'] = $action;
    }

    public static function getAction()
    {
        return self::$parameters['action'];
    }

    public static function getActionNameWithoutPrefix()
    {
        $action = str_replace('action_', '', self::$parameters['action']);
        $action = str_replace('admin_', '', $action);

        return $action;
    }

    public static function setId($id)
    {
        self::$parameters['params']['id'] = $id;
    }

    public static function getId()
    {
        return self::$parameters['params']['id'];
    }

    /**
     * Method to get the Action with Prefix
     *
     * @return $string
     */
    public static function getActionName()
    {
        return self::$parameters['method'];
    }

    public static function setMethod($method)
    {
        self::$parameters['method'] = $method;
    }

    public static function getMethod()
    {  
        // add method prefix (action_)
        $method = self::mapActionToMethodname(self::getAction());
        self::setMethod($method);        

        return self::$parameters['method'];
    }

    public static function setParameters($params)
    {
        self::$parameters['params'] = $params;
    }

    public static function getParameters()
    {
        return self::$parameters['params'];
    }

    public static function getFormat()
    {
        return self::$parameters['format'];
    }

    public static function setRequestMethod()
    {
        self::$parameters['request'];
    }

    public static function getRequestMethod()
    {
        return HttpRequest::getRequestMethod();
    }

    public static function getLayoutMode()
    {
        return (bool) self::$parameters['layout'];
    }

    public static function getAjaxMode()
    {
        return HttpRequest::isAjax();
    }

    public static function getRenderEngine()
    {
        return self::$parameters['renderer'];
    }

    public static function setRenderEngine($renderEngineName)
    {
        self::$parameters['renderer'] = $renderEngineName;
    }

    public static function getBackendTheme()
    {
        return ($_SESSION['user']['backend_theme'] !== null) ? $_SESSION['user']['backend_theme'] : 'admin';
    }

    public static function getFrontendTheme()
    {
        return ($_SESSION['user']['frontend_theme'] !== null)  ? $_SESSION['user']['frontend_theme'] : 'standard';
    }

    public static function getThemeName()
    {
        if (empty(self::$parameters['themename'])) {
            if (self::getModule() == 'controlcenter' or self::getController() == 'admin') {
                self::setThemeName(self::getBackendTheme());
            } else {
                self::setThemeName(self::getFrontendTheme());
            }
        }

        return self::$parameters['themename'];
    }

    public static function setThemeName($themename)
    {
        self::$parameters['themename'] = $themename;
    }

    public static function getModRewriteStatus()
    {
        return (bool) self::$parameters['modrewrite'];
    }

    /**
     * Method to check if the TargetRoute relates to correct file, controller and action.
     * Ensures route is valid.
     *
     * @return boolean True if TargetRoute is dispatchable, false otherwise.
     */
    public static function dispatchable()
    {
        $filename = self::getFilename();
        $classname = self::getClassname();
        $method = self::getMethod();

        // was the class loaded before?
        if (false === class_exists($classname, false)) {
            // loading manually
            if (is_file($filename) === true) {
                include_once $filename;
                // @todo position for log command
                echo 'Loaded Controller: ' . $filename;
            }
        }

        if (class_exists($classname, false) === true) {
            if (is_callable($classname, $method) === true) {
                return true;
            }
        }

        // this shows how many routes were tried
        echo '<br><strong>Route failure. Not found ' . $filename .' ### '. $classname .' ### '. $method . '</strong><br>';

        return false;
    }

    /**
     * setSegmentsToTargetRoute
     *
     * This takes the requirements array or the uri_segments array
     * and sets the proper parameters on the Target Route,
     * thereby making it dispatchable.
     *
     * URL Examples
     * a) index.php?mod=news=action=archive
     * b) index.php?mod=news&ctrl=admin&action=edit&id=77
     *
     * mod      => controller => <News>Controller.php
     * ctrl     => controller suffix  => News<Admin>Controller.php
     * action   => method     => action_<action>
     * *id*     => additional call params for the method
     */
    public static function setSegmentsToTargetRoute($array)
    {
        /**
         * if array is an found route, it has the following array structure:
         * [regexp], [number_of_segments] and [requirements].
         *
         * for getting the values module, controller, action only the
         * [requirements] array is relevant. overwriting $array drops the keys
         * [regexp] and [number_of_segments] because they are no longer needed.
         */
        if (array_key_exists('requirements', $array)) {
            $array = $array['requirements'];
        }

        // Module
        if (isset($array['module']) === true) {
            self::setModule($array['module']);
            // yes, set the controller of the module, too
            // if it is e.g. AdminController on Module News, then it will be overwritten below
            self::setController($array['module']);
            unset($array['module']);
        }

        // Controller
        if (isset($array['controller']) === true) {
            self::setController($array['controller']);
            // if a module was not set yet, then set the current controller also as module
            if(self::$parameters['module'] === 'index') { 
                self::setModule($array['controller']);
            }
            unset($array['controller']);
        }

        // Action
        if (isset($array['action']) === true) {
            self::setAction($array['action']);
            unset($array['action']);
        }
        
        // Id
        if (isset($array['id']) === true) {
            self::setId($array['id']);
            // if we set an id, and action is empty then [news/id] was requested
            // we fill automatically in the action show
            if(self::$parameters['action'] === 'list') { self::setAction('show'); }          
            unset($array['id']);
        }

        // Parameters
        if (count($array) > 0) {
            self::setParameters($array);
            unset($array);
        }

        # instantiate the target route
        return self::getInstance();
    }

    public static function reset()
    {
        $reset_params = array(
            // File
            'filename' => null,
            'classname' => null,
            // Call
            'module' => 'index',
            'controller' => 'index',
            'action' => 'list',
            'method' => 'action_list',
            'params' => null,
            // Output
            'format' => 'html',
            'language' => 'en',
            'request' => 'get',
            'layout' => true,
            'ajax' => false,
            'renderer' => 'smarty',
            'themename' => null,
            'modrewrite' => false
        );

        #self::$parameters = array_merge(self::$parameters, $reset_params);
        self::$parameters = $reset_params;
    }

    public static function getRoute()
    {
        return self::$parameters;
    }

    public static function debug()
    {
        \Koch\Debug\Debug::printR(self::$parameters);
    }

    /**
     * Sets the given key
     *
     * @param  mixed       $key
     * @param  mixed       $value
     * @return TargetRoute
     */
    public function set($key, $value)
    {
        $this[$key] = $value;

        return $this;
    }

    /**
     * Returns the value of the given key, if the key is not set, returns the default.
     *
     * @param  mixed $key     Key.
     * @param  mixed $default Default Value.
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this[$key]) ? $this[$key] : $default;
    }

    public function toArray()
    {
        return $this->getArrayCopy();
    }
}
