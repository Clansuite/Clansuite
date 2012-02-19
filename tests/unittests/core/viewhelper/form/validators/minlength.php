<?php
/**
 * @todo method chaining tests on all setter methods
 */
class Clansuite_Formelement_Validator_Minlength_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Formelement_Validator_minlength
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

    public function testMethod_getMinlength()
    {
        # set property
        $this->validator->minlength = 1980;

        # getter returns integer
        $this->assertEqual(1980, $this->validator->getMinlength());

        # getter returns integer not string
        $this->assertNotIdentical('1980', $this->validator->getMinlength());
    }

    public function testMethod_setMinlength()
    {
        # set property
        $this->validator->setMinlength('1980');

        $this->assertEqual(1980, $this->validator->getMinlength());

        # property
        $this->assertEqual('1980', $this->validator->minlength);
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

    public function testMethod_getErrorMessage()
    {
        $this->validator->setMinlength('19');

        $this->assertEqual('The value deceeds (is less than) the Minlength of 19 chars.',
                           $this->validator->getErrorMessage());;
    }

    public function testMethod_getValidationHint()
    {
        $this->validator->setMinlength('19');

        $this->assertEqual('Please enter 19 chars at maximum.',
                           $this->validator->getValidationHint());;
    }
}
?>