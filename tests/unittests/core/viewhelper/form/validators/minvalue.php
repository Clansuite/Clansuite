<?php
/**
 * @todo method chaining tests on all setter methods
 */
class Clansuite_Formelement_Validator_Minvalue_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Formelement_Validator_Minvalue
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/minvalue.php';

        $this->validator = new Clansuite_Formelement_Validator_Minvalue;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->validator);
    }

    public function testMethod_getMinvalue()
    {
        # set property
        $this->validator->minvalue = 1980;

        # getter returns integer
        $this->assertEqual(1980, $this->validator->getMinvalue());

        # getter returns integer not string
        $this->assertNotIdentical('1980', $this->validator->getMinvalue());
    }

    public function testMethod_setMinvalue()
    {
        $this->expectException('InvalidArgumentException',
            'Parameter Maxvalue must be numeric (int|float) and not string.');
        $this->validator->setMinvalue('1980');

        $this->validator->setMinvalue(1980);

        $this->assertEqual(1980, $this->validator->getMinvalue());
    }

    public function testMethod_processValidationLogic()
    {
        /**
         * method processValidationLogic is indirectly tested via calling
         * validate() on the parent class, which then calls processValidationLogic()
         */

        $value = 10; # 20 chars

        # int
        $this->validator->setMinvalue(10);
        $this->assertTrue($this->validator->validate($value));

        # float, too small
        $this->validator->setMinvalue(9.99);
        $this->assertTrue($this->validator->validate($value));

         # float, too big
        $this->validator->setMinvalue(10.01);
        $this->assertFalse($this->validator->validate($value));

        # int, too big
        $this->validator->setMinvalue(11);
        $this->assertFalse($this->validator->validate($value));
    }

    public function testMethod_getErrorMessage()
    {
        $this->validator->setMinvalue(19);

        $this->assertEqual('The value deceeds (is less than) the minimum value of 19.',
                           $this->validator->getErrorMessage());;
    }

    public function testMethod_getValidationHint()
    {
        $this->validator->setMinvalue(19);

        $this->assertEqual('Please enter a value not deceeding (being less than) the minimum value of 19.',
                           $this->validator->getValidationHint());;
    }
}
?>