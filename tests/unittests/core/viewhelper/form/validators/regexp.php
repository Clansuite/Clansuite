<?php
class Koch_Form_Validator_Regexp_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Koch_Form_Validator_Url
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        $this->validator = new \Koch\Form\Validators\Regexp;
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