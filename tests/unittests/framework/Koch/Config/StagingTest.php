<?php
namespace Koch\Config;

class StagingTest extends \Clansuite_UnitTestCase
{
    /**
     * @var Staging
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->object = new Staging;

        // set faked server name to environment to test getFilename()
        $_SERVER['SERVER_NAME'] = 'www.clansuite-dev.com';
    }

    /**
     * @covers Koch\Config\Staging::overloadWithStagingConfig
     */
    public function testOverloadWithStagingConfig()
    {
        $array_to_overload = array(
            // new key
            'overloaded-key' => 'overloaded-value',
            // overload existing key value
            'error' => array ('development' => '0')
        );

        $overloaded_cfg = Staging::overloadWithStagingConfig($array_to_overload);

        // new key exists
        $this->assertTrue(array_key_exists('overloaded-key', $overloaded_cfg));
        // new key has correct value
        $this->assertEqual($overloaded_cfg['overloaded-key'], $array_to_overload['overloaded-key']);

        // overloading of key ['error']['development']
        // original value is 0
        $this->assertEqual($array_to_overload['error']['development'], '0');
        // expect that error array is present
        $this->assertTrue(array_key_exists('error', $overloaded_cfg));
        // expect that error array has a key developement
        $this->assertTrue(array_key_exists('development', $overloaded_cfg['error']));
        // expect that this key is set to 1 (on)
        $this->assertEqual($overloaded_cfg['error']['development'], '1');
        // expect that both values are not equal
        $this->assertNotEqual($overloaded_cfg['error']['development'], $array_to_overload['error']['development']);
    }

    /**
     * @covers Koch\Config\Staging::getFilename
     */
    public function testGetFilename()
    {
        // test that the related development config exists
        $expected_filename = ROOT . 'Configuration/Staging/' . 'development.php';
        $this->assertTrue(is_file($expected_filename));

        $filename = Staging::getFilename();

        $this->assertEqual($filename,$expected_filename);
    }
}
