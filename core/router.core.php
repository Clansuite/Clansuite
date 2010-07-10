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
    * @version    SVN: $Id$response.class.php 2580 2008-11-20 20:38:03Z vain $
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_Router
 *
 * Clansuite_Router does URL Formatting and internal Rewriting.
 * The URL is segmented and restructured to fit the internal route to a controller.
 * The internal routes are described in a central routing configuration file.
 * This central config is updated on installation and deinstallation of modules and plugins.
 * @see Clansuite_Routes_Manager
 *
 * Normally all requests made map to a specific physical resource rather than a logical name.
 * With Routing you are able to map a logical name to a specific physical name.
 * Example: map a logical URL (a mod_rewritten one) to a Controller/Method/Parameter
 * Map a FileRequest via logical URL (a mod_rewritten one) to a DownloadController/Method/Parameter
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 * SPL Iterator and ArrayAccess are used for fast iteration and easier access to the stored routes.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */
class Clansuite_Router implements Iterator, ArrayAccess, Clansuite_Router_Interface
{
    private static $use_cache = false;

    private $uri = '';
    private $uri_segments = array();
    private $extension = '';

    /**
      * @var boolean Status of rewrite Engine: true=on, false=off.
     */
    private static $rewriteEngineOn = false;

    /**
     * Routing Table
     *
     * @var array Routes Array
     */
    private $routes = array();
    
    /**
     * Constructor.
     *
     * @param string $request_url The Request URL incomming via Clansuite_HttpRequest::getRequestURI()
     */
    public function __construct($request_uri)
    {
        # clean the incomming uri
        $this->uri = self::prepareRequestURI($request_uri);

        # check if routes caching is activated in config, maybe we can load routes from cache
        if(isset($config['routing']['cache_routes']) and true === $config['routing']['cache_routes'])
        {
            self::$use_cache = true;
        }
        else
        {
            self::$use_cache = false;
        }
    }

    /**
     * Add route
     *
     * @param string $url_pattern
     * @param array $route_options
     */
    public function addRoute($url_pattern, array $route_options)
    {
        $this->connect($url_pattern, $route_options);
    }

    /**
     * Add multiple route
     *
     * @param array $routes Array with multiple routes.
     */
    public function addRoutes(array $routes)
    {
        foreach ($routes as $route => $options)
        {
            $this->addRoute( (string) $route, (array) $options);
        }
    }

    /**
     * Method returns all loaded routes.
     *
     * @return array Returns array with all loaded Routes.
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    /**
     * Delete a route by its url pattern
     *
     * @param string $url_pattern
     */
    public function delRoute($url_pattern)
    {
        unset($this->routes[$url_pattern]);
    }

    /**
     * Generates a URL by parameters.
     *
     * @param string $url_pattern The URL Pattern of the route
     * @param array  $params An array of parameters
     * @param string $fragment
     * @param bool   $absolute Whether to generate an absolute URL
     *
     * @return string The generated (relative or absolute) URL.
     */
    public function generateURL($url_pattern, array $params = null, $fragment = null, $absolute = false)
    {
        
    }

    /**
     * Main method of Clansuite_Router
     *
     * The workflow is
     * 1. check if modrewrite_on, this decides on the url_parser to use
     * 2. url_parser splits the uri into uri segments
     * 3. routes are initalized (the defaultRoute + all moduleRoutes)
     * 4. try to find a route/map matching with the uri_segments
     * 5. if no mapping applies, set default values from config and fallback to a static routing
     * 6. always! -> found_route -> call!
     */
    public function route()
    {
        # detects if RewriteEngine is active and calls the proper URLParser for extraction of uri segments
        if(true === $this->isRewriteEngineOn())
        {
            $this->UrlParser_Rewrite($this->uri);
        }
        else # default
        {
            $this->UrlParser_NoRewrite($this->uri);
            return $this->NoRewriteRoute();
        }

        /**
         * if there are no uri segments, loading routes and matching is pointless
         * return the default route
         */
        if(empty($this->uri) or $this->uri === '/')
        {
            $route = new Clansuite_TargetRoute();
            $route->setController('news');
            $route->setAction('show');
            return $route;
        }

        # attach more routes to this object via the event "onInitializeRoutes"
        Clansuite_CMS::triggerEvent('onInitializeRoutes', $this);

        # initalize Routes
        #$this->loadDefaultRoutes();

        # first filter: drop all routes with more segments then uri_segments
        #self::removeRoutesBySegmentCount();

        # map match uri

    }

