<?php
// Connection Component Binding
//Doctrine_Manager::getInstance()->bindComponent('CsForumPermissionProfile', 'clansuite');

/**
 * BaseCsForumPermissionProfile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $profile_id
 * @property string $profile_name
 * 
 * @package    Clansuite
 * @subpackage Database
 * @author     Clansuite - just an eSports CMS <vain at clansuite dot com>
 * @version    SVN: $Id: Builder.php 4601 2010-08-28 20:41:25Z vain $
 */
abstract class BaseCsForumPermissionProfile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('cs_forum_permission_profile');
        $this->hasColumn('profile_id', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('profile_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}