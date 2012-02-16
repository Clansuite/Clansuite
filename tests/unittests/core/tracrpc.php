<?php
class Clansuite_TracRPC_Test extends Clansuite_UnitTestCase
{
    /**
     * @var Clansuite_TracRPC
     */
    protected $trac;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        require_once TESTSUBJECT_DIR . '/core/tracrpc.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        unset($this->trac);
    }

    public function testMethod_Constructor_setsProperties()
    {
        $this->trac = new Trac_RPC('http://trac.clansuite.com/login/jsonrpc',
                array('username' => 'user',
                      'password' => 'password',
                      'multiCall' => '1',
                      'json_decode' => '1'));

        $this->assertEqual('http://trac.clansuite.com/login/jsonrpc', $this->trac->tracURL);
        $this->assertEqual('user', $this->trac->username);
        $this->assertEqual('password', $this->trac->password);
        $this->assertTrue($this->trac->multiCall);
        $this->asserttrue($this->trac->json_decode);

    }

    /**
     * Not a mock. It's a live request.
     */
    public function testMethod_Constructor_WithoutCredentials()
    {
        $this->expectException(
            new Exception('You are trying an authenticated access without providing username and password.')
        );

        # request to "/login" without credentials
        $this->trac = new Trac_RPC('http://trac.clansuite.com/login/jsonrpc');
        $result = $this->trac->getWikiPage('ClansuiteTeam');
        unset($result);
    }

    /**
     * Not a mock. It's a live request.
     */
    public function testMethod_Constructor_doRequest()
    {
        $this->trac = new Trac_RPC('http://trac.clansuite.com/jsonrpc');
        $result = $this->trac->getWikiPage('ClansuiteTeam');

        $this->assertNotNull($result);
        $this->assertTrue(is_string($result));
        $this->assertContainsString('Maintainer', $result);
        $this->assertContainsString('Former Developers', $result);

        unset($result);
    }
}
?>