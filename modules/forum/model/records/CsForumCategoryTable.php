<?php

/**
 * CsForumCategoryTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Clansuite
 * @subpackage Database
 * @author     Clansuite - just an eSports CMS <vain at clansuite dot com>
 * @version    SVN: $Id: Builder.php 4601 2010-08-28 20:41:25Z vain $
 */
class CsForumCategoryTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CsForumCategoryTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CsForumCategory');
    }

    /**
     * fetchAllForumCategories
     *
     * Doctrine_Query to fetch Forums Category
     */
    public static function fetchAllForumCategories()
    {

        $result = Doctrine_Query::create()
                              ->select('c.*')
                              ->from('CsForumCategory c')
                              ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                              ->orderby('c.sort DESC')
                              ->execute( array() );

        return $result;
    }

}