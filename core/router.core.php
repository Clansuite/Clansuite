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
    * @version    SVN: $Id$response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

// Security Handler
if (!defined('IN_CS')) { die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * Clansuite_Route
 *
 * Purpose:
 * Clansuite_Route does URL Formating and internal Rewriting.
 * It's a wrapper around PEAR Net_URL_Mapper.
 * 
 * The URL is segmented and restructured to fit the internal route to a controller.
 * The internal routes are described in a central routing configuration file.
 * This central config is updated on installation and deinstallation of modules and plugins.
 * On Installation new routes are added via the method add_url_route().
 * On Deinstallation the routes are removed via method del_url_route().
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */

class Clansuite_Router
{
    /**
     * Objects
     */

    /**
     * @var object Clansuite_Router is a singleton instance.
     */
    private static $instance = null;

    /**
     * @var object Instance of Net_URL_Mapper
     */
    private static $router = null;

    /**
     * @var object Clansuite_HttpRequest
     */
    private $request;

    /**
     * @var object Clansuite_Configuration
     */
    private $config;
    
    /**
     * Route(s)
     */

    /**
     * @var string The current route.
     */
    private $route;

    /**
     * @var array The general routing table.
     */
    private $routes;

    /**
     * URI Segments
     */
    private $module;
    private $sub;
    private $action;
    private $id;

    /**
     * @var string The base URL
     */
    private static $baseURL = 'index.php/';

    /**
     * Returns an instance of Clansuite_Router (singleton).
     *
     * @return an instance of the Clansuite_Router
     */
    public static function getInstance()
    {
        if (self::$instance == 0)
        {
            self::$instance = new Clansuite_Router();
        }
        return self::$instance;
    }

    /**
     * Constructor of Clansuite_Router
     *
     * 1) loads PEAR Net_URL_Mapper
     *
     * @param Clansuite_HttpRequest $request
     * @param Clansuite_Config $config
     */
    public function __construct(Clansuite_HttpRequest $request, Clansuite_Config $config)
    {
        # PEAR Net_URL_Mapper = Router
        if(class_exists($class_name, false))
        {
            require '/Net/URL/Mapper.php';
        }

        if ($this->router == false)
        {
            $this->router = Net_URL_Mapper::getInstance('Clansuite_Default_Router');
        }

        $this->request = $request;
        $this->config  = $config;
    }

    public function initializeRoutes()
    {
        # load Routes Table from routes.config.php
        # $this->routes = $this->loadRoutesFromConfig();

        # set index.php as base scriptname for the router = "index.php" + routes
        $this->router->setScriptname('index.php');

        # Event: initializeRoutes with context (guess what?) Net_URL_Mapper
        Clansuite_CMS::triggerEvent('initializeRoutes', array($this->routes));

        return $this->routes;
    }

    public function loadRoutesFromConfig($routes_config_file = null)
    {
        $routes = array();

        if($routes_cfg_file == null)
        {
            # load common events configuration
            $routes = require ROOT . 'configuration/events.config.php';
        }
        else
        {
            # load specific event config file
            $routes = require ROOT . $event_cfg_file;
        }

        # register routing for all activated modules
        foreach (Clansuite_Router::$modules as $module)
        {
            $this->router->connect('index.php?module=' . $module, array('module' => $module));
        }

        return $routes;
    }

    /**
     * match()
     *
     * @param $path A path like "/news/id/77"
     * @return $match Returns the route found for the given path, like "?mod=news&action=action_show&id=77"
     */
    public function match($path)
    {
        try
        {
            $match = $this->routes->match($path);
        }
        catch (Net_URL_Mapper_InvalidException $e)
        {
            # The Route is wrong anyhow...
            Clansuite_Logger( "The route for $path is wrong :" . $e->getMessage());

            # @todo hmm? redirect 404 or default module
        }

        return $match;
    }

    /**
     * Wrapper for Net_Url_Mapper->generate()
     *
     * @param $action
     * @param $args
     * @param $fragment
     * @return $url
     */
    public function generate($action, $args = null, $params = null, $fragment = null)
    {
        $action_arg = array( 'action' => $action );

        if ($args)
        {
            $args = array_merge($action_arg, $args);
        }
        else
        {
            $args = $action_arg;
        }

        $url = $this->routes->generate($args, $params, $fragment);

       
        return $url;
    }

    /**
     * Returns the current Route
     *
     * @return
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Adds and retruns an url-string segment (&x=y) to the baseurl
     * If Package pecl_http is present it is used.
     *
     * @param string $appendString the URL parameter string to append to the url
     * @param string $url the url to append to
     * @example
     *   $sUrl = $this->addQueryToUrl("par1=value1&par2=value2...");
     */
    public static function addQueryToUrl($appendString, $url = null)
    {
        #Clansuite_Xdebug::firebug( http_build_url(self::$baseURL, array( "query"  => $appendString  ), HTTP_URL_JOIN_QUERY ) );
        if (extension_loaded('http'))
        {
            # add additional query parameter to the url
            return http_build_url(self::$baseURL, array( "query"  => $appendString  ), HTTP_URL_JOIN_QUERY );
        }
        else
        {
            if(is_null($url))
            {
                $url = self::$baseURL;
            }

            if( (is_int(strpos($url, "?"))) )
            {
                $url = $url . "&" . $appendString;
            }
            else
            {
                $url = $url . "?" . $appendString;
            }

            return $url;
        }
    }

    /**
     * Add an path-string segment to the baseurl or exchange the path
     * Note: If Package pecl_http is present it is used.
     *
     * @param string $path The path to append to an existing path or the new path.
     * @param boolean $exchange If exchange is true, the path is exchanged. Otherwise the path is appended.
     */
    public static function addPathToUrl($path, $exchange)
    {
        if (extension_loaded('http'))
        {
            if($exchange === true)
            {
                return http_build_url(self::$baseURL, array( "path"  => $path  ));
            }
            else # append path
            {
                return http_build_url(self::$baseURL, array( "path"  => $path ), HTTP_URL_JOIN_PATH );
            }
        }
        else
        {
            # @todo
        }
    }
}
?>