<?php
class Clansuite_Formelement_Validator_Range_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Formelement_Validator_Range
     */
    protected $validator;

    public $options;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/range.php';
        $this->validator = new Clansuite_Formelement_Validator_Range;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->validator);
    }

    public function testMethod_setRange()
    {
        $minimum_length = '1';
        $maximum_length = '1980';
        $this->validator->setRange($minimum_length, $maximum_length);

        # string != int
        $this->assertNotIdentical($minimum_length,
                           $this->validator->options['options']['min_range']);

        $this->assertNotIdentical($maximum_length,
                           $this->validator->options['options']['max_range']);

        # string to int
        $this->assertEqual($minimum_length,
                           $this->validator->options['options']['min_range']);

         # string to int
        $this->assertEqual($maximum_length,
                           $this->validator->options['options']['max_range']);

    }

    public function testMethod_processValidationLogic()
    {
        $minimum_length = '1';
        $maximum_length = '1980';
        $this->validator->setRange($minimum_length, $maximum_length);

        $this->assertFalse($this->validator->validate(''));
        $this->assertTrue($this->validator->validate(1));
        $this->assertTrue($this->validator->validate('1'));
        $this->assertTrue($this->validator->validate(true));
        $this->assertFalse($this->validator->validate(0));
        $this->assertFalse($this->validator->validate('0'));
        $this->assertFalse($this->validator->validate(false));

        # strings.. are not in range
        $this->assertFalse($this->validator->validate('Evolution'));
    }

    public function testMethod_getErrorMessage()
    {
        $minimum_length = '1';
        $maximum_length = '1980';
        $this->validator->setRange($minimum_length, $maximum_length);

        $this->assertIdentical('The value is outside the range of 1 <> 1980.',
                $this->validator->getErrorMessage());
    }

    public function testMethod_getValidationHint()
    {
        $minimum_length = '1';
        $maximum_length = '1980';
        $this->validator->setRange($minimum_length, $maximum_length);

        $this->assertIdentical('Please enter a value within the range of 1 <> 1980.',
                $this->validator->getValidationHint());

    }
}
?>