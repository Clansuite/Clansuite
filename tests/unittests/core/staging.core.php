<?php
class Clansuite_Staging_Test extends Clansuite_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();

        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/staging.core.php';

        # Dependency
        require_once TESTSUBJECT_DIR . 'core/config/ini.config.php';

        # set faked server name for getFilename()
        $_SERVER['SERVER_NAME'] = 'www.clansuite-dev.com';
    }

    /**
     * testMethod_overloadWithStagingConfig()
     */
    public function testMethod_overloadWithStagingConfig()
    {
        $array_to_overload = array(
            # new key
            'overloaded-key' => 'overloaded-value',
            # overload existing key value
            'error' => array ('development' => '0')
        );

        $overloaded_cfg = Clansuite_Staging::overloadWithStagingConfig($array_to_overload);

        # new key exists
        $this->assertTrue(array_key_exists('overloaded-key', $overloaded_cfg));
        # new key has correct value
        $this->assertEqual($overloaded_cfg['overloaded-key'], $array_to_overload['overloaded-key']);

        # overloading of key ['error']['development']
        # original value is 0
        $this->assertEqual($array_to_overload['error']['development'], '0');
        # expect that error array is present
        $this->assertTrue(array_key_exists('error', $overloaded_cfg));
        # expect that error array has a key developement
        $this->assertTrue(array_key_exists('development', $overloaded_cfg['error']));
        # expect that this key is set to 1 (on)
        $this->assertEqual($overloaded_cfg['error']['development'], '1');
        # expect that both values are not equal
        $this->assertNotEqual($overloaded_cfg['error']['development'], $array_to_overload['error']['development']);

    }

    public function testFileExists_DevlopmentConfig()
    {
        $expected_filename = ROOT_CONFIG . 'staging' . DS . 'development.config.php';

        $this->assertTrue(is_file($expected_filename));
    }

    /**
     * testMethod_getFilename()
     */
    public function testMethod_getFilename()
    {
        $expected_filename = ROOT_CONFIG . 'staging' . DS . 'development.config.php';

        $filename = Clansuite_Staging::getFilename();
        $this->assertEqual($filename,$expected_filename);
    }
}
?>