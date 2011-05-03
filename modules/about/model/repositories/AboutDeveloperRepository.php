<?php
namespace Repositories;
use Doctrine\ORM\EntityRepository;

class AboutDeveloperRepository extends EntityRepository
{

    /**
     * findByDeveloper
     */
    public function findByDeveloper( $dev_id )
    {
        $q = $this->_em->createQuery('
                    SELECT n
                    FROM Entities\AboutDeveloper n
                    WHERE n.developer = :dev_id
                    ');
        $q->setParameter('dev_id', $dev_id);
        $result = $q->getArrayResult();
        #\Clansuite_Debug::printR($result);
        return $result;
    }

}
?>