<?php

/**
* This class has been auto-generated by the Doctrine ORM Framework
*/
class CsCategoriesTable extends Doctrine_Table
{
    public static function fetchAll()
    {
        return Doctrine_Query::create()
               ->select('c.*')
               ->from('CsCategories c')
               ->fetchArray();
    }
}
?>