<?php
namespace Entities;

#use Doctrine\ORM\Mapping as ORM; # we are currently not using @ORM annotions

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Repositories\NewsRepository")
 * @Table(name="news")
 */
class News
{
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $news_id;

    /**
     * @Column(type="text")
     */
    protected $news_title;

    /**
     * @Column(type="text")
     */
    protected $news_body;

    /**
     * @Column(type="integer")
     */
    protected $cat_id;

    /**
     * @Column(type="integer")
     */
    protected $user_id;

    /**
     * @Column(type="integer")
     */
    protected $created_at;

    /**
     * @Column(type="integer")
     */
    protected $updated_at;

    /**
     * @Column(type="integer")
     */
    protected $news_status;

    ### Setter and Getter for existing Columns IN THIS OBJECT

    public function getNewsTitle()
    {
        return $this->news_title;
    }

    public function setNewsTitle($news_title)
    {
        $this->news_title = (string) $news_title;
    }

    public function getNewsBody()
    {
        return $this->news_body;
    }

    public function setNewsBody($news_body)
    {
        $this->news_body = (string) $news_body;
    }

    public function getCategory()
    {
        return $this->cat_id;
    }

    public function setCategory($cat_id)
    {
        $this->cat_id = (int) $cat_id;
    }

    public function setAuthor($user_id)
    {
        $this->user_id = (int) $user_id;
    }

    public function getAuthor()
    {
        return $this->user_id;
    }

    public function getNewsStatus()
    {
        return $this->news_status;
    }

    public function setNewsStatus($news_status)
    {
        $this->news_status = (int) $news_status;
    }

   /**
    * ASSOCIATIONS for existing Columns IN OTHER OBJECTS.
    *
    * Order: property first, then accessor method.
    */

     /**
     * Bidirectional - Many News are authored by one user (OWNING SIDE).
     *
     * ONE User 'authors' zero To MANY news articles.
     * And ONE news article is 'authored by' ONE User.
     *
     * The relationships is bidirectional,
     * so inversedBy is included in the ManyToOne annotation.
     *
     * @ManyToOne(targetEntity="Entities\User", inversedBy="news_authored")
     * @JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $news_authored_by;

    public function addAuthor(Entities\User $user)
    {
        $this->news_authored_by = $user->user_id;
    }

    /**
     * ONE News article may have zero to MANY comments (1:n).
     * This a unidirectional relationship, so 'invertedBy' is not used.
     *
     * @OneToMany(targetEntity="Entities\Comment", mappedBy="news")
     * @var Collection
     */
    private $comments;

    public function addComment(Entities\Comment $comment)
    {
        $this->comments[] = $comment;
        $comment->setNews($this);
    }

    /**
     * One News article belongs into one category (1:1).
     *
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="cat_id", referencedColumnName="cat_id")
     */
    private $category;

    public function addCategory(Entities\Category $category)
    {
        $this->cat_id = $category->cat_id;
    }
}
?>