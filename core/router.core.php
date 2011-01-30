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
 * Examples: map a logical URL (a mod_rewritten one) to a Controller/Method/Parameter
 * or map a FileRequest via logical URL (a mod_rewritten one) to a DownloadController/Method/Parameters
 *
 * There are two different URL Formatings allowed:
 * 1. Slashes as Segment Dividers-Style, like so: /mod/sub/action/id
 * 2. Fake HTML File Request or SMF-Style, like so: /mod.sub.action.id.html
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Router
 */
class Clansuite_Router implements ArrayAccess, Clansuite_Router_Interface
{
    /**
     * @var object Clansuite_Config
     */
    private $config;

    private static $use_cache = false;

    private $uri = '';
    private $uri_segments = array();
    private static $extension = '';

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
     */
    public function __construct(Clansuite_HttpRequest $request, Clansuite_Config $config)
    {
        # set config object to the router for later access to config variables
        $this->config = $config;

        # fetch the (unmodified) Request URI from HttpRequest Object
        $request_uri = $request->getRequestURI();

        # clean the incomming uri and set to class
        $this->uri = self::prepareRequestURI($request_uri);
    }

    /**
     * Add route
     *
     * @param string $url_pattern
     * @param array $route_options
     */
    public function addRoute($url_pattern, array $route_options = null)
    {
        /**
         * 1) Preprocess the route
         */
        # split the pattern describing the URL target into uri segments
        $url_pattern = ltrim($url_pattern, '/');
        $segments = explode('/', $url_pattern);

        # because the incomming route might have placeholders lile (:num) or (:id)
        $url_pattern = self::placeholdersToRegexp($url_pattern);

        $regexp = '';
        $regexp = $this->processSegmentsRegExp($segments, $route_options);
        $number_of_segments = count($segments);
        $options = array('regexp' => $regexp,
                         'number_of_segments' => $number_of_segments);


        /**
         * 2) Finally add the *now preprocessed* Route.
         */
        $this->routes[$url_pattern] = $options;
    }

