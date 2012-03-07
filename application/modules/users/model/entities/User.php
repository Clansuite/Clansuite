<?php
namespace Entities;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Repositories\UserRepository")
 * @Table(name="users")
 */
class User
{
    public function __construct()
    {
        $this->news_authored = new ArrayCollection;
        $this->comments_authored = new ArrayCollection;

        /**
         * $group = $entityManager->find('Group', $groupId);
            $user = new User();
            $user->getGroups()->add($group);
         */
        $this->groups = new ArrayCollection();
    }

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $user_id;

    /**
     * @Column(type="string", length=150)
     */
    protected $email;

    /**
     * @Column(type="string", length=25, unique=true)
     */
    protected $nick;

    /**
     * @Column(type="string", length=40)
     */
    protected $passwordhash;

    /**
     * @Column(type="string", length=40)
     */
    protected $new_passwordhash;

    /**
     * @Column(type="string", length=20)
     */
    protected $salt;
    /**
     * @Column(type="string", length=40)
     */
    protected $new_salt;
    /**
     * @Column(type="string")
     */
    protected $activation_code;

    /**
     * @Column(type="integer", length=4)
     */
    protected $joined;

    /**
     * @Column(type="integer", length=4)
     */
    protected $timestamp;

    /**
     * @Column(type="integer", length=1)
     */
    protected $disabled;

    /**
     * @Column(type="integer", length=1)
     */
    protected $activated;

    /**
     * @Column(type="integer", length=1)
     */
    protected $status;

    /**
     * @Column(type="string", length=5)
     */
    protected $country;

    /**
     * @Column(type="string", length=12)
     */
    protected $language;

    /**
     * @Column(type="string", length=8)
     */
    protected $timezone;

    /**
     * @Column(type="string")
     */
    protected $theme;

    /**
     * @Column(type="integer")
     */
    protected $group_id;

    /**
    * ASSOCIATIONS for existing Columns IN OTHER OBJECTS.
    *
    * Order: property first, then accessor method.
    */

    /**
     * 1) One User might have written zero to many articles.
     * 2) Not a column !
     *
     * @OneToMany(targetEntity="News", mappedBy="news_authored_by")
     */
    private $news_authored;

    public function getNewsAuthored()
    {
        return $this->news_authored;
    }

    /**
     * 1) One User might have written zero to many articles.
     * 2) Not a column in the user database table, but a mapping to another Entity Object!
     *
     * @OneToMany(targetEntity="Comment", mappedBy="comment_authored_by")
     */
    private $comments_authored;

    public function getCommentsAuthored()
    {
        return $this->comments_authored;
    }

    /**
     * A multiple number of user may each be in a multiple number of groups.
     * Making this a n:m relationship.
     *
     * -ManyToMany(targetEntity="Group")
     * -JoinTable
     */
    private $groups;

    public function getGroups()
    {
        return $this->groups;
    }
}
?>