<?php
/**
 * Extension of Simpletest UnitTestCase Class
 */
class Clansuite_UnitTestCase extends UnitTestCase
{
    /**
     * Mark a test as incomplete.
     *
     * Usage:
     * // Remove the following lines when you implement this test.
     *  $this->markTestIncomplete('This test has not been implemented yet.');
     */
    public function markTestIncomplete($msg)
    {
        $this->assertTrue(false, $msg);
    }
    
    /**
     * Mark a test as skipped.
     *
     * Usage:
     *  if (!extension_loaded('abc'))
     *  $this->markTestSkipped('Extension abc not available.');
     */
    public function markTestSkipped($msg)
    {
        $this->assertTrue(false, $msg);
    }
}
?>
