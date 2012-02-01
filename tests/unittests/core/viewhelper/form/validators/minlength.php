<?php
/**
 * @todo method chaining tests on all setter methods
 */
class Clansuite_Formelement_Validator_Minlength_Test extends Clansuite_UnitTestCase
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
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/minlength.php';
        $this->validator = new Clansuite_Formelement_Validator_Minlength;


    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->validator);
    }

    public function testMethod_setMinlength()
    {
        $value = 10;
        $this->validator->setMinlength('10');

        # setter
        $this->assertEqual($value, $this->validator->getMinlength());

        # property
        $this->assertEqual($value, $this->validator->minlength);
    }

    public function testMethod_processValidationLogic()
    {
        /**
         * method processValidationLogic is indirectly tested via calling
         * validate() on the parent class, which then calls processValidationLogic()
         */

        $value = '12345678901234567890'; # 20 chars

        $this->validator->setMinlength('10');
        $this->assertTrue($this->validator->validate($value));

        $this->validator->setMinlength('20');
        $this->assertTrue($this->validator->validate($value));

        # text to short
        $this->validator->setMinlength('21');
        $this->assertFalse($this->validator->validate($value));

        $value = ''; # 0 chars
        $this->validator->setMinlength('0');
        $this->assertTrue($this->validator->validate($value));
    }
}
?>