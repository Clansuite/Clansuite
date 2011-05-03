<?php
namespace Entities;

/**
 * @Entity(repositoryClass="Repositories\AboutDeveloperRepository")
 * @Table(name="cs_about_developer")
 */

class AboutDeveloper
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Developer type:  1 = Member; 2 = Former Member
     * @Column(type="integer")
     */
    protected $developer;

    /**
     * Status: 0 = inaktiv; 1 = aktiv; 
     * @Column(type="integer")
     */
    protected $status;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="string")
     */
    protected $nick;

    /**
     * @Column(type="string")
     */
    protected $email;

    /**
     * @Column(type="string")
     */
    protected $position;

    /**
     * @Column(type="string")
     */
    protected $ohloh_pic;

    /**
     * @Column(type="string")
     */
    protected $alternate;

    /**
     * @Column(type="string")
     */
    protected $ohloh_url;

    /**
     * @Column(type="string")
     */
    protected $gift_title;

    /**
     * @Column(type="string")
     */
    protected $gift_url;

    /**
     * Sort number for display
     * @Column(type="integer")
     */
    protected $sort;

    ### ===================================
    ### Setter and Getter methodes
    ### ===================================



}
?>