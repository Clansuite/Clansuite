<?php
class Koch_Form_Validator_Maxvalue_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Koch_Form_Validator_maxvalue
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        $this->validator = new \Koch\Form\Validators\Maxvalue;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->validator);
    }

    public function testMethod_getMaxvalue()
    {
        $this->validator->maxvalue = 1980;

		# getter returns integer
        $this->assertEqual(1980, $this->validator->getMaxvalue());

        # getter returns integer not string
        $this->assertNotIdentical('1980', $this->validator->getMaxvalue());
    }

    public function testMethod_setMaxvalue()
    {
         # setter accepts numeric
         $this->validator->setMaxvalue(19);
         $this->assertEqual(19, $this->validator->getMaxvalue());

         $this->expectException('InvalidArgumentException',
            'Parameter Maxvalue must be numeric (int|float) and not string.');
         $this->validator->setMaxvalue('19');
         $this->assertEqual(19, $this->validator->getMaxvalue());
    }

    public function testMethod_processValidationLogic()
    {
        /**
         * method processValidationLogic is indirectly tested via calling
         * validate() on the parent class, which then calls processValidationLogic()
         */

        $this->validator->setMaxvalue(19);
        $this->assertTrue($this->validator->validate(19));

        $this->validator->setMaxvalue(19);
        $this->assertFalse($this->validator->validate(20));

        $this->validator->setMaxvalue(19);
        $this->assertTrue($this->validator->validate(0));

        $this->expectException('InvalidArgumentException',
            'Parameter Maxvalue must be numeric (int|float) and not string.');
        $this->validator->setMaxvalue('19');
        $this->assertTrue($this->validator->validate(19));

        $this->validator->setMaxvalue(20);
        $this->assertTrue($this->validator->validate(20));

        $this->validator->setMaxvalue(21);
        $this->assertTrue($this->validator->validate(21));
    }

    public function testMethod_getErrorMessage()
    {
        $this->validator->setMaxvalue(1980);

        $this->assertEqual('The value exceeds the maximum value of 1980.',
                           $this->validator->getErrorMessage());;
    }

    public function testMethod_getValidationHint()
    {
        $this->validator->setmaxvalue(1980);

        $this->assertEqual('The value must be smaller than 1980.',
                           $this->validator->getValidationHint());;
    }
}
?>