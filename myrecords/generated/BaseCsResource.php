<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCsResource extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 200, array('primary' => true));
        $this->hasColumn('application_id', 'integer', 8, array());
    }

    public function setUp()
    {
        
        /**
         * Resource (1:n) Permission (via RelResourcePermission)
         */
        $this->hasMany('CsPermission',
                       array('local' => 'resource',
                             'foreign' => 'permission',
                             'refClass' => 'CsRelResourcePermission'));
    }
}