    /**
     * Ensures Apache "RewriteEngine on" by performing two checks
     * a) check if ModRewrite is activated in config to avoid overhead
     * b) check if Apache Modules "mod_rewrite" is loaded/enabled
     * c) check if Rewrite Engine is enabled in .htaccess"
     *
     * @return bool True, if "RewriteEngine On". False otherwise.
     */
    public static function isRewriteEngineOn()
    {
        # maybe, we have a modrewrite config setting, this avoids overhead
        if(isset($config['routing']['modrewrite']) and true === $config['routing']['modrewrite'])
        {
            return true;
        }

        # ensure apache has module mod_rewrite active
        if(function_exists('apache_get_modules') and in_array('mod_rewrite', apache_get_modules()))
        {
            # load htacces and check if RewriteEngine is enabled
            $htaccess_content = @file_get_contents(ROOT . '.htaccess');
            self::$rewriteEngineOn = preg_match('/.*[^#][\t ]+RewriteEngine[\t ]+On/i', $htaccess_content);

            if(self::$rewriteEngineOn == 1)
            {
                return true;
            }
            else # RewriteEngine not set or commented off in htaccess
            {
                return false;
            }
        }
        else # Apache Mod_Rewrite not available
        {
            return false;
        }
    }

    /**
     * Get and prepare the SERVER_URL/URI
     *
     * Several fixes are applied the $request_url (which is Clansuite_HttpRequest::getRequestURI())
     * It's already (1) lowercased and (2) urldecoded when incomming.
     * This function (3) strips slashes from beginning and end and (4) prepends a slash.
     * A multislash removal is not needed, because of the later usage of preg_split.
     *
     * @param string $reuest_uril this is basically Clansuite_HttpRequest::getRequestURI
     *
     * @return string Request URL
     */
    private function prepareRequestURI($request_uri)
    {
        $this->uri = '/' . trim($request_uri, '/');

        #Clansuite_Debug::firebug('The initial Server Request URI is "' . $this->uri . '"');

        return $this->uri;
    }

    /**
     * NoRewriteRouting
     *
     * URL Examples
     * a) index.php?mod=news=action=archive
     * b) index.php?mod=news&sub=admin&action=edit&id=77
     *
     * mod      => controller => clansuite_module_mod (".module.php")
     * sub      => file type  => ".admin.php"
     * action   => method     => action_method
     * *id*     => additional call params for the method
     */
    public function NoRewriteRoute()
    {
        $route = new Clansuite_TargetRoute();

        if(isset($this->uri_segments['mod']))
        {
            $route->setController($this->uri_segments['mod']);
            unset($this->uri_segments['mod']);
        }

        if(isset($this->uri_segments['sub']))
        {
            $route->setSubController($this->uri_segments['sub']);
            unset($this->uri_segments['sub']);
        }

        if(isset($this->uri_segments['action']))
        {
            $route->setAction($this->uri_segments['action']);
            unset($this->uri_segments['action']);
        }

        # the rest of the uri_segments are just params for the method

        if(count($this->uri_segments) > 0)
        {
            $route->setParameters($this->uri_segments);
            unset($this->uri_segments);
        }

        return $route;
    }

