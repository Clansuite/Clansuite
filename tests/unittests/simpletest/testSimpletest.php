<?php

# Simpletest
require_once 'simpletest/autorun.php';
#require_once 'simpletest/extensions/phpunit_test_case.php';

# PHPUnit
#require_once 'PHPUnit/Framework.php';

# Test Subject
#require_once dirname(__FILE__) . '/../../../core/xy.core.php';

/**
 * Test class for Simpletest Tests.
 *
 * Overview of Assertions
 * ----------------------
 *
 *  assertTrue($x)                      Fail if $x is false
 *  assertFalse($x)                     Fail if $x is true
 *
 *  assertNull($x)                      Fail if $x is set
 *  assertNotNull($x)                   Fail if $x not set
 *
 *  assertIsA($x, $t)                   Fail if $x is not the class or type $t
 *
 *  assertEqual($x, $y)                 Fail if $x == $y is false
 *  assertNotEqual($x, $y)              Fail if $x == $y is true
 *
 *  assertIdentical($x, $y)             Fail if $x === $y is false
 *  assertNotIdentical($x, $y)          Fail if $x === $y is true
 *
 *  assertReference($x, $y)             Fail unless $x and $y are the same variable
 *  assertCopy($x, $y)                  Fail if $x and $y are the same variable
 *
 *  assertWantedPattern($p, $x)         Fail unless the regex $p matches $x
 *  assertNoUnwantedPattern($p, $x)     Fail if the regex $p matches $x
 *
 *  assertNoErrors()                    Fail if any PHP error occoured
 *  assertError($x)                     Fail if no PHP error or incorrect message
 */
class Simpletest_Test extends UnitTestCase #PHPUnit_Framework_TestCase
{
    /**
     * @var Clansuite_xy
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        #$this->object = new Clansuite_xy;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {

    }

    /**
     * testMethodOne()
     */
    public function testMethodOne()
    {
        $this->assertTrue(TRUE, 'This should be working.');

        // Remove the following lines when you implement this test.
        /*$this->markTestIncomplete(
                'This test has not been implemented yet.'
        );*/
    }

    /**
     * testMethodTwo()
     */
    public function testMethodTwo()
    {
        $this->assertFalse(FALSE, 'This should be working.');

        // Remove the following lines when you implement this test.
        /*$this->markTestIncomplete(
                'This test has not been implemented yet.'
        );*/
    }
}
?>