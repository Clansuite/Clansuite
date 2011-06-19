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
    
    /**
     *    Will trigger a pass if the string
     *    is found in the subject. Fail otherwise.
     *    @param string $string     String to look for.
     *    @param string $subject    String to search in.
     *    @param string $message    Message to display.
     *    @return boolean           True on pass
     *    @access public
     */
    function assertContainsString($string, $subject, $message = '%s') {
        return $this->assert(
                new StringExpectation($string),
                $subject,
                $message);
    }
}

/**
 *    Test for a string using simple and fast string functions.
 *    @package Clansuite_SimpleTest
 *    @subpackage UnitTester
 */
class StringExpectation extends SimpleExpectation
{
    private $string;

    /**
     *    Sets the value to compare against.
     *    @param string $string    Pattern to search for.
     *    @param string $message    Customised message on failure.
     *    @access public
     */
    function __construct($string, $message = '%s') {
        parent::__construct($message);
        $this->string = $string;
    }

    /**
     *    Accessor for the string to look out for.
     *    @return string       Perl regex as string.
     *    @access protected
     */
    protected function getString() {
        return $this->string;
    }

    /**
     *    Tests the expectation. True if the string
     *    matches the comparison value.
     *    @param string $compare        Comparison value.
     *    @return boolean               True if correct.
     *    @access public
     */
    function test($compare)
    {        
        if(strpos($compare, $this->getString()) !== false)
        {
            return true; # string is found
        }
        else
        {
            return false; # string not found
        }
    }

    /**
     *    Returns a human readable test message.
     *    @param mixed $compare      Comparison value.
     *    @return string             Description of success
     *                               or failure.
     *    @access public
     */
    function testMessage($compare) {
        if ($this->test($compare)) {
            return $this->describePatternMatch($this->getString(), $compare);
        } else {
            $dumper = $this->getDumper();
            return "Pattern [" . $this->getString() .
                    "] not detected in [" .
                    $dumper->describeValue($compare) . "]";
        }
    }

    /**
     *    Describes a string match including the string
     *    found and it's position.
     *    @param string $string        String to look for.
     *    @param string $subject       Subject to search.
     *    @access protected
     */
    protected function describePatternMatch($string, $subject) {
        $position = strpos($subject, $string);
        $dumper = $this->getDumper();
        return "Pattern [$string] detected at character [$position] in [" .
                $dumper->describeValue($subject) . "] in region [" .
                $dumper->clipString($subject, 100, $position) . "]";
    }
}
?>