    /**
     * URLParser for NoRewrite URL/URIs
     *
     * This URLParser has to extract mod, sub, action, id/parameters from the URI.
     *
     * @param string $url The Request URL
     */
    private function UrlParser_NoRewrite($uri) # Standard_Request_Resolver
    {

        # use some parse_url magic to get the url_query part from the uri
        $uri_query_string = parse_url($uri, PHP_URL_QUERY);
        unset($uri);

        /**
         * The ampersand (&)
         */
        $uri_query_array = explode('&', $uri_query_string);

        /**
         * The equals sign (=)
         *
         * This addresses the pair relationship between parameter name and value, like "id=77".
         */
        $parameters = array();
        if(count($uri_query_array) > 0)
        {
            foreach($uri_query_array as $query_pair)
            {
                list($key, $value) = explode('=', $query_pair);
                $parameters[$key] = $value;
            }
        }

        unset($uri_query_string, $uri_query_array, $query_pair, $key, $value);

        /**
         * Finished!
         */
        $this->uri_segments = $parameters;
    }

    /**
     * URLParser for Apache ModRewrite URL/URIs
     *
     * This is based on htaccess rewriting with [QSA,L].
     * Think of it as a Modrewrite_Request_Resolver.
     *
     * Maybe rewriting with [E] would allow a much faster url parsing by string splitting,
     * because of a fixed url style (think of @ or ___ as separators).
     * @todo consider using rewriting with [E] in htaccess and string splitting
     *
     * @param string $url The Request URL
     */
    private function UrlParser_Rewrite($uri)
    {
        /**
         * The query string up to the question mark (?)
         *
         * Removes everything after a "?".
         * Note: with correct rewrite rules in htaccess, this conditon is not needed.
         */
        $pos = mb_strpos($uri, '?');
        if($pos !== false)
        {
            $uri = mb_substr($uri, 0, $pos);
        }
        unset($pos);

        /**
         * The last dot (.)
         *
         * This detects the extension and removes it from the uri string.
         * The extension determines the output format. It is always the last piece of the URI.
         * Even if there are multiple points in the url string this processes only the last dot
         * and fetches everything after it as the extension.
         */
        $pos = mb_strpos($uri, '.');
        if($pos !== false)
        {
            $uri_dot_array = array();
            # Segmentize the url into an array
            $uri_dot_array = explode('.', $uri);
            # chop off the last piece as the extension
            $this->extension = array_pop($uri_dot_array);
            # there might be multiple dots in the url
            # thats why implode is used to reassemble the segmentized array to a string again
            # but note the different glue string: the dots are now replaced by slashes ,)
            # = ini_get('arg_separator.output')
            $uri = implode('/', $uri_dot_array);
        }
        unset($uri_dot_array);
        unset($pos);

        /**
         * The slashes (/) and empty segments
         */
        $url_split = preg_split('#/#', $uri, -1, PREG_SPLIT_NO_EMPTY);
        unset($uri);

        /**
         * Finished!
         */
        #Clansuite_Debug::firebug($uri_split);
        $this->uri_segments = $url_split;
    }

    /**
     * Replaces the placeholders in a route, like alpha, num, word
     * with their regular expressions for later preg_matching.
     * This is used while adding a new Route.
     *
     * @param string $route_with_placeholder A Route with a placeholder like alpha or num.
     */
    public static function placeholdersToRegexp($route_with_placeholders)
    {
        $placeholders = array('(:id)', '(:num)', '(:alpha)', '(:alphanum)', '(:any)', '(:word)',
                              '(:year)', '(:month)', '(:day)');

        $replacements = array('([0-9]+)', '([0-9]+)', '([a-zA-Z]+)', '([a-zA-Z0-9]+)', '(.*)', '(\w+)',
                              '([12][0-9]{3})', '(0[1-9]|1[012])', '(0[1-9]|1[012])');

        return str_replace($placeholders, $replacements, $route_with_placeholders);
    }

    /**
     * This unsets all Routes of Routing Table ($this->routes)
     * which have more segments then the request uri.
     */
    public function removeRoutesBySegmentCount()
    {
        foreach($this->routes as $route_pattern => $route_values)
        {
            if($route_values['number_of_segments'] === count($this->uri_segments))
            {
                continue;
            }
            else
            {
                unset($this->routes[$route_pattern]);
            }
        }
    }

