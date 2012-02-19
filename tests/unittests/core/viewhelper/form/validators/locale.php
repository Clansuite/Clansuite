<?php
class Clansuite_Formelement_Validator_Locale_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Formelement_Validator_Locale
     */
    protected $validator;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/validators/locale.php';
        $this->validator = new Clansuite_Formelement_Validator_Locale;
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

        $this->assertTrue($this->validator->validate('de'));

        $this->assertTrue($this->validator->validate('de-DE'));

        # zh-TW - Chinese Taiwan
        $this->assertTrue($this->validator->validate('zh-Hant-TW'));

        # fr-CA Canadian Frenchy
        $this->assertTrue($this->validator->validate('fr-CA'));

        $this->assertTrue($this->validator->validate('en-US'));

        $this->assertTrue($this->validator->validate('no-tt'));

        $this->assertTrue($this->validator->validate('no-No'));
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