<?php
if (count(get_included_files()) == 1) {
    require_once 'autorun.php';
}

use Koch\Config\Adapter\Json;

class JsonTest extends Clansuite_UnitTestCase
{
    protected $object;

    public function setUp()
    {
        $this->object = new Json;

        if(is_file($this->getFile())) {
            unlink($this->getFile());
        }
    }

    public function tearDown()
    {
        unset($this->object);
    }

    public function getFile()
    {
        return __DIR__ . '/file.json';
    }

    public function testReadConfig_throwsException_IfFileNotFound()
    {
        $this->expectException();
        $this->object->readConfig('not-existant-file.json');
    }

    public function testReadConfig_throwsException_JsonError()
    {
        $this->expectException();
        $this->object->readConfig(__DIR__ . 'error.json');
    }

    public function testReadConfig()
    {
        $this->expectException();
        $json = $this->object->readConfig($this->getFile());

        $expected = array();

        $this->assertEqual($expected, $json);
    }

    public function testWriteConfig()
    {
        $array = array( 'section-1' => array( 'key1' => 'value1' ) );
        $file = __DIR__.'/writeTest.json';

        $int_or_bool = $this->object->writeConfig($file, $array);

        $this->assertIsA($int_or_bool, 'int');

        unlink($file);
    }

    public function testgetJsonErrorMessage()
    {
        $errmsg = $this->object->getJsonErrorMessage(JSON_ERROR_DEPTH);
        $expected = 'The maximum stack depth has been exceeded.';
        $this->assertEqual($expected, $errmsg);
    }
}
