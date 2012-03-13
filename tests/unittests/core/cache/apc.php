<?php
#namespace Koch\Test;

use Koch\Cache\Adapter\Apc;

class ApcTest extends Clansuite_UnitTestCase
{
    private $apc_not_loaded;

    public function setUp()
    {
        if(extension_loaded('apc') === false)
        {
            $this->apc_not_loaded = true;
            #$this->markTestSkipped('The PHP extension APC (Alternative PHP Cache) is not loaded.!');
        }
    }

    public function testCache()
    {
        $this->skipIf($this->apc_not_loaded, 'APC extension not loaded!');

        if($this->apc_not_loaded)
        {
            $this->expectException();
        }

        $cache = new Apc();

        $this->assertFalse($cache->contains('key1'));

        $cache->store('key1', 'value1');

        $this->assertEquals('value1', $cache->fetch('key1'));

        $this->assertTrue($cache->contains('key1'));

        $cache->delete('key1');

        $this->assertFalse($cache->contains('key1'));
    }
}
?>