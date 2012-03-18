<?php
class Koch_Form_Validators_Ip_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Koch_Form_Validator_Ip
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        $this->validator = new \Koch\Form\Validators\Ip;
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

        # ipv4 - num
        $this->assertTrue($this->validator->validate('127.0.0.1'));

        # ipv4 - num false
        $this->assertFalse($this->validator->validate('127.0.0.1.127'));

        $ipv6 = '2001:0db8:85a3:08d3:1319:8a2e:0370:7344';
        $this->assertTrue($this->validator->validate($ipv6));

        # IDNA URL based on intl extension
        if(function_exists('idn_to_ascii'))
        {
            $this->assertEqual(idn_to_ascii('url-ästhetik.de'),
                        $this->validator->validate('url-ästhetik.de'));
        }

        # does not accept URLs
        $this->assertFalse($this->validator->validate('clansuite.com'));
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