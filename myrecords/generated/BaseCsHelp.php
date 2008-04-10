<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCsHelp extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('cs_help');
    $this->hasColumn('help_id', 'integer', 4, array('alltypes' =>  array(  0 => 'integer', ), 'ntype' => 'int(11)', 'unsigned' => 0, 'values' =>  array(), 'primary' => true, 'notnull' => true, 'autoincrement' => true));
    $this->hasColumn('mod', 'string', 255, array('alltypes' =>  array(  0 => 'string', ), 'ntype' => 'varchar(255)', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('sub', 'string', 255, array('alltypes' =>  array(  0 => 'string', ), 'ntype' => 'varchar(255)', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('action', 'string', 255, array('alltypes' =>  array(  0 => 'string', ), 'ntype' => 'varchar(255)', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('helptext', 'string', null, array('alltypes' =>  array(  0 => 'string',   1 => 'clob', ), 'ntype' => 'text', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
    $this->hasColumn('related_links', 'string', null, array('alltypes' =>  array(  0 => 'string',   1 => 'clob', ), 'ntype' => 'text', 'fixed' => false, 'values' =>  array(), 'primary' => false, 'default' => '', 'notnull' => true, 'autoincrement' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}