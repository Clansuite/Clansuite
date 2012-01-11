<?php
class Clansuite_Form_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_Form
     */
    protected $form;

    public function markTestIncomplete($msg)
    {
        $this->assertTrue(false, $msg);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        # Test Subject - Clansuite_Form
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/form.core.php';
        $this->form = new Clansuite_Form('TestForm');

        # setAction requires Clansuite_Router for URL building
        require_once TESTSUBJECT_DIR . 'core/router.core.php';
        # url building needs this
        require_once TESTSUBJECT_DIR . 'core/functions.core.php';

        # addElement() needs Clansuite_Formelement
        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/formelement.core.php';

        require_once TESTSUBJECT_DIR . 'core/viewhelper/form/formdecorator.core.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->form);
    }

    public function testSetMethod()
    {
        $this->form->setMethod('POST');

        # via getter not uppercased
        $this->assertNotEqual('POST', $this->form->getMethod());
        # via getter lowercased
        $this->assertEqual('post', $this->form->getMethod());
        # via property
        $this->assertEqual('post', $this->form->method);
    }

    public function testGetMethod()
    {
        $this->form->setMethod('GET');

        # via getter lowercased
        $this->assertEqual('get', $this->form->getMethod());
    }

    public function testSetAction()
    {
        # set internal url - rebuilds the external url via router
        $this->form->setAction('/module/action');
        $this->assertEqual( WWW_ROOT . 'index.php?mod=module&amp;sub=action', $this->form->getAction());

        # set external url
        $this->form->setAction('index.php?mod=news&action=show');
        $this->assertEqual( WWW_ROOT . 'index.php?mod=index.php%3Fmod%3Dnews%26action%3Dshow', $this->form->getAction());
    }

    public function testGetAction()
    {
        $this->form->setAction('index.php?mod=news&action=show');

        # via getter - qualified url
        $this->assertEqual( WWW_ROOT . 'index.php?mod=index.php%3Fmod%3Dnews%26action%3Dshow', $this->form->getAction());
    }

    public function testGetAutocomplete()
    {
        $this->form->setAutocomplete(false);

        # via getter
        $this->assertEqual('off', $this->form->getAutocomplete());
    }

    public function testSetAutocomplete()
    {
        $this->form->setAutocomplete(false);

        # via getter
        $this->assertEqual('off', $this->form->getAutocomplete());

        $this->form->setAutocomplete(true);

        # via getter
        $this->assertEqual('on', $this->form->getAutocomplete());
    }

    public function testGetNoValidation()
    {
        $this->form->setNoValidation(true);

        # via getter
        $this->assertEqual('novalidate', $this->form->getNoValidation());
    }

    public function testSetNoValidation()
    {
        $this->form->setNoValidation(false);

        # via getter - returns empty string
        $this->assertEqual('', $this->form->getNoValidation());

        $this->form->setNoValidation(true);

        # via getter - returns string
        $this->assertEqual('novalidate', $this->form->getNoValidation());
    }

    public function testGetAttribute()
    {
        $this->form->setAttribute('myAttribute', true);

        # via getter - returns string
        $this->assertEqual(true, $this->form->getAttribute('myAttribute'));
    }

    public function testSetAttribute()
    {
        $this->form->setAttribute('myAttribute', true);

        # via getter - returns string
        $this->assertEqual(true, $this->form->getAttribute('myAttribute'));
    }

    public function testSetAttributes()
    {
        $array = array('attr1' => 'val1', 'attr2' => true);

        $this->form->setAttributes($array);

        # via getter - returns string
        $this->assertEqual('val1',  $this->form->getAttribute('attr1'));
        $this->assertEqual(true,  $this->form->getAttribute('attr2'));

        unset($array);

        # @todo $attributes['form'] = array contains ['form']
    }

    public function testCopyObjectProperties()
    {
        # prefilled object
        $from_object_a = new stdClass();
        $from_object_a->attribute_string = 'value_of_attr_a';
        $from_object_a->attribute_int = 9;
        $from_object_a->attribute_bool = true;
        $from_object_a->attribute_array = array('key' => 'value');

        # empty target object
        $to_object_b = new stdClass();

        $this->form->copyObjectProperties($from_object_a, $to_object_b);

        $this->assertIdentical($from_object_a, $to_object_b);
        $this->assertTrue($to_object_b->attribute_string, 'value_of_attr_a');
        $this->assertTrue($to_object_b->attribute_int, 9);
        $this->assertTrue($to_object_b->attribute_bool, true);
        $this->assertTrue($to_object_b->attribute_array['key'], 'value');
    }

    /**
     * @todo Implement testSetID().
     */
    public function testSetID()
    {
        $this->form->setId('identifier1');

        # via getter - returns string
        $this->assertEqual('identifier1', $this->form->getId());
    }

    public function testGetID()
    {
        $this->form->setId('identifier2');

        # via getter - returns string
        $this->assertEqual('identifier2', $this->form->getId());
    }

    public function testSetName()
    {
        $this->form->setName('name1');

        # via getter - returns string
        $this->assertEqual('name1', $this->form->getName());
    }

    public function testGetName()
    {
        $this->form->setName('name2');

        # via getter - returns string
        $this->assertEqual('name2', $this->form->getName());
    }

    public function testSetAcceptCharset()
    {
        $this->form->setAcceptCharset('iso-8859-1');

        # via getter - returns string
        $this->assertEqual('iso-8859-1', $this->form->getAcceptCharset());
    }

    public function testGetAcceptCharset()
    {
        # via getter - returns default value utf-8 as string
        $this->assertEqual('utf-8', $this->form->getAcceptCharset());

        $this->form->setAcceptCharset('iso-8859-1');

        # via getter - returns string
        $this->assertEqual('iso-8859-1', $this->form->getAcceptCharset());
    }

    public function testSetClass()
    {
        $this->form->setClass('cssclassname1');

        # via getter - returns string
        $this->assertEqual('cssclassname1', $this->form->getClass());
    }

    public function testGetClass()
    {
        $this->form->setClass('cssclassname2');

        # via getter - returns string
        $this->assertEqual('cssclassname2', $this->form->getClass());
    }

    public function testSetDescription()
    {
        $this->form->setDescription('description1');

        # via getter - returns string
        $this->assertEqual('description1', $this->form->getDescription());
    }

    public function testGetDescription()
    {
        $this->form->setDescription('description2');

        # via getter - returns string
        $this->assertEqual('description2', $this->form->getDescription());
    }

    public function testSetHeading()
    {
        $this->form->setHeading('heading2');

        # via getter - returns string
        $this->assertEqual('heading2', $this->form->getHeading());
    }

    public function testGetHeading()
    {
        $this->form->setHeading('heading2');

        # via getter - returns string
        $this->assertEqual('heading2', $this->form->getHeading());
    }

    public function testGetEncoding()
    {
        # via getter - returns default value as string
        $this->assertEqual('multipart/form-data', $this->form->getEncoding());

        $this->form->setEncoding('text/plain');

        # via getter - returns string
        $this->assertEqual('text/plain', $this->form->getEncoding());
    }

    public function testSetLegend()
    {
        $this->form->setLegend('legend-set');

        # via getter - returns string
        $this->assertEqual('legend-set', $this->form->getLegend());
    }

    public function testGetLegend()
    {
        $this->form->setLegend('legend-get');

        # via getter - returns string
        $this->assertEqual('legend-get', $this->form->getLegend());
    }

    public function testSetEncoding()
    {
        $this->form->setEncoding('text/plain');

        # via getter - returns string
        $this->assertEqual('text/plain', $this->form->getEncoding());
    }

    public function testGetFormelements()
    {
       # via getter - returns inital empty array
       $this->assertEqual(array(), $this->form->getFormelements()); ;
    }

    public function testSetFormelements()
    {
        $formelements = array();
        $formelements[] = $this->form->addElement('buttonbar');
        $formelements[] = $this->form->addElement('textarea');

        $formelements_from_testobject = $this->form->getFormelements();
        $this->assertFalse( empty($formelements_from_testobject) );
        $this->assertIdentical($formelements, $this->form->getFormelements());
    }

    public function testFormHasErrors()
    {
        $this->assertFalse($this->form->FormHasErrors());
    }

    public function testregisterDefaultFormelementDecorators()
    {
        $this->form->addElement('textarea');
        $formelements = $this->form->getFormelements();
        $textarea_formelement = $formelements['0'];

        $this->form->registerDefaultFormelementDecorators($textarea_formelement);

        $formelement_decorators = $textarea_formelement->getDecorators();

        $this->assertFalse(empty($formelement_decorators));
        $this->assertTrue(is_object($formelement_decorators['label']));
        $this->assertTrue(is_object($formelement_decorators['description']));
        $this->assertTrue(is_object($formelement_decorators['div']));
    }

    public function testRenderAllFormelements()
    {
        $this->form->addElement('textarea');

        $formelements_html_expected = "\n<div class=\"formline\"><textarea id=\"textarea-formelement-0\"></textarea></div>\n";

        $formelements_html = $this->form->renderAllFormelements();
        $this->assertFalse(empty($formelements_html));
        $this->assertEqual($formelements_html, $formelements_html_expected);
    }

    public function testuseDefaultFormDecorators_disable_via_constructor()
    {
        $form = new Clansuite_Form(array('useDefaultFormDecorators' => true));
        $decorators = $form->getDecorators();
        $this->assertEqual(array(), $decorators);
        unset($form);
    }

    public function testuseDefaultFormDecorators_method_true()
    {
        $this->form->useDefaultFormDecorators(true);

        $this->form->registerDefaultFormDecorators();
        $default_form_decorators = $this->form->getDecorators();
        $this->assertFalse(empty($default_form_decorators));
        $this->assertTrue(is_object($default_form_decorators['form']));
        $this->assertTrue(is_a($default_form_decorators['form'], 'Clansuite_Form_Decorator'));
    }

    public function testregisterDefaultFormDecorators()
    {
        $this->form->registerDefaultFormDecorators();
        $default_form_decorators = $this->form->getDecorators();
        $this->assertFalse(empty($default_form_decorators));
        $this->assertTrue(is_object($default_form_decorators['form']));
        $this->assertTrue(is_a($default_form_decorators['form'], 'Clansuite_Form_Decorator'));
    }

    public function testremoveDecorator()
    {
        $this->form->registerDefaultFormDecorators();
        $this->form->removeDecorator('form');
        $default_form_decorators = $this->form->getDecorators();
        $this->assertFalse(array_key_exists('form', $default_form_decorators));
    }

    public function testgetDecorator()
    {
        $this->form->registerDefaultFormDecorators();
        $default_form_decorators = $this->form->getDecorators();
        $this->assertTrue(array_key_exists('form', $default_form_decorators));
        $this->assertTrue($this->form->getDecorator('form'));
    }

    public function testgetDecorator_exception_notfound()
    {
        $this->expectException(
            'Clansuite_Exception',
            'The Formdecorator "not-existing-formdecorator" was not found.'
        );

        $this->form->getDecorator('not-existing-formdecorator');
    }

    public function testRender()
    {
        $this->form->addElement('textarea');

        $html = $this->form->render();
        $this->assertFalse(empty($html));
        $this->assertTrue(strpos($html, '<form'));
        $this->assertTrue(strpos($html, '<textarea id="textarea-formelement-0">'));
        $this->assertTrue(strpos($html, '</form>'));
    }

    public function test__toString()
    {
        $this->form->addElement('textarea');

        ob_start();
        print $this->form;
        $html = ob_get_clean();

        $this->assertFalse(empty($html));
        $this->assertTrue(strpos($html, '<form'));
        $this->assertTrue(strpos($html, '<textarea id="textarea-formelement-0">'));
        $this->assertTrue(strpos($html, '</form>'));
    }

    public function testAddElement()
    {
        $this->form->addElement('text');

        #$this->form->getElementByPosition('0');
        $formelements_array = $this->form->getFormelements();

        $formelement = new Clansuite_Formelement_Text;
        $formelement->setID('text-formelement-0');

        $this->assertIdentical($formelement, $formelements_array[0]);
    }

    public function testAddElement_withSettingAttributes()
    {
        # test element
        $attributes = array('class' => 'myFormelementClass',
                            'maxlength' => '20',
                            'label' => 'myFormelementLabel',
                            'id' => 'text-formelement-0');

        $this->form->addElement('text', $attributes);
        $formelement = $this->form->getElementByPosition('0');

        $this->assertEqual($attributes['class'], $formelement->class);
        $this->assertEqual($attributes['maxlength'], $formelement->maxlength);
        $this->assertEqual($attributes['label'], $formelement->label);
        $this->assertEqual($attributes['id'], $formelement->id);
    }

    /**
     * @covers regenerateFormelementIdentifiers
     */
    public function testAddElement_ToCertainPosition()
    {
        # PREPARE:
        # this will take position 0
        $this->form->addElement('file');
        # this will take position 1
        $this->form->addElement('captcha');

        # TEST:
        # this will take position 0 + reorders the array
        $this->form->addElement('text', null, 0);

        $array = array();
        $array[] = new Clansuite_Formelement_Text;    # 0 - Text
        $array[] = new Clansuite_Formelement_File;    # 1 - File
        $array[] = new Clansuite_Formelement_Captcha; # 2 - Captcha

        # manually reapply formelement identifiers
        $array['0']->setID('text-formelement-0');
        $array['1']->setID('file-formelement-1');
        $array['2']->setID('captcha-formelement-2');

        $this->assertIdentical($array, $this->form->getFormelements());
    }

    public function testAddElement_switchEncodingWhenUsingFormelementFile()
    {
        $this->form->addElement('file');

        $this->assertContainsString('enctype="multipart/form-data"', $this->form->render());
    }

    public function testregenerateFormelementIdentifiers()
    {
        # PREPARE:
        # this will take position 0
        $this->form->addElement('file');
        # this will take position 1
        $this->form->addElement('captcha');

        # TEST:
        # this will take position 0 + reorders the array
        $this->form->addElement('text', null, 0);

        $array = array();
        $array[] = new Clansuite_Formelement_Text;    # 0 - Text
        $array[] = new Clansuite_Formelement_File;    # 1 - File
        $array[] = new Clansuite_Formelement_Captcha; # 2 - Captcha

        # manually reapply formelement identifiers
        $array['0']->setID('text-formelement-0');
        $array['1']->setID('file-formelement-1');
        $array['2']->setID('captcha-formelement-2');

        $this->assertIdentical($array, $this->form->getFormelements());
    }

    public function testDelElementByName()
    {
        $this->form->addElement('textarea')->setName('myTextareaElement');
        $this->form->delElementByName('myTextareaElement');

        $this->assertNull($this->form->getElementByName('myTextareaElement'));
    }

    public function testGetElementByPosition()
    {
        $this->form->addElement('text');

        $formelements_array = $this->form->getFormelements();

        $this->assertIdentical( $formelements_array['0'], $this->form->getElementByPosition(0));
    }

    public function testGetElementByName()
    {
        $this->form->addElement('button')->setName('myButton1');

        $formelement_object = $this->form->getElementByName('myButton1');
        $this->assertIdentical('myButton1', $formelement_object->getName());
    }

        public function testGetElement_ByName_or_ByPosition_or_LastElement()
    {
        $this->form->addElement('button')->setName('myButton1');

        # ByName
        $formelement_object = $this->form->getElement('myButton1');
        $this->assertIdentical('myButton1', $formelement_object->getName());

        # ByPosition
        $formelement_object = $this->form->getElement('0');
        $this->assertIdentical('myButton1', $formelement_object->getName());

        # Default Value null as param
        $formelement_object = $this->form->getElement();
        $this->assertIdentical('myButton1', $formelement_object->getName());
    }

    public function testFormelementFactory()
    {
        $formelement_object = $this->form->formelementFactory('text');

        $this->assertIdentical(new Clansuite_Formelement_Text, $formelement_object);
    }

    public function testsetValues_DataArrayPassedToMethod()
    {
        # create multiselect "Snacks" with three options
        $this->form->addElement('multiselect')->setName('Snacks')->setOptions(
            array('cola' => 'Cola', 'popcorn' => 'Popcorn', 'peanuts' => 'Peanuts')
        );

        # two options were selected (array is incomming via post)
        $data = array('snacks' => array('cola', 'popcorn'));

        $this->form->setValues($data);

        $snacks_array = $this->form->getElementByName('Snacks')->getValue();
        $this->assertIdentical(count($snacks_array), 2);
        $this->assertIdentical($snacks_array[0], 'cola');
        $this->assertIdentical($snacks_array[1], 'popcorn');
    }

    public function testgetValues()
    {
        $this->form->addElement('textarea', array('value' => 'Some Text Inside The First Textarea'));
        $this->form->addElement('textarea', array('value' => 'More Text Inside The Second Textarea'));

        $values = $this->form->getValues();

        $this->assertTrue(is_array($values));
        $this->assertIdentical(count($values), 2);

        $expected_values = array (
            'textarea-formelement-0' => 'Some Text Inside The First Textarea',
            'textarea-formelement-1' => 'More Text Inside The Second Textarea'
        );

        $this->assertIdentical($values, $expected_values);
    }

    public function testSetFormelementDecorator_formelementPositionNull()
    {
        $this->form->addElement('textarea');
        $this->form->setFormelementDecorator('label', null);

        $formelements = $this->form->getFormelements();
        $textarea_element = $formelements[0];
        $decorators = $textarea_element->formelementdecorators;

        $this->assertTrue(is_array($decorators));
        $this->assertEqual(1, count($decorators));
        $this->assertTrue(isset($decorators['label']));
    }

    public function testAddFormelementDecorator()
    {
        $this->form->addElement('textarea');
        $this->form->addElement('multiselect');
        $this->form->addFormelementDecorator('label', 1);

        $formelements = $this->form->getFormelements();
        $textarea_element = $formelements[1];
        $decorators = $textarea_element->formelementdecorators;

        $this->assertTrue(is_array($decorators));
        $this->assertEqual(1, count($decorators));
        $this->assertTrue(isset($decorators['label']));
    }

    public function testSetDecorator()
    {
        $this->form->setDecorator('label');

        $decorators = $this->form->getDecorators();

        $this->assertTrue(is_array($decorators));
        $this->assertEqual(1, count($decorators));
        $this->assertTrue(isset($decorators['label']));
    }

    public function testAddDecorator()
    {
        $this->form->addDecorator('label');

        $decorators = $this->form->getDecorators();

        $this->assertTrue(is_array($decorators));
        $this->assertEqual(1, count($decorators));
        $this->assertTrue(isset($decorators['label']));
    }

    public function testGetDecorators()
    {
        $this->form->setDecorator('label');
        $decorators = $this->form->getDecorators();

        $this->assertTrue(is_array($decorators));
        $this->assertEqual(1, count($decorators));
    }

    public function testDecoratorFactory()
    {
        $form_decorator_object = $this->form->DecoratorFactory('label');

        $this->assertIdentical(new Clansuite_Form_Decorator_Label, $form_decorator_object);
    }

    public function testsetDecoratorAttributesArray()
    {
        $attributes = array('attribute1' => 'value1');
        $this->form->setDecoratorAttributesArray($attributes);

        $this->assertIdentical($attributes, $this->form->getDecoratorAttributesArray());
    }

    public function testgetDecoratorAttributesArray()
    {
        $attributes = array('attribute2' => 'value2');
        $this->form->setDecoratorAttributesArray($attributes);

        $this->assertIdentical($attributes, $this->form->getDecoratorAttributesArray());
    }

    public function testapplyDecoratorAttributes()
    {
        # decorator type will be form
        # this is just another way of setting attributes to the form itself
        $attributes = array('form' =>
            array('form' => # this is Clansuite_Form_Decorator_Form
                array('heading' => 'This is the Heading of the form.',
                      'description' => 'This is a form description text.')
        ));

        $this->form->setDecoratorAttributesArray($attributes);

        $this->form->registerDefaultFormDecorators();

        $this->form->applyDecoratorAttributes();

        $form_decorator_form = $this->form->getDecorator('form');

        $this->assertIdentical('This is the Heading of the form.', $form_decorator_form->heading);
        $this->assertIdentical('This is a form description text.', $form_decorator_form->description);
    }

    /*public function testAddValidator()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }*/

    public function testValidateForm_false()
    {
        $this->form->addElement('textarea')
                ->setName('Textarea-Validate-Test')
                ->setRequired()
                ->setRules('required, string, maxlength=100');

        # no value set, but required
        $this->assertFalse($this->form->validateForm());

        # set a value, exceeding maxlength
        $element = $this->form->getElementByName('Textarea-Validate-Test');
        $element->setValue('0123456789-0123456789'); # 21 chars

        # max length exceeded
        $this->assertFalse($this->form->validateForm());
    }

    public function testValidateForm_true()
    {
        $this->form->addElement('textarea')
                ->setName('Textarea-Validate-Test')
                ->setRequired()
                ->setRules('required, string, maxlength=20');

        # set value, length ok
        $element = $this->form->getElementByName('Textarea-Validate-Test');
        $element->setValue('01234567890123456789'); # 20 chars

        $this->assertTrue($this->form->validateForm());
    }

    public function testsetRequired()
    {
        $this->form->addElement('textarea')->setName('Textarea-A')->setRequired();

        $formelement = $this->form->getElementByName('Textarea-A');
        $this->assertTrue($formelement->required);
        $this->assertTrue($formelement->isRequired());
    }

    public function testIsRequired()
    {
        $this->form->addElement('textarea')->setName('Textarea-A')->setRequired();
        $formelement = $this->form->getElementByName('Textarea-A');
        $this->assertTrue($formelement->required);
        $this->assertTrue($formelement->isRequired());
    }

    public function testsetErrorState()
    {
        $this->form->setErrorState(true);
        $this->assertTrue($this->form->error);
        $this->assertTrue($this->form->getErrorState());
    }

    public function testgetErrorState()
    {
        $this->form->setErrorState(true);
        $this->assertTrue($this->form->error);
        $this->assertTrue($this->form->getErrorState());

        $this->form->setErrorState(false);
        $this->assertFalse($this->form->error);
        $this->assertFalse($this->form->getErrorState());
    }

    public function testaddErrorMessage()
    {
        $message = 'message text';
        $this->form->addErrorMessage($message);
        $errormessages = $this->form->getErrorMessages();
        $this->assertIdentical($message, $errormessages['0']);
    }

    public function testaddErrorMessages()
    {
        $set1 = array('aaa', 'bbb', 'ccc');
        $this->form->addErrorMessages($set1);
        $this->assertIdentical($set1, $this->form->getErrorMessages());
    }

    public function testaddErrorMessages_OverwriteMessages()
    {
        $set1 = array('aaa', 'bbb', 'ccc');
        $set2 = array('ddd', 'eee');
        $this->form->addErrorMessages($set1);
        $this->assertIdentical($set1, $this->form->getErrorMessages());
        $this->form->addErrorMessages($set2);
        $this->assertIdentical($set2, $this->form->getErrorMessages());
    }

    public function testresetErrorMessages()
    {
        $set1 = array('aaa', 'bbb', 'ccc');
        $this->form->addErrorMessages($set1);
        $this->form->resetErrorMessages();
        $messages = $this->form->getErrorMessages();
        $this->assertTrue(empty($messages));
    }

    public function testgetErrorMessages()
    {
        $set1 = array('aaa', 'bbb', 'ccc');
        $this->form->addErrorMessages($set1);
        $this->assertIdentical($set1, $this->form->getErrorMessages());
    }

    public function test__set()
    {
        # this will call __set
        $this->form->method = 'methodname';

        $this->assertEqual('methodname', $this->form->getMethod());
    }

    public function test__get()
    {
        # this will call __set
        $this->form->method = 'methodname';

        # this will call __get
        $this->assertEqual('methodname', $this->form->method);
    }
}
?>