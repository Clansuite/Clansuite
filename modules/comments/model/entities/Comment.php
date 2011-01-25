<?php
namespace Entities;

/**
 * -Entity(repositoryClass="Repositories\CommentsRepository")
 * @Entity @Table(name="Cs_Comments")
 */
class Comment
{
    /**
     * @Id
     * @Column(type="integer", length="4")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $comment_id;
    
    /**
     * @Id
     * @Column(type="integer", length="4")
     */
    protected $user_id;
    
    /**
     * @Column(type="string", length="200")
     */
    protected $email;
    
    /**
     * @Column(type="string", length="40")
     */
    protected $body;
    
    /**
     * @Column(type="datetime")
     */
    protected $added;
    
    /**
     * @Column(type="string", length="25")
     */
    protected $pseudo;
    /**
     * @Column(type="string", length="15")
     */
    protected $ip;
    /**
     * @Column(type="string", length="255")
     */
    protected $host;
    
     /**
     * @ManyToOne(targetEntity="News", inversedBy="comments")
     * @JoinColumn(name="comment_id", referencedColumnName="news_id")
     */
    private $news;
    
    /**
     * @ManyToOne(targetEntity="User", inversedBy="comments_authored")
     * @JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $user;

    public function setArticle(Entities\News $article)
    {
        $this->news = $article;
    }

    public function setAuthor(Entities\User $user)
    {
        $this->user = $user;
    }
}
?>