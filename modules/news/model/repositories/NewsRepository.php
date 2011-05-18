<?php
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

namespace Repositories;
use Doctrine\ORM\EntityRepository;
use DoctrineExtensions\Paginate\Paginate;

class NewsRepository extends EntityRepository
{
    public function findAllNews($currentPage, $resultsPerPage)
    {
        $result = $aResult = array();

        $q = $this->_em->createQueryBuilder();
        $q->select('
                            n,
                            partial u.{user_id, nick, email, country},
                            partial c.{cat_id, name, description, image, icon, color},
                            nc,
                            partial ncu.{user_id, nick, email, country}
                        ');
        $q->from( 'Entities\News', 'n' );
        $q->leftJoin( 'n.news_authored_by', 'u' );
        $q->leftJoin( 'n.category', 'c' );
        $q->leftJoin( 'n.comments', 'nc' );
        $q->leftJoin( 'nc.comment_authored_by', 'ncu' );
        $q->where( 'c.module_id = 7' );
        $q->orderBy( 'n.news_id', 'ASC' );

        $query = $q->getQuery();
        $result = $query->getArrayResult();

        #$count = Paginate::getTotalQueryResults($query);
        #$paginateQuery = Paginate::getPaginateQuery($query, 0, $resultsPerPage);
        #$result = $paginateQuery->getArrayResult();
        #\Clansuite_Debug::printR( $result );

        foreach( $result as $row )
        {
            if( count( $row['comments'] >0 )) {
                $row['nr_comments'] = count( $row['comments']);
            } else {
                $row['nr_comments'] = 0;
            }
            $aResult[] = $row;
        }

        #\Clansuite_Debug::printR( $aResult );

         return $aResult;
    }

    public function findOneNews($news_id)
    {
        $result = $aResult = array();

        $q = $this->_em->createQuery('
                    SELECT n,
                           partial u.{user_id, nick, email, country},
                           partial c.{cat_id, name, description, image, icon, color},
                           nc,
                           partial ncu.{user_id, nick, email, country}
                    FROM Entities\News n
                    LEFT JOIN n.news_authored_by u
                    LEFT JOIN n.category c
                    LEFT JOIN n.comments nc
                    LEFT JOIN nc.comment_authored_by ncu
                    WHERE c.module_id = 7
                    AND n.news_id = :news_id');
        $q->setParameter('news_id', $news_id);

        $result = $q->getArrayResult();

        foreach( $result as $row )
        {
            if( count( $row['comments'] >0 )) {
                $row['nr_comments'] = count( $row['comments']);
            } else {
                $row['nr_comments'] = 0;
            }
            $aResult[] = $row;
        }

        #\Clansuite_Debug::printR( $aResult );

         return $aResult[0];
    }

    public function findPublishNews($news_id)
    {
        $q = $this->_em->createQuery('
                    SELECT n,
                           partial u.{user_id, nick},
                           partial c.{cat_id, image}
                    FROM Entities\News n
                    LEFT JOIN n.news_authored_by u
                    LEFT JOIN n.category c
                    WHERE n.news_id = :news_id
                        ');
        $q->setParameter('news_id', $news_id);

        $result = $q->getArrayResult();
        #\Clansuite_Debug::printR( $result );

         return $result[0];
    }

    /**
     * fetchSingleNews
     *
     * Doctrine_Query to fetch News by Category
     */
    public function fetchSingleNews($news_id)
    {
        $q = $this->_em->createQuery('
                    SELECT n,
                           partial u.{user_id, nick, email, country},
                           partial c.{cat_id, name, description, image, icon, color},
                           nc,
                           partial ncu.{user_id, nick, email, country}
                    FROM Entities\News n
                    LEFT JOIN n.news_authored_by u
                    LEFT JOIN n.category c
                    LEFT JOIN n.comments nc
                    LEFT JOIN nc.comment_authored_by ncu
                    WHERE c.module_id = 7
                    AND n.news_id = :news_id');
        $q->setParameter('news_id', $news_id);
        #$r = $q->getScalarResult();
        $r = $q->getArrayResult();
        #\Clansuite_Debug::printR($r);
        return $r['0'];
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
        $query = $this->_em->createQuery('
                           SELECT n,
                                  partial u.{nick, user_id},
                                  partial c.{cat_id, name, description, image, icon, color}
                           FROM Entities\News n
                           LEFT JOIN n.news_authored_by u
                           LEFT JOIN n.category c
                           WHERE c.module_id = 7
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
    public function fetchAllNewsCategoriesDropDown()
    {
        $q = $this->_em->createQuery('
                                    SELECT c.cat_id, c.name
                                    FROM Entities\Category c
                                    WHERE c.module_id = 7
                                    GROUP BY c.name');
        $r = $q->getArrayResult();
        $r = \Clansuite_Functions::map_array_keys_to_values($r, 'cat_id', 'name');
        #\Clansuite_Debug::printR($r);
        return $r;
    }

    public function fetchNewsArchiveWidget()
    {
        # fetch all newsentries, ordered by creation date ASCENDING
        $q = $this->_em->createQuery('
                                    SELECT n.news_id, n.created_at
                                    FROM Entities\News n
                                    ORDER BY n.created_at ASC'
        );
        $r = $q->getArrayResult();
        #\Clansuite_Debug::printR($r);
        return $r;
    }
}
?>