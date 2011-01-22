<?php
namespace Repositories;
use Doctrine\ORM\EntityRepository;

class NewsRepository extends EntityRepository
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

         /*$qb->where('n.status = 1')
            ->andWhere('n.date = :date')
            ->setParameter('date', $date)
            ->orderBy('n.date');
          *
          */

         return $qb->getQuery()->getResult();
    }

    /**
     * fetchLatestNews
     *
     * @param int The number of news to fetch.
     */
    public function fetchLatestNews($numberNews)
    {

        # 12.2.4.1. Partial Object Syntax¶, partial c.{name, image}
        # @link http://www.doctrine-project.org/docs/orm/2.0/en/reference/dql-doctrine-query-language.html
        $query = $this->_em->createQuery('SELECT n, partial u.{nick, user_id}
                                   FROM Entities\News n
                                   LEFT JOIN n.authored u
                                   ORDER BY n.news_id DESC');
        # Note: association via object#n.authored, real LEFT JOIN via table#n.user_id
        # Note: removed limit, because its not working: LIMIT :number_of_news
        # LIMIT is implemented via setMaxResults()
        # $query->setParameter('number_of_news', $numberNews);
        $query->getMaxResults($numberNews);
        $latestnews = $query->getArrayResult();

        # bah, get class from global space ;)
        #\Clansuite_Debug::printR($latestnews);

        return $latestnews;
    }

    /**
     * fetch used News Categories
     *
     * Doctrine_Query to fetch all used News Categories
     */
    public function fetchUsedNewsCategories()
    {
        $q = $this->_em->createQuery('
                        SELECT n.cat_id, COUNT(n.cat_id) sum_news, c.name
                        FROM Entities\News n
                        LEFT JOIN n.category c
                        WHERE c.module_id = 7
                        GROUP BY c.name');
        $r = $q->getArrayResult();
        #\Clansuite_Debug::printR($r);
        return $r;
    }

    /**
     * fetch all News Categories
     */
    public static function fetchAllNewsCategoriesDropDown()
    {
        # fetch news via doctrine query
        $newscategories = Doctrine_Query::create()
                                    ->select('c.cat_id, c.name')
                                    ->from('CsCategories c')
                                    ->where('c.module_id = 7')
                                    ->groupBy('c.name')
                                    ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                    ->execute( array() );
    }

    public function fetchNewsArchiveWidget()
    {
        # fetch all newsentries, ordered by creation date ASCENDING
        $q = $this->_em->createQuery('
                                    SELECT n.news_id, n.created_at
                                    FROM Entities\News n
                                    ORDER BY n.created_at ASC');
        $r = $q->getArrayResult();
        #\Clansuite_Debug::printR($r);
        return $r;
    }
}
?>