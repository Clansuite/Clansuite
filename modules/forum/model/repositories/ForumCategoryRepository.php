<?php
namespace Repositories;
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
                    FROM Entities\ForumCategory c
                    ');
        $result = $q->getArrayResult();
        #\Clansuite_Debug::printR($result);
        return $result;
    }



}
?>