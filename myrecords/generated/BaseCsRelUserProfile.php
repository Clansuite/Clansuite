<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCsRelUserProfile extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('cs_rel_user_profile');
    $this->hasColumn('user_id', 'integer', 4, array('unsigned' => 1, 'primary' => true, 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('profile_id', 'integer', 4, array('unsigned' => 1, 'primary' => true, 'notnull' => true, 'autoincrement' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
