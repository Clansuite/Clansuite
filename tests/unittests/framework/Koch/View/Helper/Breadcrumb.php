<?php

use Koch\Router\TargetRoute;

/**
 * Test class for Clansuite_Breadcrumb.
 * Generated by PHPUnit on 2011-01-30 at 12:55:19.
 */
class BreadcrumbTest extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Breadcrumb
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->object = new \Koch\View\Helper\Breadcrumb;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->object);
    }

    /**
     * @todo Implement testAdd().
     */
    public function testAdd()
    {
        // test data
        $array = array( array('title' => 'modulenameA', 'link' => 'index.php?mod=modulenameA'),
                        array('title' => 'modulenameB', 'link' => '/index.php?mod=modulenameB'));

        $this->object->add($array[0]['title'], $array[0]['link']);
        $this->object->add($array[1]['title'], $array[1]['link']);

        $t_array = $this->object->getTrail(false);

        // title is uppercased on first char
        $this->assertIdentical('ModulenameA', $t_array[0]['title']);
        // short url to qualified url
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=modulenameA', $t_array[0]['link']);

        // title is uppercased on first char
        $this->assertIdentical('ModulenameB', $t_array[1]['title']);
        // slash at the start is removed and shorturl to qualified url
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=modulenameB', $t_array[1]['link']);
    }

    /**
     * @todo Implement testReplace().
     */
    public function testReplace()
    {
        // test data
        $array = array( array('title' => 'modulenameA', 'link' => 'index.php?mod=modulenameA'),
                        array('title' => 'modulenameC', 'link' => '/index.php?mod=modulenameC'));

        // add array[0]
        $this->object->add($array[0]['title'], $array[0]['link']);
        // replace array[0]
        $this->object->replace($array[1]['title'], $array[1]['link'], 0);

        $t_array = $this->object->getTrail(false);

        $this->assertIdentical('ModulenameC', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=modulenameC', $t_array[0]['link']);
    }

    /**
     * @todo Implement testAddDynamicBreadcrumbs().
     */
    public function testAddDynamicBreadcrumbs()
    {
        /**
         * case A -  normal module - frontend access => module = news, action =  action_show
         *
         * expected path = Home >> News >> Show
         */

        // add Level 1 - Home
        $this->object->resetBreadcrumbs();
        $this->object->initialize();
        TargetRoute::reset();
        TargetRoute::setModule('news');
        TargetRoute::setController('news');
        TargetRoute::setAction('action_show');

        // fetch with dynamical trail building
        $t_array = $this->object->getTrail(true);
        #var_dump($t_array);

        // Level 1 - expected Home
        $this->assertIdentical('Home', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT, $t_array[0]['link']);

        // Level 2 - Modulename News
        $this->assertIdentical('News', $t_array[1]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=news', $t_array[1]['link']);

        // Level 3 - Action  Show
        $this->assertIdentical('Show', $t_array[2]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=news&amp;action=show', $t_array[2]['link']);

        /**
         * case B -  normal module - backend access => module = news, sub = admin, action =  action_admin_show
         *
         * expected path = Controlcenter >> News Admin >> Show
         */

        // add Level 1 - Home
        $this->object->resetBreadcrumbs();
        $this->object->initialize('news', 'admin');
        TargetRoute::reset();
        TargetRoute::setModule('news');
        TargetRoute::setController('admin');
        TargetRoute::setAction('action_show');

        // fetch with dynamical trail building
        $t_array = $this->object->getTrail(true);
        #var_dump($t_array);

        // expected values on level 0
        $this->assertIdentical('Control Center', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=controlcenter', $t_array[0]['link']);

        // expected values on level 1
        $this->assertIdentical('News Admin', $t_array[1]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=news&amp;ctrl=admin', $t_array[1]['link']);

        // expected values on level 2
        $this->assertIdentical('Show', $t_array[2]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=news&amp;ctrl=admin&amp;action=show', $t_array[2]['link']);

        /**
         * case c -  Control Center => module = controlcenter, action = show
         *
         * expected path = Controlcenter
         */

        // add Level 1 - Home
        $this->object->resetBreadcrumbs();
        $this->object->initialize('controlcenter');
        TargetRoute::reset();
        TargetRoute::setModule('news');
        TargetRoute::setController('news');
        TargetRoute::setAction('show');

        // fetch with dynamical trail building
        $t_array = $this->object->getTrail(true);
        #var_dump($t_array);

        // Level 1 - expected
        $this->assertIdentical('Control Center', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=controlcenter', $t_array[0]['link']);

        // Level 2 - Modulename News
        $this->assertIdentical('News', $t_array[1]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=news', $t_array[1]['link']);

        // Level 3 - Action  Show
        $this->assertIdentical('Show', $t_array[2]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=news&amp;action=show', $t_array[2]['link']);

    }

    /**
     * @todo Implement testGetTrail().
     */
    public function testGetTrail()
    {
        // test data
        $array = array( array('title' => 'modulenameA', 'link' => 'index.php?mod=modulenameA'));

        // insert
        $this->object->add($array[0]['title'], $array[0]['link']);

        // fetch
        $t_array = $this->object->getTrail(false);

        // return value is an array
        $this->assertIsA($t_array, 'array');
        // array is not empty
        $bool = empty($t_array) ? true : false;
        $this->assertFalse($bool);
    }

    /**
     * @todo Implement testinitialize().
     */
    public function testinitialize()
    {
        // case HOME
        $this->object->resetBreadcrumbs();
        $this->object->initialize();

        $t_array = $this->object->getTrail(false);

        $this->assertIdentical('Home', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT, $t_array[0]['link']);

        // case CONTROLCENTER module

        $this->object->resetBreadcrumbs();
        $this->object->initialize('controlcenter');

        $t_array = $this->object->getTrail(false);

        $this->assertIdentical('Control Center', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=controlcenter', $t_array[0]['link']);

        // case ADMIN submodule => the first breadcrumb is also the controlcenter (because backend of module)

        $this->object->resetBreadcrumbs();
        $this->object->initialize('testmodule', 'admin');

        $t_array = $this->object->getTrail(false);

        $this->assertIdentical('Control Center', $t_array[0]['title']);
        $this->assertIdentical(WWW_ROOT . 'index.php?mod=controlcenter', $t_array[0]['link']);
    }

    public function testResetBreadcrumbs()
    {
       // lets add an HOME entry on [0]
       $this->object->initialize();
       // and reset the paths array
       $this->object->resetBreadcrumbs();

       $t_array = $this->object->getTrail(false);

       $this->assertIdentical(array(), $t_array);
    }
}