    /**
     * Register the default routes.
     */
    public function loadDefaultRoutes()
    {
        # check cache for routes
        if(true === self::$use_cache and empty($this->routes) and Clansuite_Cache::contains('clansuite.routes'))
        {
            $this->addRoutes(Clansuite_Cache::read('clansuite.routes'));
        }

        if(empty($this->routes)) # load routes table from routes.config.php
        {
            $this->addRoutes( Clansuite_Routes_Manager::loadRoutesFromConfig());

            # and save these routes to cache
            if(true === self::$use_cache)
            {
                Clansuite_Cache::store('clansuite.routes', $this->getRoutes());
            }
        }

        /**
         * Connect some default fallback Routes
         *
         * With ArrayAccess: $r['/:controller'];
         */
        if(empty($this->routes))
        {
            $this->connect('/:controller');
            $this->connect('/:controller/:action');
            $this->connect('/:controller/:action/:id');
            $this->connect('/:controller/:action/:id/:format');
        }
    }

    /**
     * Implementation of SPL ArrayAccess
     */

    /**
     * Instead of working with $router->addRoute(name,map);
     * you may now access the routing table as an array $router[$route] = $map;
     */
    final public function offsetSet($route, $target)
    {
        $this->addRoute($route, $target);
    }

    final public function offsetGet($name)
    {
        if(array_key_exists($name, $this->routes) === true)
        {
            return $this->routes[$name];
        }
        else
        {
            return null;
        }
    }

    final public function offsetExists($name)
    {
        return array_key_exists($name, $this->routes);
    }

    final public function offsetUnset($name)
    {
        unset($this->routes[$name]);
    }
}

/**
 * Clansuite_Mapper
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Mapper
 */
class Clansuite_Mapper
{
    const MODULE_CLASS_PREFIX = 'Clansuite_Module';
    const METHOD_PREFIX = 'action';

    /**
     * @var string Name of the Default Module
     */
    private static $defaultModule = 'news';

    /**
     * @var string Name of the Default Action
     */
    private static $defaultAction = 'show';

    /**
     * Maps the controller and subcontroller (optional) to filename
     *
     * * @param string $controller Name of Controller
     * @param string $subcontroller Name of SubController (optional)
     * @return string filename
     */
    public static function mapControllerToFilename($module_path, $controller, $subcontroller = null)
    {
        $filename = '';

        # construct the module_path, like "/clansuite/modules/news/" + "controller/"
        $module_path = $module_path . 'controller' . DS;

        # subcontroller
        if('admin' == $subcontroller)
        {
            $filename_postfix = '.admin.php';
        }
        else
        {
            $filename_postfix = '.module.php';
        }

        $filename = $module_path . $controller . $filename_postfix;

        return $filename;
    }

    /**
     * Maps Controller and SubController (optional)
     *
     * @param string $controller Name of Controller
     * @param string $subcontroller Name of SubController (optional)
     *
     * @return string classname
     */
    public static function mapControllerToClassname($controller, $subcontroller = null)
    {
        $classname = '';

        # attach controller
        $classname .= '_' . ucfirst($controller);

        # attach subcontroller to classname
        if($subcontroller !== null)
        {
            $classname .= '_' . ucfirst($subcontroller);
        }

        return self::MODULE_CLASS_PREFIX . $classname;
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
    public static function mapActionToActioname($action, $submodule = null)
    {
        # action not set by URL, so we set action from config/this class
        if(false === isset($action))
        {
            # set the method name
            $action = self::$defaultAction;
        }

        # if $submodule is set, use it as a prefix on $action
        if(isset($submodule) and ($submodule !== null))
        {
            $action = $submodule . '_' . $action;
        }

        # all clansuite actions are prefixed with 'action_'
        return 'action_' . $action;
    }
}

class Clansuite_TargetRoute extends Clansuite_Mapper
{
    private static $parameters = array(
        'filename'      => null,
        'classname'     => null,
        'controller'    => 'index',
        'subcontroller' => null,
        'action'        => 'show',
        'method'        => null,
        'params'        => null,
        'format'        => 'html',
        'language'      => 'en',
        'request'       => 'get',
        'layout'        => true,
        'ajax'          => false,
        'renderer'      => 'smarty',
        'modrewrite'    => false
    );

