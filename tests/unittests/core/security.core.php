<?php
class Clansuite_Security_Test extends Clansuite_UnitTestCase
{
    public function setUp()
    {
        parent::setUp();

        # Test Subject - Clansuite_Security
        require_once TESTSUBJECT_DIR . 'core/security.core.php';
    }

    /**
     * testMethod_generate_salt()
     */
    public function testMethod_generate_salt()
    {
        # generate a salt with length
        $salt = Clansuite_Security::generate_salt(12);

        # ensure $salt is a string
        $this->assertTrue(is_string($salt), true);

        # ensure $salt has correct length
        $this->assertEqual(strlen($salt), 12);
    }

    public function testMethod_generate_hash()
    {
        $hash_md5 = Clansuite_Security::generate_hash('md5', 'admin');

        $this->assertIdentical('21232f297a57a5a743894a0e4a801fc3', $hash_md5);

        $hash_sha1 = Clansuite_Security::generate_hash('sha1', 'admin');

        $this->assertIdentical('d033e22ae348aeb5660fc2140aec35850c4da997', $hash_sha1);
    }

    public function testMethod_build_salted_hash()
    {
        $salted_hash = Clansuite_Security::build_salted_hash('admin', 'md5');

        $this->assertTrue(is_array($salted_hash), true);
     }

    public function testMethod_check_salted_hash()
    {
        # md5('admin'); from form input
        $passwordhash = '21232f297a57a5a743894a0e4a801fc3';
        # expected, from db
        $databasehash = '7ff3adfa18a8ad7f115e90ce2c44a0ec';
        # from db
        $salt = 'Sko5ie';
        $hash_algorithm = 'md5';

        $bool = Clansuite_Security::check_salted_hash( $passwordhash, $databasehash, $salt, $hash_algorithm );

        $this->assertTrue($bool, true);
    }
}
?>