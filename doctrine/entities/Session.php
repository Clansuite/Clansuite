<?php

namespace Entities;

/** @Entity @Table(name="Cs_Session") */
class Session
{
    /**
     * @Column(type="integer")
     */
    protected $user_id;

    /**
     * @Id
     * @Column(type="integer")
     */
    protected $session_id;

    /**
     * @Column(type="string")
     */
    protected $session_name;

    /**
     * @Column(type="string")
     */
    protected $session_data;

    /**
     * @Column(type="integer", length="4")
     */
    protected $session_starttime;

    /**
     * @Column(type="integer", length="1")
     */
    protected $session_visibility;

    /**
     * @Column(type="string")
     */
    protected $session_where;
}
?>