<?php
class Clansuite_Router_Test extends Clansuite_UnitTestCase
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
        $request = new Clansuite_HttpRequest;

        $config = new Clansuite_Config;

        $this->router = new Clansuite_Router($request, $config);

        # url building needs this
        require_once TESTSUBJECT_DIR . 'core/functions.core.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->router);
    }


    public function testMethod_generateURL()
    {
        /*$url = $this->router->generateURL($url_pattern);

        $this->assertEqual('url', $url);*/
    }

    public function testMethod_buildURL()
    {
        /**
         * Do not build an URL, if FQDN is passed
         * like http://clansuite-dev.com/tests/index.php?mod=news&action=show
         * Just return the URL (pass-through).
         */

        $urlstring = WWW_ROOT . 'index.php?mod=news&action=show';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, false);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&action=show', $url);

        $urlstring = WWW_ROOT . 'index.php?mod=news&action=show';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&action=show', $url);

        /**
         * Build URL from internal slashed URLs, like
         * /news
         * /news/show
         * /news/admin/show/2
         */

        /**
         * Parameter 1 - module
         */

        # crappy slashes removal test
        $urlstring = '////news///';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news', $url);

        # crappy slashes removal test
        $urlstring = '/news///';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news', $url);

        $urlstring = '/news';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news', $url);

        /**
         * Parameter 2 - action or sub
         */

        $urlstring = '/news/show';
        $internal_url = false;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&action=show', $url);

        $urlstring = '/news/show';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&amp;action=show', $url);

        $urlstring = '/news/admin/edit/1';
        $internal_url = true;
        $url = $this->router->buildURL($urlstring, $internal_url);
        $this->assertEqual(WWW_ROOT . 'index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id=1', $url);

        /*$urlstring = '';
        $internal_url = false;

        $url = $this->router->buildURL($urlstring, $internal_url)*/
    }
}
?>