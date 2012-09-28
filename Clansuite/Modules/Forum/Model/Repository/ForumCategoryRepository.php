<?php
namespace Repository;
use Doctrine\ORM\EntityRepository;

class ForumCategoryRepository extends EntityRepository
{

    /**
     * findAllCategories
     *
     * Doctrine_Query to fetch Forums Category
     */
    public function findAllCategories()
    {
        $q = $this->_em->createQuery('
                    SELECT c
                    FROM Entity\ForumCategory c
                    ');
        $result = $q->getArrayResult();
        #\\Koch\Debug\Debug::printR($result);

        return $result;
    }

}
