<?php
class Koch_Form_Validator_Maxlength_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Koch_Form_Validator_Maxlength
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        $this->validator = new Koch\Form\Validators\Maxlength;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->validator);
    }

    public function testMethod_getMaxlength()
    {
        $this->validator->maxlength = 1980;

		# getter returns integer
        $this->assertEqual(1980, $this->validator->getMaxlength());

        # getter returns integer not string
        $this->assertNotIdentical('1980', $this->validator->getMaxlength());
    }

    public function testMethod_setMaxlength()
    {
         # setter accepts numeric
         $this->validator->setMaxlength(19);
         $this->assertEqual(19, $this->validator->getMaxlength());

         # setter accepts string
         $this->validator->setMaxlength('19');
         $this->assertEqual(19, $this->validator->getMaxlength());
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

    public function testMethod_getErrorMessage()
    {
        $this->validator->setMaxlength('1980');

        $this->assertEqual('The value exceeds the maxlength of 1980 chars',
                           $this->validator->getErrorMessage());;
    }

    public function testMethod_getValidationHint()
    {
        $this->validator->setMaxlength('1980');

        $this->assertEqual('Please enter 1980 chars at maximum.',
                           $this->validator->getValidationHint());;
    }
}
?>