    public static function setFilename($filename)
    {
        self::$parameters['filename'] = $filename;
    }

    public static function getFilename()
    {
        if(empty(self::$parameters['filename']))
        {
            self::setFilename(self::mapControllerToFilename(self::getModulePath(), self::getController(), self::getSubController()));
        }

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

    public static function getController()
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

    public static function setAction($action)
    {
        self::$parameters['action'] = $action;
    }

    public static function getAction()
    {
        return self::$parameters['action'];
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
        else # add method prefix
        {
            $method = 'action_' . self::$parameters['action'];

            # action + prefix = method, set it
            self::setMethod($method);
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

    public static function getModRewriteStatus()
    {
        return (bool) self::$parameters['modrewrite'];
    }

    public static function getModulePath()
    {
        return ROOT_MOD . self::getController() . DS;
    }
}

/**
 * Clansuite Routes Management
 *
 * On Installation new routes are added via the method addRoutesOfModule($modulename).
 * On Deinstallation the routes are removed via method delRoutesOfModlee($modulename).
 */
class Clansuite_Routes_Manager
{
    public static function addRoutesOfModule($modulename)
    {
        self:updateApplicationRoutes($modulename);
    }

    public static function delRoutesOfModule($modulename)
    {
        $module_routes_file = ROOT_MOD . '/' . $modulename . '/' . $modulename . '.routes.php';
        $module_routes = $this->loadRoutesFromConfig($module_routes_file);

        # load main routes file
        $application_routes = $this->loadRoutesFromConfig();

        # subtract the $module_routes from $application_routes array
        $this->deleteRoute($route_name);

        # update / write merged content to application config

    }

    public function deleteRoute($route_name)
    {
        $routes_count = count($this->routes);
        for($i == 0; $i < $routes_count; $i++)
        {
            if($this->routes[$i]['name'] == $route_name)
            {
                array_splice($this->routes, $i, 1);
                break;
            }
        }
        return $this->routes;;
    }

    /**
     * Registers routing for all activated modules
     *
     * @param string $modulename Name of module
     */
    public static function updateApplicationRoutes($modulename = null)
    {
        $activated_modules = array();

        if($modulename === null)
        {
            $activated_modules[] = array($modulename);
        }
        else # get all activated modules
        {
            # $activated_modules =
        }

        foreach($activated_modules as $modulename)
        {
            # load module routing file
            $module_routes_file = ROOT_MOD . '/' . $modulename . '/' . $modulename . '.routes.php';
            $module_routes = $this->loadRoutesFromConfig($module_routes_file);

            # load main routes file
            $application_routes = $this->loadRoutesFromConfig();

            # merge the content of modules into application
            # @todo: consider using array_merge_recursive_distinct /unique ?
            $combined_routes = array_merge_recursive($module_routes, $application_routes);

            # update / write merged content to application config
        }
    }

    /**
     * Load Routes from Configuration File
     *
     * @param string Path to a (module) Routing Configuration File.
     * @return array Array of Routes.
     */
    public static function loadRoutesFromConfig($routes_config_file = null)
    {
        $routes = array();

        if($routes_config_file === null)
        {
            # load common routes configuration
            $routes = include ROOT . 'configuration/routes.config.php';
        }
        else
        {
            # load specific routes config file
            $routes = include ROOT . $routes_config_file;
        }

        return (array) $routes;
    }
}

/**
 * Interface for Clansuite_Router(s)
 *
 * A router has to implement the following methods to resolve the Request to a Module and the Action/Command.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */
interface Clansuite_Router_Interface
{
    function addRoute($name, array $route);
    function addRoutes(array $routes);
    function getRoutes();
    function delRoute($name);
    function generateURL($url_pattern, array $params = null, $fragment = null, $absolute = false);
    function route();
}
?>