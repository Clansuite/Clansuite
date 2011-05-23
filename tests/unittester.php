<?php
/**
 * Extension of Simpletest UnitTestCase Class
 */
class Clansuite_UnitTestCase extends UnitTestCase
{
    public function markTestIncomplete($msg)
    {
        $this->assertTrue(false, $msg);
    }
}
?>
