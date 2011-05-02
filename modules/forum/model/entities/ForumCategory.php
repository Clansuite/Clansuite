<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\ForumCategoryRepository")
 * @Table(name="cs_forum_category")
 */

class ForumCategory
{

    /**
     * @Id
     * @Column(name="cat_id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $cat_id;

    /**
     * @Column(name="parent_id", type="integer")
     */
    protected $parent_id;

    /**
     * @Column(name="sort", type="integer")
     */
    protected $sort;

    /**
     * @Column(name="title", type="string")
     */
    protected $title;

    /**
     * @Column(name="description", type="string")
     */
    protected $description;

    ### ===================================
    ### Setter and Getter methodes
    ### ===================================



}
?>