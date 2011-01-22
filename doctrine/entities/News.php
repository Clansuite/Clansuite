<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Entities\NewsRepository")
 * @Entity @Table(name="Cs_News")
 */
class News
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $news_id;

    /**
     * @Column(type="string")
     */
    protected $news_title;

    /**
     * @Column(type="string")
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

    ### Setter and Getter for existing Columns IN OTHER OBJECTS


    /**
     * Bidirectional - Many News are authored by one user (OWNING SIDE)
     *
     * ONE User 'authors' zero To MANY news articles.
     * An news article is 'authored by' one User.
     * The relationships is bidirectional, so inversedBy is included in the @ManyToOne annotation.
     */

    /**
     * @ManyToOne(targetEntity="User", inversedBy="news_authored")
     * @JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $authored;

    public function setAuthor(Entities\User $user)
    {
        $this->authored = $user->user_id;
    }

}
?>