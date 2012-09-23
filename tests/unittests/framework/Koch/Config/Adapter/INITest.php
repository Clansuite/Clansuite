<?php
if (count(get_included_files()) == 1) {
    require_once 'autorun.php';
}

namespace Koch\Config\Adapter;

class INITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var INI
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->object = new INI;

        if (is_file($this->getFile())) {
            unlink($this->getFile());
        }
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->object);
    }

    public function getIniArray()
    {
        return array(
            'section' => array (
                'key1' => 'value1',
                'key2' => 'value2',
                'key3-int' => 123
        ));
    }

    public function getFile()
    {
        return __DIR__ . '/file.ini';
    }

    public function testReadConfig_throwsException_IfFileNotFound()
    {
        $this->expectException();
        $this->object->readConfig('not-existant-file.ini');
    }

    /**
     * @covers Koch\Config\Adapter\INI::writeConfig
     */
    public function testWriteConfig()
    {
        $ini_array = $this->object->writeConfig($this->getFile(), $this->getIniArray());
    }

    /**
     * @covers Koch\Config\Adapter\INI::writeConfig
     */
    public function testWriteConfig_secondParameterMustBeArray()
    {
        $this->expectError(); // from "array" type hint
        $this->expectException();
        $ini_array = $this->object->writeConfig($this->getFile(), 'string');
    }

    public function testReadingBooleanValues()
    {
        $config = $this->object->readConfig(__DIR__.'/booleans.ini');

        $this->assertTrue($config['booleans']['test_on']);
        $this->assertFalse($config['booleans']['test_off']);

        $this->assertTrue($config['booleans']['test_yes']);
        $this->assertFalse($config['booleans']['test_no']);

        $this->assertTrue($config['booleans']['test_true']);
        $this->assertFalse($config['booleans']['test_false']);

        $this->assertFalse($config['booleans']['test_null']);
    }

    public function testReadingWithoutSection()
    {
        $config = $this->object->readConfig(__DIR__.'/no-section.ini');

        $expected = array(
            'string_key' => 'string_value',
            'bool_key' => true
        );

        $this->assertEqual($expected, $config);
    }

    /**
     * @covers Koch\Config\Adapter\INI::writeConfig
     * @covers Koch\Config\Adapter\INI::readConfig
     */
    public function testReadConfig()
    {
        $this->object->writeConfig($this->getFile(), $this->getIniArray());

        $ini_array = $this->object->readConfig($this->getFile());

        $this->assertEqual($ini_array, $this->getIniArray());

        $this->assertIsA($ini_array['section']['key3-int'], 'string');
    }

    /**
     * @covers Koch\Config\Adapter\INI::readConfig
     */
    public function testReadConfig()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
