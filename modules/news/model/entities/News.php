<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\NewsRepository") @Table(name="Cs_News")
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
     * @OneToOne(targetEntity="Category")
     * @JoinColumn(name="cat_id", referencedColumnName="cat_id")
     */
    private $category;

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
        $this->user_id = $user->user_id;
    }

    public function setCategory(Entities\Category $category)
    {
        $this->cat_id = $category->cat_id;
    }
}
?>