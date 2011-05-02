<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\ForumBoardsRepository")
 * @Table(name="cs_forum_boards")
 */

class ForumBoards
{
    /**
     * @Id
     * @Column(name="board_id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $board_id;

    /**
     * @OneToOne(targetEntity="ForumCategory")
     * @JoinColumn(name="cat_id", referencedColumnName="cat_id")
     */
    protected $cat_id;

    /**
     * @Column(name="child_level", type="integer")
     */
    protected $child_level;

    /**
     * @Column(name="parent_id", type="integer")
     */
    protected $parent_id;

    /**
     * @Column(name="sort", type="integer")
     */
    protected $sort;

    /**
     * @Column(name="post_id_last", type="integer")
     */
    protected $post_id_last;

    /**
     * @Column(name="post_id_updated", type="integer")
     */
    protected $post_id_updated;

    /**
     * @Column(name="groups", type="string")
     */
    protected $groups;

    /**
     * @Column(name="profile_id", type="integer")
     */
    protected $profile_id;

    /**
     * @Column(name="title", type="string")
     */
    protected $title;

    /**
     * @Column(name="description", type="string")
     */
    protected $description;

    /**
     * @Column(name="num_topics", type="integer")
     */
    protected $num_topics;

    /**
     * @Column(name="num_posts", type="integer")
     */
    protected $num_posts;

    /**
     * @Column(name="count_posts", type="integer")
     */
    protected $count_posts;

    /**
     * @Column(name="unapproved_posts", type="integer")
     */
    protected $unapproved_posts;

    /**
     * @Column(name="unapproved_topics", type="integer")
     */
    protected $unapproved_topics;

    /**
     * @Column(name="redirect", type="string")
     */
    protected $redirect;


    ### ===================================
    ### Setter and Getter methodes
    ### ===================================



}
?>