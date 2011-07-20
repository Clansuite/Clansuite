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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
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
 * Clansuite_TargetRoute (processed RequestObject)
 */
class Clansuite_TargetRoute extends Clansuite_Mapper
{
    public static $parameters = array(
        # File
        'filename'      => null,
        'classname'     => null,
        # Call
        'controller'    => 'index',
        'subcontroller' => null,
        'action'        => 'show',
        'method'        => null,
        'params'        => null,
        # Output
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
     * Clansuite_TargetRoute is a Singleton
     *
     * @return instance of Clansuite_TargetRoute class
     */
    public static function getInstance()
    {
        static $instance = null;

        if($instance === null)
        {
            $instance = new Clansuite_TargetRoute();
        }

        return $instance;
    }

    public static function setFilename($filename)
    {
        self::$parameters['filename'] = $filename;
    }

    public static function getFilename()
    {
        #if(empty(self::$parameters['filename']))
        #{
            self::setFilename(self::mapControllerToFilename(self::getModulePath(), self::getController(), self::getSubController()));
        #}

        return self::$parameters['filename'];
    }

    public static function setClassname($classname)
    {
        self::$parameters['classname'] = $classname;
    }

    public static function getClassname()
    {
        if(empty(self::$parameters['classname']))
        {
            self::setClassname(self::mapControllerToClassname(self::getController(), self::getSubController()));
        }

        return self::$parameters['classname'];
    }

    public static function setController($controller)
    {
        self::$parameters['controller'] = $controller;
    }

    /**
     * Returns Name of the Controller
     *
     * @return string Controller/Modulename
     */
    public static function getController()
    {
        return self::$parameters['controller'];
    }

    /**
     * Convenience/shorthand Method for getController()
     *
     * @return string Controller/Modulename
     */
    public static function getModuleName()
    {
        return self::$parameters['controller'];
    }

    public static function setSubController($subcontroller)
    {
        self::$parameters['subcontroller'] = $subcontroller;
    }

    public static function getSubController()
    {
        return self::$parameters['subcontroller'];
    }

    /**
     * Method to get the SubModuleName
     *
     * @return $string
     */
    public static function getSubModuleName()
    {
        return self::$parameters['subcontroller'];
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
        # check if method is correctly prefixed with 'action_'
        if (isset(self::$parameters['method']) and mb_strpos(self::$parameters['method'], 'action_'))
        {
            return self::$parameters['method'];
        }
        else # add method prefix (action_) and subcontroller prefix (admin_)
        {
            self::setMethod(self::mapActionToActioname(self::getAction(), self::getSubController()));
        }

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
        return Clansuite_HttpRequest::getRequestMethod();
    }

    public static function getLayoutMode()
    {
        return (bool) self::$parameters['layout'];
    }

    public static function getAjaxMode()
    {
        return (bool) self::$parameters['ajax'];
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
        return (isset($_SESSION['user']['backend_theme'])) ? $_SESSION['user']['backend_theme'] : 'admin';
    }

    public static function getFrontendTheme()
    {
        return (isset($_SESSION['user']['frontend_theme'])) ? $_SESSION['user']['frontend_theme'] : 'standard';
    }

    public static function getThemeName()
    {
        if(empty(self::$parameters['themename']))
        {
            if(self::getModuleName() == 'controlcenter' or self::getSubModuleName() == 'admin')
            {
                self::setThemeName(self::getBackendTheme());
            }
            else
            {
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

    public static function getModulePath()
    {
        return ROOT_MOD . self::getController() . DS;
    }

    public static function debug()
    {
        $string = (string) implode(",", self::$parameters);
        Clansuite_Debug::firebug($string);
    }

    /**
     * Method to check if the TargetRoute relates to correct file, controller and action.
     *
     * @return boolean True if TargetRoute is dispatchable, false otherwise.
     */
    public static function dispatchable()
    {
        $filename  = self::getFilename();
        $classname = self::getClassname();
        $method    = self::getMethod();

        /**
         * The file we want to call has to exists
         */
        if(is_file($filename))
        {
            include $filename;

            /**
             * Inside this file, the correct class has to exist
             */
            if(class_exists($classname, false))
            {
                # WATCH IT!
                # method_exists works on objects? i just have a classname
                # is_callable on classes ?!
                # @todo how to get the object back for a classname?
                if(true === in_array($method, get_class_methods($classname)))
                {
                      #Clansuite_Debug::firebug('(OK) Route is dispatchable: '. $filename .' '. $classname .'->'. $method);
                      return true;
                }
            }
        }

        #Clansuite_Debug::firebug('(ERROR) Route not dispatchable: '. $filename .' '. $classname .'->'. $method);
        return false;
    }

    public static function reset()
    {
        $reset_params = array(
            # File
            'filename' => null,
            'classname' => null,
            # Call
            'controller' => 'index',
            'subcontroller' => null,
            'action' => 'show',
            'method' => null,
            'params' => null,
            # Output
            'format' => 'html',
            'language' => 'en',
            'request' => 'get',
            'layout' => true,
            'ajax' => false,
            'renderer' => 'smarty',
            'themename' => null,
            'modrewrite' => false
        );

        self::$parameters = array_merge(self::$parameters, $reset_params);
    }

    public static function getRoute()
    {
        return self::$parameters;
    }
}
?>