<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCsShoutbox extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('cs_shoutbox');
    $this->hasColumn('id', 'integer', 4, array('alltypes' =>  array(  0 => 'integer', ), 'ntype' => 'int(11) unsigned', 'unsigned' => 1, 'values' =>  array(), 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 100, array('alltypes' =>  array(  0 => 'string', ), 'ntype' => 'varchar(100)', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('mail', 'string', 100, array('alltypes' =>  array(  0 => 'string', ), 'ntype' => 'varchar(100)', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('msg', 'string', null, array('alltypes' =>  array(  0 => 'string',   1 => 'clob', ), 'ntype' => 'tinytext', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('time', 'integer', 4, array('alltypes' =>  array(  0 => 'integer', ), 'ntype' => 'int(10) unsigned', 'unsigned' => 1, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('ip', 'string', 15, array('alltypes' =>  array(  0 => 'string', ), 'ntype' => 'varchar(15)', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}