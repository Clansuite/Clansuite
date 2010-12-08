<?php
// Connection Component Binding
//Doctrine_Manager::getInstance()->bindComponent('CsForumBoardsModerator', 'clansuite');

/**
 * BaseCsForumBoardsModerator
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $board_id
 * @property integer $user_id
 * 
 * @package    Clansuite
 * @subpackage Database
 * @author     Clansuite - just an eSports CMS <vain at clansuite dot com>
 * @version    SVN: $Id: Builder.php 4601 2010-08-28 20:41:25Z vain $
 */
abstract class BaseCsForumBoardsModerator extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('cs_forum_boards_moderator');
        $this->hasColumn('board_id', 'integer', 3, array(
             'type' => 'integer',
             'length' => 3,
             'fixed' => false,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}