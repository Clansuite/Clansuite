<?php
namespace Repositories;
use Doctrine\ORM\EntityRepository;

class ForumBoardsRepository extends EntityRepository
{
    /**
     * findBoards
     * will return all Boards exludes Sub-Borads
     */
    public function findBoards()
    {
        $q = $this->_em->createQuery('
                    SELECT b
                    FROM Entities\ForumBoards b
                    ');
        $result = $q->getArrayResult();
        #\Clansuite_Debug::printR($result);
        return $result;
    }

    /**
     * findSubBoards
     * returned all Sub-Boards from given Board-ID
     */
    public function findSubBoards( $bid )
    {
        $q = $this->_em->createQuery('
                    SELECT b
                    FROM Entities\ForumBoards b
                    WHERE b.parent_id = :bid
                    ');
        $q->setParameter('bid', $bid);
        $result = $q->getArrayResult();
        #\Clansuite_Debug::printR($result);

        return $result;
    }


}
?>