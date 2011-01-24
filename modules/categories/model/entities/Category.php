<?php
use Entities\News;

namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\CategoryRepository")
 * @Entity @Table(name="Cs_Categories")
 */
class Category
{
    /**
     * @Id
     * @Column(type="integer", length="4")
     * @GeneratedValue
     */
    protected $cat_id;

    /**
     * @Column(type="integer", length="4")
     */
    protected $module_id;

    /**
     * @Column(type="integer", length="4")
     */
    protected $sortorder;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="string")
     */
    protected $description;

    /**
     * @Column(type="string")
     */
    protected $image;

    /**
     * @Column(type="string")
     */

    protected $icon;

    /**
     * @Column(type="string", length="7")
     */
    protected $color;


    /**
     * @OneToMany(targetEntity="Category", mappedBy="parent")
     */
    #private $children;

    /**
     * @ManyToOne(targetEntity="Category", inversedBy="children")
     * @JoinColumn(name="parent_id", referencedColumnName="id")
     */
    #private $parent;

    // ...

    #public function __construct()
    #{
    #    $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    #}

}
?>