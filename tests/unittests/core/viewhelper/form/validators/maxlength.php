<?php
/**
 * @todo method chaining tests on all setter methods
 */
class Clansuite_Formelement_Validator_Maxlength_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Formelement_Validator_Maxlength
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/maxlength.php';
        $this->validator = new Clansuite_Formelement_Validator_Maxlength;


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

        $value = '12345678901234567890'; # 20 chars

        $this->validator->setMaxlength('19');
        $this->assertFalse($this->validator->validate($value));

        $this->validator->setMaxlength('20');
        $this->assertTrue($this->validator->validate($value));

        $this->validator->setMaxlength('21');
        $this->assertTrue($this->validator->validate($value));

        $value = ''; # 0 chars
        $this->validator->setMaxlength('0');
        $this->assertTrue($this->validator->validate($value));
    }
}
?>