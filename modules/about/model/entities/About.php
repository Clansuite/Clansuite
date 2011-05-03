<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\AboutRepository")
 * @Table(name="cs_about")
 */

class About
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $title;

    /**
     * @Column(type="string")
     */
    protected $description;

    /**
     * @Column(type="integer")
     */
    protected $sort;

    ### ===================================
    ### Setter and Getter methodes
    ### ===================================



}
?>