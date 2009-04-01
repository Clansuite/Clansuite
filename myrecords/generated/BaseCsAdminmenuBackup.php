<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCsAdminmenuBackup extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('adminmenu_backup');
    $this->hasColumn('id', 'integer', 1, array('type' => 'integer', 'length' => 1, 'unsigned' => 1, 'primary' => true));
    $this->hasColumn('parent', 'integer', 1, array('type' => 'integer', 'length' => 1, 'unsigned' => 1, 'primary' => true));
    $this->hasColumn('type', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('text', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('href', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('target', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('order', 'integer', 1, array('type' => 'integer', 'length' => 1, 'notnull' => true));
    $this->hasColumn('icon', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('permission', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
