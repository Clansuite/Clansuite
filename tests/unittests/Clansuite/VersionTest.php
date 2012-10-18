<?php
namespace Clansuite;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-12 at 21:24:49.
 */
class VersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Version
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->object = new Version;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
    }

    /**
     * @covers Clansuite\Version::setVersionInformation
     * @todo   Implement testSetVersionInformation().
     */
    public function testSetVersionInformation()
    {
        $this->object->setVersionInformation();

        $this->assertTrue(
            defined('APPLICATION_NAME') &&
            defined('APPLICATION_VERSION') &&
            defined('APPLICATION_VERSION_NAME') &&
            defined('APPLICATION_VERSION_STATE') &&
            defined('APPLICATION_URL')
        );
    }

    /**
     * @covers Clansuite\Version::setVersionInformationToCaches
     * @todo   Implement testSetVersionInformationToCaches().
     */
    public function testSetVersionInformationToCaches()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
