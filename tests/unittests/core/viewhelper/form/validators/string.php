<?php
class Clansuite_Formelement_Validator_String_Test extends Clansuite_UnitTestCase
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
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/string.php';
        $this->validator = new Clansuite_Formelement_Validator_String;
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

        $this->assertTrue($this->validator->validate('string'));

        $this->assertTrue($this->validator->validate(1));
        $this->assertTrue($this->validator->validate(1.01));

        $this->assertFalse($this->validator->validate(true));
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