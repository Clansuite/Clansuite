<?php

namespace Repositories;
use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Query\Mysql\Rand;

class UserRepository extends EntityRepository
{

    public function getLastRegisteredUsers($limit)
    {
        $q = $this->_em->createQueryBuilder();
        $q->select('
                            partial u.{user_id, nick, email, country, joined}
                       ')
            ->from('Entities\User', 'u')
            ->where('u.activated = 1')
            ->orderBy('u.joined', 'DESC')
            ->setMaxResults( $limit );

        $query = $q->getQuery();
        $result = $query->getArrayResult();

        #\Clansuite_Debug::printR( $result );

         return $result;
    }

}
?>