<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Interface for Clansuite_Event
 *
 * The Clansuite_Event has to implement a method handle()
 *
 * @package     clansuite
 * @subpackage  eventhandler
 * @category    interfaces
 */
interface Clansuite_Event
{
    # methods gets an Event Object
    public function execute(Clansuite_Event $event);
}

/**
 * Clansuite_Eventdispatcher
 *
 * Purpose:
 * Eventdispatcher is a container class for all the EventHandlers. This class is an aide for event-driven development. 
 * You can register eventhandlers under a eventname. When you trigger an event, it performs an lookup of the eventname 
 * over all registered eventhandlers and fires the event, if found.
 * This is a very flexible form of communication between objects.
 * 
 * @pattern EventDispatcher, Event, Advanced Subject-Observer-Pattern, Notification Queue
 *
 * @package     clansuite
 * @subpackage  eventhandler
 * @category    core
 */
class Clansuite_Eventdispatcher
{
    # holds instance of this class (singleton)
    static private $instance;

    # holds eventhandlers
    private $eventhandlers = array();

    /**
     * Clansuite_Eventhandler is a Singelton implementation
     */
    public static function instantiate()
    {
        if (self::$instance === null)
        {
            self::instance = new Clansuite_Eventhandler();
        }
        return self::instance;
    }

    /**
     * Returns an array of all registered eventhandlers for a eventName.
     *
     * @param string $name    The event name
     *
     * @return array  Array of all eventhandlers for a certain event.
     */
    public function getEventHandlersForEvent($eventName)
    {
        if (!isset($this->eventhandlers[$eventName]))
        {
            return array();
        }

        return $this->eventhandlers[$eventName];
    }

    /**
     * Adds an Event to the Eventhandlers Array
     *
     * @param $eventName    Name of the Event
     * @param $event        Instance of Clansuite_Event
     */
    public function addEventHandler($eventName, Clansuite_Event $event)
    {
        # if eventhandler is not set already, initialize as array
        if (!isset($this->eventhandlers[$eventName]))
        {
            $this->eventhandlers[$eventName] = array();
        }

        # add event to the eventhandler list
        $this->eventhandlers[$eventName][] = $event;
    }

    public function removeEventHandlers($eventName)
    {
        # if eventhandler is not added, we have nothing to remove
        if (!isset($this->eventhandlers[$eventName]))
        {
            return false;
        }

        # unset all eventhandlers for this event
        unset($this->eventhandlers[$eventName]);
    }

    /**
     * triggerEvent
     *
     * @param $name
     * @param $context default null
     * @param $info default null
     */
    public function triggerEvent($event, $context = null, $info = null)
    {
        /**
         * init Event Class with constructor settings
         * if $event is not an instance of Clansuite_Event.
         * $event string will be the $name inside $event object,
         * accessible with $event->getName();
        if(!$event instanceof Clansuite_Event)
        {
            $event = new Clansuite_Event($event, $context, $info);
        }

        # get the Name
        $eventName = $event->getName();

        if (!isset($this->eventhandlers[$eventName]))
        {
            return $event;
        }

        # loop over all eventhandlers and look for that eventname
        foreach ($this->eventhandlers[$eventName] as $eventhandler)
        {
            # handle the event !!
            $eventhandler->execute($event);

            # break, on cancelled
            if($event->isCancelled())
            {
                break;
            }
        }
        return $event;
    }

    # no construct (singleton)
    protected function __construct(){}
    # no clone (singleton)
    private function __clone(){}
}

/**
 * Represents an Event
 *
 * @package     clansuite
 * @subpackage  eventhandler
 * @category    core
 */
class Clansuite_Event implements ArrayAccess
{
    # name of the event
    private $name;
    # context of the event triggering, the object from where we are calling
    private $context;
    # some pieces of information
    private $info;
    # cancel state of the event
    private $cancelled = false;

    /**
     * Event constructor
     *
     * @param $name     Event Name
     * @param $context  default null
     * @param $info     default null
     */
    public function __construct($name, $context = null, $info = null)
    {
        $this->name     = $name;
        $this->content  = $context;
        $this->info;    = $info;
    }

    /**
     * getName returns the Name of the Event.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

   public function offsetExists($name)
   {

   }

   public function offsetGet($name)
   {

   }

   public function offsetSet($name, $value)
   {

   }

   public function offsetUnset($name)
   {

   }
}
?>