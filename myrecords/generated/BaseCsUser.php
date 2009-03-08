<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCsUser extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('users');

    $this->hasColumn('user_id', 'integer', 4, array('unsigned' => 1, 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('email', 'string', 150, array('unique' => true, 'fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false, 'unique' => true));
    $this->hasColumn('nick', 'string', 25, array('unique' => true, 'fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('passwordhash', 'string', 40, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('new_passwordhash', 'string', 40, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('salt', 'string', 20, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('new_salt', 'string', 20, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('activation_code', 'string', 255, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('joined', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('timestamp', 'integer', 4, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('disabled', 'integer', 1, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('activated', 'integer', 1, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('status', 'integer', 1, array('unsigned' => 0, 'primary' => false, 'default' => '0', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('country', 'string', 5, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('language', 'string', 12, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('timezone', 'string', 8, array('fixed' => false, 'primary' => false, 'notnull' => false, 'autoincrement' => false));
    $this->hasColumn('theme', 'string', 255, array('fixed' => false, 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
  }

  public function setUp()
  {
    parent::setUp();

    $this->index('user_id', array('fields' => 'user_id'));

    $this->hasMany('CsGroup',       array(      'local' => 'user_id',
                                                'foreign' => 'group_id',
                                                'refClass' => 'CsRelUserGroup'
                                         ));

    $this->hasMany('CsOption',      array(      'local' => 'user_id',
                                                'foreign' => 'option_id',
                                                'refClass' => 'CsRelUserOption'
                                         ));

    $this->hasOne('CsProfile',      array(      'local' => 'user_id',
                                                'foreign' => 'user_id',
                                         ));

    $this->hasMany('CsProfileGuestbook', array( 'local' => 'user_id',
                                                'foreign' => 'user_id',
                                         ));

    $this->hasMany('CsProfileGeneral', array(   'local' => 'user_id',
                                                'foreign' => 'user_id',
                                         ));

    $this->hasMany('CsProfileComputer', array(  'local' => 'user_id',
                                                'foreign' => 'user_id',
                                         ));
    }
}
?>