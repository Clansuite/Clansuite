<?php
class Clansuite_Formelement_Validator_Url_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Formelement_Validator_Url
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/url.php';
        $this->validator = new Clansuite_Formelement_Validator_Url;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->validator);
    }

    public function testMethod_processValidationLogic()
    {
        /**
         * method processValidationLogic is indirectly tested via calling
         * validate() on the parent class, which then calls processValidationLogic()
         */

        # IDNA URL based on intl extension
        if(function_exists('idn_to_ascii'))
        {
            $this->assertEqual(idn_to_ascii('url-ästhetik.de'),
                        $this->validator->validate('url-ästhetik.de'));
        }

        # hmm... this puny doesn't ride...
        $this->assertFalse($this->validator->validate('http://www.täst.com'));

        # no dash
        $this->assertTrue($this->validator->validate('http://clansuite.com'));

        # 1 dash
        $this->assertTrue($this->validator->validate('http://clan-cms.com'));

        # 2 dashes
        $this->assertTrue($this->validator->validate('http://jens-andre-koch.de'));
    }

    public function testMethod_getErrorMessage()
    {
        $this->assertTrue(is_string($this->validator->getErrorMessage()));
    }

    public function testMethod_getValidationHint()
    {
        $this->assertTrue(is_string($this->validator->getValidationHint()));
    }
}
?>