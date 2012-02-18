<?php
class Clansuite_HttpRequest_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_HttpRequest
     */
    protected $request;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $this->request = new Clansuite_HttpRequest;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->request);
    }

    function testMethod_getRequestMethod()
    {
        $this->request->setRequestMethod('BEAVIS');
        $this->assertEqual('BEAVIS', Clansuite_HttpRequest::getRequestMethod());
    }

    function testMethod_setRequestMethod()
    {
        $this->request->setRequestMethod('BUTTHEAD');
        $this->assertEqual('BUTTHEAD', Clansuite_HttpRequest::getRequestMethod());
    }

    function testMethod_isGET()
    {
        $this->request->setRequestMethod('GET');
        $this->assertTrue($this->request->isGet());
    }

    function testMethod_isPOST()
    {
        $this->request->setRequestMethod('POST');
        $this->assertTrue($this->request->isPost());
    }

    function testMethod_isPUT()
    {
        $this->request->setRequestMethod('PUT');
        $this->assertTrue($this->request->isPut());
    }

    function testMethod_isDELETE()
    {
        $this->request->setRequestMethod('DELETE');
        $this->assertTrue($this->request->isDelete());
    }
}
?>
