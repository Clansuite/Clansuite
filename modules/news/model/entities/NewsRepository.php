<?php
namespace Repositories;

class NewsRepository extends Doctrine\ORM\EntityRepository
{
    public function findAllNews($arguments)
    {
        /**
         * Tricks :)
         *
         * Instead of writing
         *
         * $qb = $this->_em->createQueryBuilder();
         * $qb->select('n') ->from('Entities\News', 'n')
         *
         * you may simply write
         *
         * $this->createQueryBuilder('n')
         */

         $qb = $this->createQueryBuilder('n');

         /*$qb->where('u.authenticated = 1')
            ->andWhere('u.date = :date')
            ->setParameter('date', $date)
            ->orderBy('u.name');
          *
          */

         return $qb->getQuery()->getResult();
    }

    /**
     * fetchLatestNews
     *
     * Doctrine_Query to fetch Latest News
     */
    public function findAllLatestNews($numberNews)
    {
        #$query = $_em->createQuery('SELECT a FROM Entities\User a');
        #$r = $query->getArrayResult();
        #Clansuite_Debug::printR($r);

        #$validator = new SchemaValidator($_em);
        #$errors = $validator->validateMapping();
        #Clansuite_Debug::printR($errors);
        echo $numberNews;
        # 12.2.4.1. Partial Object Syntax¶, partial c.{name, image}
        # @link http://www.doctrine-project.org/docs/orm/2.0/en/reference/dql-doctrine-query-language.html
        $query = $_em->createQuery('SELECT n, partial u.{nick, user_id}
                                   FROM Entities\News n
                                   LEFT JOIN n.authored u
                                   ORDER BY n.news_id DESC');
        # Note: association via object#n.authored, real LEFT JOIN via table#n.user_id
        # Note: removed limit, because its not working: LIMIT :number_of_news
        # LIMIT is implemented via setMaxResults()
        # $query->setParameter('number_of_news', $numberNews);
        $query->getMaxResults($numberNews);
        $latestnews = $query->getArrayResult();

        Clansuite_Debug::printR($latestnews);

        return $latestnews;
    }
}
?>