<?php
namespace Koch\Config\Adapter;

if (count(get_included_files()) == 1) {
    require_once 'autorun.php';
}

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
        return dirname(__DIR__) . '/fixtures/writeTest.ini';
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

        $this->assertEqual($ini_array, $this->getIniArray());

        unlink($this->getFile());
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
        $file = dirname(__DIR__) . '/fixtures/booleans.ini';
        $config = $this->object->readConfig($file);

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
        $file = dirname(__DIR__) . '/fixtures/no-section.ini';
        $config = $this->object->readConfig($file);

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
}
