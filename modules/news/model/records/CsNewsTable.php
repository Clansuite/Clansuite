    /**
    * construct
    * Introducing Named Queries
    */
    public function construct()
    {
        # var_dump($this->doctrine_em->getRepository($this->getRepositoryName())->findAll());

        // Get all news elements
        $this->addNamedQuery(
            'fetchAllNews', Doctrine_Query::create()
                                    ->select('n.*,
                                              u.nick, u.user_id, u.email, u.country,
                                              c.name, c.image, c.icon, c.color,
                                              nc.*,
                                              ncu.nick, ncu.email, ncu.country')
                                    ->addSelect('(SELECT COUNT(cc.comment_id) FROM CsNews ns LEFTJOIN ns.CsComments cc WHERE ns.news_id = n.news_id) as nr_news_comments')
                                    ->from('CsNews n')
                                    ->leftJoin('n.CsUsers u')
                                    ->leftJoin('n.CsCategories c')
                                    ->leftJoin('n.CsComments nc')
                                    ->leftJoin('nc.CsUsers ncu')
                                    ->orderBy('n.news_id DESC, n.created_at DESC')
                             );
    }


    /**
     * fetchAllNews
     *
     * Doctrine_Query to fetch News by Category
     * wrapped into the Doctrine_Pager
     *
     * For Pager Chapter in Doctrine Manual
     * @link http://www.phpdoctrine.org/documentation/manual/0_10?one-page#utilities
     */
    public static function fetchAllNews($currentPage, $resultsPerPage, $admin = null, $sortorder = null)
    {
        if($sortorder == null)
        {
            $sortorder = 'n.news_id DESC, n.created_at DESC';
        }

        # create public or administration link
        if($admin == null)
        {
            # link for public area
            $link =  '?mod=news&amp;action=show&amp;page={%page}';
        }
        else
        {
            # link for administration area
            $link = '?mod=news&amp;sub=admin&amp;action=show&amp;page={%page}';
        }

        # Creating Pager Object with a Query Object inside
        $pager_layout= new Doctrine_Pager_Layout(
                                new Doctrine_Pager(
                                    Doctrine_Query::create()
                                            ->select('n.*,
                                                      u.nick, u.user_id, u.email, u.country,
                                                      c.name, c.image, c.icon, c.color,
                                                      nc.*,
                                                      ncu.nick, ncu.email, ncu.country')
                                            ->addSelect('(SELECT COUNT(cc.comment_id) FROM CsNews ns LEFTJOIN ns.CsComments cc WHERE ns.news_id = n.news_id) as nr_news_comments')
                                            ->from('CsNews n')
                                            ->leftJoin('n.CsUsers u')
                                            ->leftJoin('n.CsCategories c')
                                            ->leftJoin('n.CsComments nc')
                                            ->leftJoin('nc.CsUsers ncu')
                                            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                            ->orderby($sortorder),
                                         # the following two values are the (sql) limit  ?,? =
                                         $currentPage, # Current page of request
                                         $resultsPerPage # (Optional) Number of results per page Default is 25
                                     ),
                                 new Doctrine_Pager_Range_Sliding(array(
                                     'chunk' => 5
                                    )),
                                    $link
                                 );

        # Sets Layout of the pager links
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');

        # Sets Layout of the pager
        $pager_layout->setSelectedTemplate('[{%page}]');

        # Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        # fetching the paginated news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        # put things in an array-box for delivery of multiple things with one return stmt
        return compact('news', 'pager', 'pager_layout');
    }

    /**
     * fetchNewsByCategory
     *
     * Doctrine_Query to fetch News by Category
     */
    public static function fetchNewsByCategory($category, $currentPage, $resultsPerPage, $admin = null, $sortorder = null)
    {
        if($sortorder == null)
        {
            $sortorder = 'n.news_id DESC, n.created_at DESC';
        }

        # create public or administration link
        if($admin == null)
        {
            # link for public area
            $link = '?mod=news&amp;action=show&amp;page={%page}&amp;cat='.$category;
        }
        else
        {
            # link for administration area
            $link = '?mod=news&amp;sub=admin&amp;action=show&amp;page={%page}&amp;cat='.$category;
        }

        # Creating Pager Object with a Query Object inside
        $pager_layout = new Doctrine_Pager_Layout(
                                new Doctrine_Pager(
                                    Doctrine_Query::create()
                                            ->select('n.*,
                                                      u.nick, u.user_id, u.email, u.country,
                                                      c.name, c.image, c.icon, c.color,
                                                      nc.*,
                                                      ncu.nick, ncu.email, ncu.country')
                                            ->addSelect('(SELECT COUNT(cc.comment_id) FROM CsNews ns LEFTJOIN ns.CsComments cc WHERE ns.news_id = n.news_id) as nr_news_comments')
                                            ->from('CsNews n')
                                            ->leftJoin('n.CsUsers u')
                                            ->leftJoin('n.CsCategories c')
                                            ->leftJoin('n.CsComments nc')
                                            ->leftJoin('nc.CsUsers ncu')
                                            ->where('n.cat_id = ?', array( $category ) )
                                            ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                            ->orderby($sortorder),
                                         # The following is Limit  ?,? =
                                         $currentPage, # Current page of request
                                         $resultsPerPage # (Optional) Number of results per page Default is 25
                                    ),
                                 new Doctrine_Pager_Range_Sliding(array(
                                     'chunk' => 5
                                    )),
                                 $link
                                 );

        # Set Layout of the pager links
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');

        # Set Layout of the pager
        $pager_layout->setSelectedTemplate('[{%page}]');

        # Displaying pager links with cat added / 2nd parameter (true) surpresses the direct display
        #$pager_layout->display( array('cat' => urlencode($category)), true);

        # Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        # Fetching the paginated news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        # put things in an array-box for delivery multiple things with one return stmt
        return compact('news', 'pager', 'pager_layout');
    }

    /**
    * fetchNewsForFeed
    *
    * Doctrine_Query to fetch News by Category
    */
    public static function fetchNewsForFeed()
    {
        $news = Doctrine_Query::create()
                        ->select('n.*,
                                  u.nick, u.user_id, u.email, u.country,
                                  c.name, c.image, c.icon, c.color,
                                  nc.*,
                                  ncu.nick, ncu.email, ncu.country')
                        ->addSelect('(SELECT COUNT(cc.comment_id) FROM CsNews ns LEFTJOIN ns.CsComments cc WHERE ns.news_id = n.news_id) as nr_news_comments')
                        ->from('CsNews n')
                        ->leftJoin('n.CsUsers u')
                        ->leftJoin('n.CsCategories c')
                        ->leftJoin('n.CsComments nc')
                        ->leftJoin('nc.CsUsers ncu')
                        #->where('c.module_id = 7')
                        ->orderBy('created_at DESC')
                        ->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        ->fetchArray();

        # put things in an array-box for delivery multiple things with one return stmt
        return $news;
    }



    /**
     * fetchNewsForFullArchiv
     *
     * Doctrine_Query to fetch News for Archiv
     */
    public static function fetchNewsForArchiv($startdate, $enddate, $currentPage, $resultsPerPage)
    {
        # Creating Pager Object with a Query Object inside
        $pager_layout= new Doctrine_Pager_Layout(
                                new Doctrine_Pager(
                                    Doctrine_Query::create()
                                            ->select('n.*,
                                                      u.nick, u.user_id, u.email, u.country,
                                                      c.name, c.image, c.icon, c.color,
                                                      nc.*,
                                                      ncu.nick, ncu.email, ncu.country')
                                            ->addSelect('(SELECT COUNT(cc.comment_id) FROM CsNews ns LEFTJOIN ns.CsComments cc WHERE ns.news_id = n.news_id) as nr_news_comments')
                                            ->from('CsNews n')
                                            ->leftJoin('n.CsUsers u')
                                            ->leftJoin('n.CsCategories c')
                                            ->leftJoin('n.CsComments nc')
                                            ->leftJoin('nc.CsUsers ncu')
                                            ->andWhere('n.created_at >= ?', array( $startdate ))
                                            ->andWhere('n.created_at <= ?', array( $enddate ))
                                            #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                            ->orderby('n.created_at'),
                                            # the following two values are the (sql) limit  ?,? =
                                         $currentPage, # Current page of request
                                         $resultsPerPage  # (Optional) Number of results per page Default is 25
                                     ),
                                 new Doctrine_Pager_Range_Sliding(array(
                                     'chunk' => 5
                                    )),
                                 '?mod=news&amp;action=archiv&amp;page={%page}&amp;date='.$startdate
                                 );

        # Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        # Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        # Fetching news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        # put things in an array-box for delivery multiple things with one return stmt
        return compact('news', 'pager', 'pager_layout');
    }

    /**
     * fetchNewsForFullArchiv
     *
     * Doctrine_Query to fetch News for Archiv
     */
    public static function fetchNewsForFullArchiv($sortorder, $startdate, $enddate, $currentPage, $resultsPerPage)
    {
        # Creating Pager Object with a Query Object inside
        $pager_layout= new Doctrine_Pager_Layout(
                                new Doctrine_Pager(
                                    Doctrine_Query::create()
                                            ->select('n.*,
                                                      u.nick, u.user_id, u.email, u.country,
                                                      c.name, c.image, c.icon, c.color,
                                                      nc.*,
                                                      ncu.nick, ncu.email, ncu.country')
                                            ->addSelect('(SELECT COUNT(cc.comment_id) FROM CsNews ns LEFTJOIN ns.CsComments cc WHERE ns.news_id = n.news_id) as nr_news_comments ')
                                            ->from('CsNews n')
                                            ->leftJoin('n.CsUsers u')
                                            ->leftJoin('n.CsCategories c')
                                            ->leftJoin('n.CsComments nc')
                                            ->leftJoin('nc.CsUsers ncu')
                                            ->andWhere('n.created_at >= ?', array( $startdate ))
                                            ->andWhere('n.created_at <= ?', array( $enddate ))
                                            #->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                                            ->orderby($sortorder),
                                         # the following two values are the (sql) limit  ?,? =
                                         $currentPage, # Current page of request
                                         $resultsPerPage  # (Optional) Number of results per page Default is 25
                                     ),
                                 new Doctrine_Pager_Range_Sliding(array(
                                     'chunk' => 5
                                    )),
                                 '?mod=news&amp;action=fullarchiv&amp;page={%page}&amp;date='.$startdate
                                 );
        # Assigning templates for page links creation
        $pager_layout->setTemplate('[<a href="{%url}">{%page}</a>]');
        $pager_layout->setSelectedTemplate('[{%page}]');

        # Retrieving Doctrine_Pager instance
        $pager = $pager_layout->getPager();

        # Fetching news
        $news = $pager->execute(array(), Doctrine::HYDRATE_ARRAY);

        # put things in an array-box for delivery multiple things with one return stmt
        return compact('news', 'pager', 'pager_layout');
    }
