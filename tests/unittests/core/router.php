<?php
if (count(get_included_files()) == 1)
{
    require_once '../../simpletest/autorun.php';
    require_once '../../bootstrap.php';
    require_once '../../unittester.php';

}

use Koch\Router\Router;
use Koch\Router\TargetRoute;
use Koch\MVC\HttpRequest;
use Koch\Config\Config;

class RouterTest extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Router
     */
    protected $router;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $request = new HttpRequest;

        $config = new Config();

        $this->router = new Router($request, $config);

        # url building needs this
        require_once TESTSUBJECT_DIR . 'core/functions.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->router);
    }

    function testMethod_addRoute()
    {
        $this->router->addRoute('/news/(:id)', array('controller', 'id'));

        $routes = $this->router->getRoutes();

        $this->assertEqual(2, $routes['/news/([0-9]+)']['number_of_segments']);
        $this->assertEqual('#\/news\/?\/([0-9]+)\/?#', $routes['/news/([0-9]+)']['regexp']);

        /*$this->routes[] = array(
                '/:controller/:action/(\d+)/:name',
         array( 'controller' => '',
                'action' => 'show',
                'id' => '',
                'name' => '')
        );*/
        $this->router->reset();
    }

    function testMethod_delRoute()
    {
        $this->router->addRoute('/news/(:id)', array(':controller', 'id'));

        $this->assertTrue(1 == count($this->router->getRoutes()));

        $this->router->delRoute('/news/([0-9]+)');

        $this->assertTrue(0 == count($this->router->getRoutes()));
    }

    function testMethod_reset()
    {
        $this->assertTrue(0 == count($this->router->getRoutes()));

        $this->router->addRoute('/news', array(':controller'));

        $this->assertTrue(1 == count($this->router->getRoutes()));

        $this->router->reset();

        $this->assertTrue(0 == count($this->router->getRoutes()));
    }

    function testMethod_reset_resets_TargetRoute_too()
    {
        TargetRoute::setAction('testclass');
        $this->assertEqual('testclass', TargetRoute::getAction());
        $this->router->reset();
        $this->assertEqual('index', TargetRoute::getAction());
    }

    function testMethod_addRoutes()
    {
        $routes = array(
            '/news'                   => array(':controller'),
            '/news/edit'              => array(':controller', ':action', 'id'),
            '/news/edit/(:id)'        => array(':controller', ':action', 'id'),
            '/news/admin/edit/(:id)'  => array(':controller', ':subcontroller', ':action', 'id'),
            '/news/:year/:month'      => array(':controller', ':year', ':month'),
            '/news/he-ad-li-ne-SEO'   => array(':controller', ':word')
        );

        $this->router->addRoutes($routes);

        $this->assertTrue( count($routes) === count($this->router->getRoutes()));
    }

    function testMethod_addRoutes_via_ArrayAccess()
    {
        $r = $this->router;

        $r['/news']                  = array(':controller');
        $r['/news/edit']             = array(':controller', ':action', 'id');
        $r['/news/edit/(:id)']       = array(':controller', ':action', 'id');
        $r['/news/admin/edit/(:id)'] = array(':controller', ':subcontroller', ':action', 'id');
        $r['/news/:year/:month']     = array(':controller', ':year', ':month');
        $r['/news/he-ad-li-ne-text'] = array(':controller', ':word');

        $this->assertTrue( 6 === count($this->router->getRoutes()));
    }

    function testMethod_removeRoutesBySegmentCount()
    {
        # adding 3 routes, each with different segment number
        $this->router->addRoute('/news', array(':controller'));
        $this->router->addRoute('/news/edit', array(':controller', ':action', 'id'));
        $this->router->addRoute('/news/edit/(:id)', array(':controller', ':action', 'id'));

        $this->assertTrue(3 == count($this->router->getRoutes()));

        $this->router->uri_segments = array('0' => 'news');

        # this makes all other routes irrelevant for the lookup
        $this->router->removeRoutesBySegmentCount();

        $this->assertTrue(1 == count($this->router->getRoutes()));
    }

    function testMethod_prepareRequestURI()
    {
        # prepends slash
        $request_uri = 'news';
        $this->assertEqual('/news', $this->router->prepareRequestURI($request_uri));

        # prepends slash and removes any trailing slashes
        $request_uri = 'news///';
        $this->assertEqual('/news', $this->router->prepareRequestURI($request_uri));

        # prepends slash
        $request_uri = 'news/edit';
        $this->assertEqual('/news/edit', $this->router->prepareRequestURI($request_uri));
    }

    function testMethod_placeholdersToRegexp()
    {
        $this->assertEqual('/route/with/([0-9]+)', $this->router->placeholdersToRegexp('/route/with/(:id)'));
        $this->assertEqual('/route/with/([0-9]+)', $this->router->placeholdersToRegexp('/route/with/(:num)'));
        $this->assertEqual('/route/with/([a-zA-Z]+)', $this->router->placeholdersToRegexp('/route/with/(:alpha)'));
        $this->assertEqual('/route/with/([a-zA-Z0-9]+)', $this->router->placeholdersToRegexp('/route/with/(:alphanum)'));
        $this->assertEqual('/route/with/(.*)', $this->router->placeholdersToRegexp('/route/with/(:any)'));
        $this->assertEqual('/route/with/(\w+)', $this->router->placeholdersToRegexp('/route/with/(:word)'));
        $this->assertEqual('/route/with/([12][0-9]{3})', $this->router->placeholdersToRegexp('/route/with/(:year)'));
        $this->assertEqual('/route/with/(0[1-9]|1[012])', $this->router->placeholdersToRegexp('/route/with/(:month)'));
        $this->assertEqual('/route/with/(0[1-9]|1[012])', $this->router->placeholdersToRegexp('/route/with/(:day)'));
    }

    function testMethod_processSegmentsRegExp()
    {
        $segments = array('news', 'edit', '([0-9]+)');
        $requirements = array('controller', 'action', ':num',);

        $this->assertIdentical('#\/news\/?\/edit\/?\/([0-9]+)\/?#',
            $this->router->processSegmentsRegExp($segments, $requirements));

        /**
         * Static Named Route
         */
        $segments = array(':news');
        $requirements = array('controller');

        $this->assertIdentical('#(?P<news>[a-z0-9_-]+)\/?#',
            $this->router->processSegmentsRegExp($segments, $requirements));
    }

    /*function testMethod_match_DualRoute()
    {
        # on 1st position: controller
        # on 2nd position: id or action
        # on 3nd position: id or action
        # a) controller/id/action, like news/42/edit
        # b) controller/action/id, like news/edit/42
        $this->addRoute('/:controller/(:action|:id)/(:action|:id)');

        # id will match if numeric
    }*/

    function testMethod_match_StaticRoute()
    {
        # http://example.com/login

        $this->router->addRoute('/:controller');
        $this->router->addRoute('/:controller/:action');
        $this->router->addRoute('/:controller/:action/id');

        $r = $this->router;
        $r['/login'] = array('controller' => 'account', 'action' => 'login');

        $this->router->setRequestURI('/login');
        HttpRequest::setRequestMethod('GET');
        $route = $this->router->route();
        #$route->_debug();
        $this->assertEqual('account',                   $route->getController());
        $this->assertEqual('Clansuite\Module\Account',  $route->getClassname());
        $this->assertEqual('action_login',              $route->getMethod());
        $this->assertEqual(array(),                     $route->getParameters());
        $this->assertEqual('GET',                       $route->getRequestMethod());
        unset($route);
        $r->reset(true);

        # http://example.com/about

        $r = $this->router;
        $r['/about'] = array('controller' => 'index', 'action' => 'about');

        $r->setRequestURI('/about');
        HttpRequest::setRequestMethod('GET');
        $route = $this->router->route();
        #$route->_debug();
        $this->assertEqual('index',                     $route->getController());
        $this->assertEqual('Clansuite\Module\Index',    $route->getClassname());
        $this->assertEqual('action_about',              $route->getMethod());
        $this->assertEqual(array(),                     $route->getParameters());
        $this->assertEqual('GET',                       $route->getRequestMethod());
        $r->reset(true);

    }

    function testMethod_match_RestRoutes()
    {
        $this->router->reset()->loadDefaultRoutes();

        # http://example.com/news
        # routes to
        # Controller: News
        # Action: action_index()
        # Type: GET [REST Route]

        HttpRequest::setRequestMethod('GET');
        $this->router->prepareRequestURI('/news');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('index',                 $route->getAction());
        $this->assertEqual('action_index',          $route->getMethod());
        $this->assertEqual(array(),                 $route->getParameters());
        $this->assertEqual('GET',                   $route->getRequestMethod());
        $this->router->reset(true);

        # http://example.com/news/42
        # routes to
        # Controller: News
        # Action: action_show()
        # Id: 42
        # Type: GET [REST Route]

        HttpRequest::setRequestMethod('GET');
        $this->router->prepareRequestURI('/news/42');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_show',           $route->getMethod());
        $this->assertEqual(array('id' => '42'),     $route->getParameters());
        $this->assertEqual('GET',                   $route->getRequestMethod());
        $this->router->reset(true);

        # http://example.com/news/new
        # routes to
        # Controller: News
        # Action: action_new()
        # Type: GET [REST Route]

        HttpRequest::setRequestMethod('GET');
        $this->router->prepareRequestURI('/news/new');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_new',            $route->getMethod());
        $this->assertEqual('GET',                   $route->getRequestMethod());
        $this->router->reset(true);

        # http://example.com/news/42/edit
        # routes to
        # Controller: News
        # Action: action_edit()
        # Id: 42
        # Type: GET [REST Route]

        HttpRequest::setRequestMethod('GET');
        $this->router->prepareRequestURI('/news/42/edit');
        $route = $this->router->route();
        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_edit',           $route->getMethod());
        $this->assertIdentical(array('id' => '42'), $route->getParameters());
        $this->assertEqual('GET',                   $route->getRequestMethod());
        $this->router->reset(true);

        # same as above with reversed last segements
        # http://example.com/news/edit/42
        # routes to
        # Controller: News
        # Action: action_edit()
        # Id: 42
        # Type: GET [WEB]

        HttpRequest::setRequestMethod('GET');
        $this->router->prepareRequestURI('/news/edit/42');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_edit',           $route->getMethod());
        $this->assertEqual(array('id' => '42'),     $route->getParameters());
        $this->assertEqual('GET',                   $route->getRequestMethod());
        $this->router->reset(true);

        # http://example.com/news/42
        # routes to
        # Controller: News
        # Action: action_update()
        # Id: 42
        # Type: PUT [REST Route]
        # Post_parameters are filled.

        HttpRequest::setRequestMethod('PUT');
        $this->router->prepareRequestURI('/news/42');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_update',         $route->getMethod());
        $this->assertEqual(array('id' => '42'),     $route->getParameters());
        $this->assertEqual('PUT',                   $route->getRequestMethod());
        $this->router->reset(true);

        # http://example.com/news
        # routes to
        # Controller: News
        # Action: action_insert()
        # Type: POST [REST Route]
        # Post_parameters are filled.

        HttpRequest::setRequestMethod('POST');
        $this->router->prepareRequestURI('/news');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_insert',         $route->getMethod());
        $this->assertEqual(array('id' => '42'),     $route->getParameters());
        $this->assertEqual('POST',                  $route->getRequestMethod());
        $this->router->reset(true);

        # http://example.com/news/42
        # routes to
        # Controller: News
        # Action: action_delete()
        # Id: 42
        # Type: DELETE [REST Route]

        HttpRequest::setRequestMethod('DELETE');
        $this->router->prepareRequestURI('/news/42');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_delete',         $route->getMethod());
        $this->assertEqual(array('id' => '42'),     $route->getParameters());
        $this->assertEqual('DELETE',                $route->getRequestMethod());
        $this->router->reset(true);

        # same as above, web route
        # http://example.com/news/delete/42
        # routes to
        # Controller: News
        # Action: action_delete()
        # Id: 42
        # Type: DELETE [WEB]

        HttpRequest::setRequestMethod('DELETE');
        $this->router->prepareRequestURI('/news/delete/42');
        $route = $this->router->route();

        $this->assertEqual('news',                  $route->getController());
        $this->assertEqual('Clansuite\Module\News', $route->getClassname());
        $this->assertEqual('action_delete',         $route->getMethod());
        $this->assertEqual(array('id' => '42'),     $route->getParameters());
        $this->assertEqual('DELETE',                $route->getRequestMethod());
        $this->router->reset(true);

        /*
         * Feature Idea:
        $this->router->route('controller_item', '/:controller/<:id>.:format',
            array('defaults' => array(
                    'action' => 'view',
                    'format' => 'html'),
                'get' => array('action' => 'show'),
                'put' => array('action' => 'update'),
                'delete' => array('action' => 'delete')
            )
        );
         */
    }

    function testMethod_match_SEO_Dynamic_Routes()
    {
        # http://example.com/category/movies/Se7en.htm

        # http://example.com/feeds/news/atom.xml

        # http://example.com/news/atom.xml

    }

    function testMethod_match_throwsExceptionIfNoRoutesFound()
    {
        $this->router->reset();

        $this->assertTrue(0 == count($this->router->getRoutes()));

        $this->expectException('OutOfBoundsException');
        $this->assertTrue($this->router->match());
    }

    public function testMethod_generateURL()
    {
        /*
        $url = $this->router->generateURL($url_pattern);
        $this->assertEqual('url', $url);
         */
        $this->markTestIncomplete('Test not implemented yet.');

    }

    public function testMethod_buildURL_ModRewrite_OFF()
    {
        $force_modrewrite_on = false;

        /**
         * Do not build an URL, if FQDN is passed and mod_rewrite is off.
         * like http://clansuite-dev.com/tests/index.php?mod=news&action=show
         * Just return the URL (pass-through).
         */
        $urlstring = WWW_ROOT . 'index.php?mod=news&action=show';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, false, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&action=show', $url);

        $urlstring = WWW_ROOT . 'index.php?mod=news&action=show';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&action=show', $url);

        /**
         * Build FQDN URL from internal slashed URLs, like
         * /news
         * /news/show
         * /news/admin/show/2
         *
         * So internally we use the mod_rewrite style.
         */
        /**
         * Parameter 1 - module
         */
        # removes crappy slashes
        $urlstring = '////news///';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news', $url);

        # removes crappy slashes
        $urlstring = '/news///';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news', $url);

        $urlstring = '/news';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news', $url);

        /**
         * Parameter 2 - action or sub
         */
        $urlstring = '/news/show';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&action=show', $url);

        $urlstring = '/news/show/42';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&amp;action=show&amp;id=42', $url);

        $urlstring = '/news/admin/edit/1';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url, $force_modrewrite_on);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id=1', $url);
    }

    public function testMethod_buildURL_ModRewrite_ON()
    {
        /**
         * Build URL from internal slashed URLs, like
         * /news
         * /news/show
         * /news/admin/show/2
         *
         * So internally we use the mod_rewrite style.
         */
        /**
         * Parameter 1 - module
         */
        # removes crappy slashes
        $urlstring = '////news///';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'news', $url);

        # removes crappy slashes
        $urlstring = '/news///';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'news', $url);

        $urlstring = '/////news';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'news', $url);

        /**
         * Parameter 2 - action or sub
         */
        $urlstring = '/news/show';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'news/show', $url);

        /**
         * Internal URLs (mod_rewrite style)
         * This should by-pass...
         */
        $urlstring = '/news/show/42';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'news/show/42', $url);

        $urlstring = '/news/admin/edit/1';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'news/admin/edit/1', $url);
    }
}
?>