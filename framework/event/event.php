<?php

/**
 * Koch Framework
 * Jens-André Koch © 2005 - onwards
 *
 * This file is part of "Koch Framework".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\event;

/**
 * Represents an Event
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Event
 */
class event implements \ArrayAccess
{
    /**
     * @var Name of the event
     */
    private $eventname;

    /**
     * @var array The context of the event triggering. Often the object from where we are calling.
     */
    private $context;

    /**
     * @var string Some pieces of additional information
     */
    private $info;

    /**
     * @var boolean The cancel state of the event
     */
    private $cancelled = false;

    /**
     * Event constructor
     *
     * @param $name     Event Name
     * @param $context  The context of the event triggering. Often the object from where we are calling. Default null.
     * @param $info     Some pieces of additional information. Default null.
     */
    public function __construct($name, $context = null, $info = null)
    {
        $this->eventname = $name;
        $this->context = $context;
        $this->info = $info;
    }

    /**
     * getName returns the Name of the Event.
     *
     * @return string
     */
    public function getName()
    {
        return $this->eventname;
    }

    /**
     * getContext returns the Context.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * getInfo returns
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * getCancelled returns the cancelled-status of the event
     *
     * @returns boolean
     */
    public function getCancelled()
    {
        return (boolean) $this->cancelled;
    }

    /**
     * sets the cancelled flag to true
     */
    public function cancel()
    {
        $this->cancelled = true;
    }

    /**
     * ArrayAccess Implementation
     */

    /**
     * Returns true if the parameter exists (implements the ArrayAccess interface).
     *
     * @param string $name The parameter name
     *
     * @return Boolean true if the parameter exists, false otherwise
     */
    public function offsetExists($name)
    {
        return array_key_exists($name, $this->context);
    }

    /**
     * Returns a parameter value (implements the ArrayAccess interface).
     *
     * @param string $name The parameter name
     *
     * @return mixed The parameter value
     */
    public function offsetGet($name)
    {
        if (false == array_key_exists($name, $this->context)) {
            throw new Koch_Exception(sprintf(_('The event "%s" has no context parameter "%s" .'), $this->eventname, $name));
        }

        return $this->context[$name];
    }

    /**
     * Sets a parameter (implements the ArrayAccess interface).
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     */
    public function offsetSet($name, $value)
    {
        $this->context[$name] = $value;
    }

    /**
     * Removes a parameter (implements the ArrayAccess interface).
     *
     * @param string $name The parameter name
     */
    public function offsetUnset($name)
    {
        unset($this->context[$name]);
    }
}
