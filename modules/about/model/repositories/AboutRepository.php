<?php
namespace Repositories;
use Doctrine\ORM\EntityRepository;

class AboutRepository extends EntityRepository
{

    /**
     * findByID
     */
    public function findByID( $about_id )
    {
        $q = $this->_em->createQuery('
                    SELECT n
                    FROM Entities\About n
                    WHERE n.id = :about_id
                    ');
        $q->setParameter('about_id', $about_id);
        $result = $q->getArrayResult();
        #\Clansuite_Debug::printR($result);
        return $result[0];
    }


}
?>