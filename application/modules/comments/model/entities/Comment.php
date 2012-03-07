<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\CommentRepository")
 * @Table(name="news_comments")
 */
class Comment
{
    /**
     * @Id
     * @Column(type="integer", length=4)
     * @GeneratedValue(strategy="AUTO")
     */
    protected $comment_id;

    /**
     * @Column(type="integer", length=4)
     */
    protected $news_id;

    /**
     * @Column(type="integer", length=4)
     */
    protected $user_id;

    /**
     * @Column(type="string", length=200)
     */
    protected $email;

    /**
     * @Column(type="string", length=40)
     */
    protected $body;

    /**
     * @Column(type="datetime")
     */
    protected $added;

    /**
     * @Column(type="string", length=25)
     */
    protected $pseudo;

    /**
     * @Column(type="string", length=15)
     */
    protected $ip;

    /**
     * @Column(type="string", length=255)
     */
    protected $host;

    /**
    * ASSOCIATIONS for existing Columns IN OTHER OBJECTS.
    *
    * Order: property first, then accessor method.
    */

    /**
     * Mapping 1 Comment to n News (1:n).
     *
     * @ManyToOne(targetEntity="News", inversedBy="comments")
     * @JoinColumn(name="news_id", referencedColumnName="news_id")
     */
    private $news;

    public function setNews(Entities\News $news)
    {
        $this->news = $news;
    }

    /**
     * @ManyToOne(targetEntity="User", inversedBy="comments_authored")
     * @JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $comment_authored_by;

    public function setAuthor(Entities\User $user)
    {
        $this->comment_authored_by = $user;
    }
}
?>