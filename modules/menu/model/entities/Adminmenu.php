<?php

namespace Entities;

/** @Entity @Table(name="Cs_Adminmenu") */
class Adminmenu
{
    /**
     * @Id
     * @Column(type="integer", length="3")
     */    
    protected $id;
    /**
     * @Id
     * @Column(type="integer", length="2")
     */
    protected $parent;
    /**
     * @Column(type="string")
     */
    protected $type;
    /**
     * @Column(type="string")
     */
    protected $text;
    /**
     * @Column(type="string")
     */
    protected $href;
    /**
     * @Column(type="string")
     */
    protected $title;
    /**
     * @Column(type="string")
     */
    protected $target;
    /**
     * @Column(type="integer", length="3")
     */
    protected $sortorder;
    /**
     * @Column(type="string")
     */
    protected $icon;
    /**
     * @Column(type="string")
     */
    protected $permission;
}
?>