    public function processSegmentsRegExp(array $segments, array $requirements = null)
    {
        # start regular expression
        $regexp = '/^';

        # process all segments
        foreach($segments as $segment)
        {
            $match = '';

            /**
             * process static named parameter => ":contoller"
             */
            if (true === preg_match('/^:([a-zA-Z_]+)$/', $segment, $match))
            {
                $name = $match[1]; #controller

                # is there a requirement for this param?
                if(true === isset($requirements[$name]))
                {
                    # add it to the regex
                    $regexp .= '\/(?P<' . $name . '>' . $requirements[$name] . ')';
                    # and remove the requirement
                    unset($requirements[$name]);
                }
                else # no requirement
                {
                    $regexp .= '(?P<' . $name . '>[a-z0-9_-]+)';
                }
            }
            else # process static parameter = string => "/index" or "/news"
            {
                $regexp .= '\\/' . $segment;
            }

            # regexp between segments
            $regexp .= '\/?';
        }

        # finish regular expression
        $regexp .= '$/';

        return $regexp;
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
     * @param bool   $absolute Whether to generate an absolute URL
     *
     * @return string The generated (relative or absolute) URL.
     */
    public function generateURL($url_pattern, array $params = null, $absolute = false)
    {
        # @todo merge with buildURL + routing rules + parameters
    }

    /**
     * Builds a url string
     *
     * @param $urlstring String to build the url from (e.g. '/news/admin/show')
     * @param $internal_url bool True (default) defines ampersand type as "amp"; False as "&".
     */
    public static function buildURL($urlstring, $internal_url = true)
    {
        # if urlstring is already a qualified url
        if(false !== strpos($urlstring, WWW_ROOT . 'index.php?mod='))
        {
            # there is no need to rebuild it, just return
            return $urlstring;
        }
        # e.g. ROOT/news/admin
        elseif(REWRITE_ENGINE_ON === true)
        {
            #Clansuite_Debug::firebug(WWW_ROOT . ltrim($urlstring, '/'));
            return WWW_ROOT . ltrim($urlstring, '/');
        }
        else # ROOT/index.php?mod=abc&action=123&etc...
        {
            $url_values = explode('/', ltrim($urlstring, '/'));
            $url_keys = array('mod', 'sub', 'action', 'id');
            $url_data = Clansuite_Functions::array_unequal_combine($url_keys, $url_values);
            $url = '';

            # Defaults to &amp; for internal usage in html documents.
            #  = ROOT/index.php?mod=abc&amp;action=123&amp;etc...
            if($internal_url === true)
            {
                $url = http_build_query($url_data, '', '&amp;');
            }
            # external link / redirect etc.
            #  = ROOT/index.php?mod=abc&action=123&etc...
            elseif($internal_url === false)
            {
                $url = http_build_query($url_data, '', '&');
            }

            #Clansuite_Debug::firebug(WWW_ROOT . 'index.php?' . $url);
            return WWW_ROOT . 'index.php?' . $url;
        }
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
        if(true === $this->isRewriteEngineOn() and empty($_GET['mod']) and empty($_GET['sub']))
        {
            $this->UrlParser_Rewrite($this->uri);
        }
        else # default
        {
            $this->UrlParser_NoRewrite($this->uri);
            return $this->NoRewriteRoute();
        }

        /**
         * If there are no uri segments, loading routes and matching is pointless.
         * Dispatch to the default route!
         */
        if(empty($this->uri) or $this->uri === '/')
        {
            Clansuite_TargetRoute::setController($this->config['defaults']['module']);
            Clansuite_TargetRoute::setAction($this->config['defaults']['action']);

            if(Clansuite_TargetRoute::dispatchable() === true)
            {
                return Clansuite_TargetRoute::getInstance();
            }
        }

        # attach more routes to this object via the event "onInitializeRoutes"
        #Clansuite_CMS::triggerEvent('onInitializeRoutes', $this);

        # initalize Routes
        $this->loadDefaultRoutes();

        # first filter: drop all routes with more segments then uri_segments
        self::removeRoutesBySegmentCount();

        # map match uri
        return $this->mapMatchURI();
    }

    /**
     * Matches the URI against the Routes Table
     * takes static, dynamic and regexp routings into account
     *
     * @return object Clansuite_TargetRoute
     */
    public function mapMatchURI()
    {
        #Clansuite_Debug::firebug($this->uri);

        /**
         * Do we have a direct match ?
         * URI = '/index/show' => Routes['/index/show']
         */
        if(isset($this->routes[$this->uri])) # does this check work?
        {
            $found_route = '';
            $found_route = $this->routes[$this->uri];
        }
        else # no, there wasn't a 1:1 match. now we have to check the uri segments
        {
            # loop over the remaining routes and try to map match the uri_segments
            foreach($this->routes as $route_pattern => $route_values)
            {
                unset($route_pattern);

                #Clansuite_Debug::firebug($route_values);

                $matches = '';

                /**
                 * process static named parameter
                 * like ":controller" or ":subcontroller" or ":action" or ":id"
                 * $route_pattern
                 */
                if (true === preg_match('/^:([a-zA-Z_]+)$/', $this->uri, $matches))
                {
                    #Clansuite_Debug::firebug($matches);
                    # $found_route = $matches[1];
                    # @todo
                    Clansuite_TargetRoute::setController($matches[1]);
                }
                # dynamic regexp segment?
                elseif(true === preg_match( $route_values['regexp'], $this->uri, $matches))
                {
                    #Clansuite_Debug::firebug($matches);

                    # parameters found by regular expression have priority
                    if(true === isset($matches['controller']))
                    {
                        Clansuite_TargetRoute::setController($matches['controller']);
                    }

                    if(true === isset($matches['subcontroller']))
                    {
                        Clansuite_TargetRoute::setSubController($matches['subcontroller']);
                    }

                    if(true === isset($matches['action']))
                    {
                        Clansuite_TargetRoute::setAction($matches['action']);
                    }

                    if(true === isset($matches['id']))
                    {
                        Clansuite_TargetRoute::setId($matches['id']);
                    }
                }

                if(Clansuite_TargetRoute::dispatchable() === true)
                {
                    # route found
                    break;
                }
                else
                {
                    Clansuite_TargetRoute::reset();
                }
            }
        }

        #Clansuite_TargetRoute::setController($found_route);
        #Clansuite_TargetRoute::setAction('show');

        return Clansuite_TargetRoute::getInstance();
        # Clansuite_CMS::triggerEvent('onAfterInitializeRoutes', $this);
    }

    /**
     * Ensures Apache "RewriteEngine on" by performing two checks
     * a) check if ModRewrite is activated in config to avoid overhead
     * b) check if Apache Modules "mod_rewrite" is loaded/enabled
     *    and Rewrite Engine is enabled in .htaccess"
     *
     * @return bool True, if "RewriteEngine On". False otherwise.
     */
    public function isRewriteEngineOn()
    {
        # maybe, we have a modrewrite config setting, this avoids overhead
        if(true === isset($this->config['routing']['modrewrite']) and true === $this->config['routing']['modrewrite'])
        {
            define('REWRITE_ENGINE_ON', true);
            return true;
        }

        # ensure apache has module mod_rewrite active
        if(true === function_exists('apache_get_modules') and
           true === in_array('mod_rewrite', apache_get_modules()))
        {
            # load htaccess and check if RewriteEngine is enabled
            if(true === is_file(ROOT . '.htaccess'))
            {
                $htaccess_content = file_get_contents(ROOT . '.htaccess');
                self::$rewriteEngineOn = preg_match('/.*[^#][\t ]+RewriteEngine[\t ]+On/i', $htaccess_content);
            }

            if(true === self::$rewriteEngineOn)
            {
                define('REWRITE_ENGINE_ON', true);
                return true;
            }
            else # RewriteEngine not set or commented off in htaccess
            {
                define('REWRITE_ENGINE_ON', false);
                return false;
            }
        }
        else # Apache Mod_Rewrite not available
        {
            define('REWRITE_ENGINE_ON', false);
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
        #Clansuite_Debug::firebug('The unprepared Server Request URI is "' . $request_uri . '"');

        # add slash in front + remove slash at the end
        $this->uri = '/' . trim($request_uri, '/');

        # path subtraction (get length of dirname of php_self and subtract from uri)
        $url_directory_prefix_length = strlen(dirname($_SERVER['PHP_SELF']));
        $this->uri = substr($this->uri, $url_directory_prefix_length);

        #Clansuite_Debug::firebug('The prepared Server Request URI is "' . $this->uri . '"');

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
        # Controller
        if(true === isset($this->uri_segments['mod']))
        {
            Clansuite_TargetRoute::setController($this->uri_segments['mod']);
            unset($this->uri_segments['mod']);
        }

        # SubController
        if(true === isset($this->uri_segments['sub']))
        {
            Clansuite_TargetRoute::setSubController($this->uri_segments['sub']);
            unset($this->uri_segments['sub']);
        }

        # Action
        if(true === isset($this->uri_segments['action']))
        {
            Clansuite_TargetRoute::setAction($this->uri_segments['action']);
            unset($this->uri_segments['action']);
        }

        # Parameters
        if(count($this->uri_segments) > 0)
        {
            Clansuite_TargetRoute::setParameters($this->uri_segments);
            unset($this->uri_segments);
        }

        if(Clansuite_TargetRoute::dispatchable() === true)
        {
            return Clansuite_TargetRoute::getInstance();
        }
    }

    /**
     * URLParser for NoRewrite URL/URIs
     *
     * This URLParser has to extract mod, sub, action, id/parameters from the URI.
     * This is the Standard_Request_Resolver.
     *
     * @param string $url The Request URL
     */
    private function UrlParser_NoRewrite($uri)
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
        $key = '';
        $value = '';
        $query_pair = '';

        if(count($uri_query_array) > 0)
        {
            foreach($uri_query_array as $query_pair)
            {
                if( false !== strpos($query_pair, '='))
                {
                    list($key, $value) = explode('=', $query_pair);
                    $parameters[$key] = $value;
                }
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
            self::$extension = array_pop($uri_dot_array);
            # there might be multiple dots in the url
            # thats why implode is used to reassemble the segmentized array to a string again
            # but note the different glue string: the dots are now replaced by slashes ,)
            # = ini_get('arg_separator.output')
            $uri = implode('/', $uri_dot_array);
            unset($uri_dot_array);
        }
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
        unset($url_split);
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
        $route_pattern = '';
        $route_values = '';

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
     * Method checks if routes caching is activated in config,
     * maybe we can load routes from cache.
     *
     * @return bool True if route caching active.
     */
    public function checkRouteCachingActive()
    {
        if(true === isset($this->config['routing']['cache_routes']) and
           true === $this->config['routing']['cache_routes'])
        {
            self::$use_cache = true;
        }
        else
        {
            self::$use_cache = false;
        }
    }

    /**
     * Register the default routes.
     */
    public function loadDefaultRoutes()
    {
        $this->checkRouteCachingActive();

        # Load Routes from Cache
        if(true === self::$use_cache and empty($this->routes) and Clansuite_Cache::contains('clansuite.routes'))
        {
            $this->addRoutes(Clansuite_Cache::read('clansuite.routes'));
        }

        # Load Routes from routes.config.php
        if(empty($this->routes))
        {
            $this->addRoutes(Clansuite_Routes_Manager::loadRoutesFromConfig());

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
        if(true === empty($this->routes))
        {
            $this->addRoute('/:controller');
            $this->addRoute('/:controller/:action');
            $this->addRoute('/:controller/:action/:id');
            $this->addRoute('/:controller/:action/:id/:format');

            $this->addRoute('/:controller/:subcontroller');
            $this->addRoute('/:controller/:subcontroller/:action');
            $this->addRoute('/:controller/:subcontroller/:action/:id');
            $this->addRoute('/:controller/:subcontroller/:action/:id/:format');
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
 * Provides helper methods to transform (map)
 * (a) the controller name into the specific application classname and filename
 * (b) the action name into the specific application actioname.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Mapper
 */
class Clansuite_Mapper
{
    /**
     * Classname prefix for modules
     * 
     * @const string
     */
    const MODULE_CLASS_PREFIX = 'Clansuite_Module';
    
    /**
     * Method prefix for module actions
     *
     * @const string
     */
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
     * @param string $controller Name of Controller
     * @param string $subcontroller Name of SubController (optional)
     * @return string filename
     */
    public static function mapControllerToFilename($module_path, $controller, $subcontroller = null)
    {
        $filename = '';
        $filename_postfix = '';

        # construct the module_path, like "/clansuite/modules/news/" + "controller/"
        $module_path = $module_path . 'controller' . DS;

        # subcontroller
        if(isset($subcontroller) and 'admin' == $subcontroller)
        {
            $filename_postfix = '.admin.php';
        }
        elseif(isset($subcontroller) and $subcontroller != 'admin') # any subcontroller name as postfix
        {
            $filename_postfix = '.'.$subcontroller.'.php';
        }
        else # apply standard postfix
        {
            $filename_postfix = '.module.php';
        }

        $filename = $module_path . $controller . $filename_postfix;

        unset($filename_postfix, $module_path);

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
        if(isset($subcontroller))
        {
            $classname .= '_' . ucfirst($subcontroller);
        }

        return self::MODULE_CLASS_PREFIX . $classname;
    }

    /**
     * Maps the action to it's method name.
     * The prefix 'action_' (pseudo-namesspace) is used for all actions.
     * Example: A action named "show" will be mapped to "action_show()"
     * This is also a way to ensure some kind of whitelisting via namespacing.
     *
     * The use of submodules like News_Admin is also supported.
     * In this case the actionname is action_admin_show().
     *
     * @param  string $action the action
     * @param  string $submodule the submodule
     * @return string the mapped method name
     */
    public static function mapActionToActioname($action, $submodule = null)
    {
        # set default value for action, when not set by URL
        if(false === isset($action))
        {
            $action = self::$defaultAction;
        }

        # if a $submodule is set, use it as a PREFIX on $action
        if(isset($submodule))
        {
            $action = $submodule . '_' . $action;
        }

        # all clansuite actions are prefixed with 'action_'
        return self::METHOD_PREFIX . '_' . $action;
    }
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

/**
 * Clansuite Routes Management
 *
 * On Installation new routes are added via the method addRoutesOfModule($modulename).
 * On Deinstallation the routes are removed via method delRoutesOfModlee($modulename).
 */
class Clansuite_Routes_Manager
{
    public function addRoutesOfModule($modulename)
    {
        self::updateApplicationRoutes($modulename);
    }

    public function delRoutesOfModule($modulename)
    {
        # @todo
        $module_routes_file = ROOT_MOD . $modulename . '/' . $modulename . '.routes.php';
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

        # loop over all routes
        for($i == 0; $i < $routes_count; $i++)
        {
            # check if there is a route with the given name
            if($this->routes[$i]['name'] == $route_name)
            {
                # got one? then remove it from the routes array and stop
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
    public function updateApplicationRoutes($modulename = null)
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
            $module_routes_file = ROOT_MOD . $modulename . '/' . $modulename . '.routes.php';
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
            # includes array $routes
            include ROOT . 'configuration/routes.config.php';
        }
        else
        {
            # load specific routes config file
            include ROOT . $routes_config_file;
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
    function addRoute($url_pattern, array $route_options = null);
    function addRoutes(array $routes);
    function getRoutes();
    function delRoute($name);
    function generateURL($url_pattern, array $params = null, $absolute = false);
    function route();
}
?>