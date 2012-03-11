<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Event;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch_Eventdispatcher
 *
 * Eventdispatcher is a container class for all the EventHandlers.
 * This class is a helper for event-driven development.
 * You can register eventhandlers under an eventname.
 * When you trigger an event, it performs an lookup of the eventname
 * over all registered eventhandlers and fires the event, if found.
 * This is a very flexible form of communication between objects.
 *
 * @pattern EventDispatcher, Event, Advanced Subject-Observer-Pattern, Notification Queue
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Eventdispatcher
 */
class Dispatcher
{
    /**
     * @var object Instance of Koch_Eventdispatcher (singleton)
     */
    private static $instance = null;

    /**
     * @var array All registered Eventhandlers
     */
    private $eventhandlers = array();

    /**
     * Koch_Eventdispatcher is a Singleton implementation
     */
    public static function instantiate()
    {
        if(self::$instance === null)
        {
            self::$instance = new Dispatcher;
        }

        return self::$instance;
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
        if(isset($this->eventhandlers[$eventName]) === false)
        {
            return array();
        }

        return $this->eventhandlers[$eventName];
    }

    /**
     * Adds an Event to the Eventhandlers Array
     *
     * Usage
     * <code>
     * function handler1() {
     * echo "A";
     * }
     * function handler2() {
     * echo "B";
     * }
     * function handler3() {
     * echo "C";
     * }
     * $event = Koch_Eventdispatcher::instantiate();
     * $event->addEventHandler('event_name1', 'handler1');
     * $event->triggerEvent('event_name1'); # Output: A
     * $event->addEventHandler('event_name2', 'handler2');
     * $event->triggerEvent('event_name2'); # Output: B
     * $event->addEventHandler('event_name1', 'handler3');
     * $event->triggerEvent('event_name1'); # Output: AC
     * </code>
     *
     * @param $eventName    Name of the Event
     * @param $eventobject object|string Instance of Koch_Event or filename string
     */
    public function addEventHandler($eventName, Koch_Event_Interface $event_object)
    {
        # if eventhandler is not set already, initialize as array
        if(isset($this->eventhandlers[$eventName]) === false)
        {
            $this->eventhandlers[$eventName] = array();
        }

        # add event to the eventhandler list
        $this->eventhandlers[$eventName][] = $event_object;
    }

    /**
     * Removes an Event
     *
     * Usage
     * <code>
     * function handler1() {
     * echo "A";
     * }
     * $event = Koch_Eventdispatcher::instantiate();
     * $event->addEventHandler('event_name', 'handler1');
     * $event->triggerEvent('event_name'); # Output: A
     * $event->removeEventHandler('event_name', 'handler1');
     * $event->triggerEvent('event_name'); # No Output
     * </code>
     *
     * @param string event name
     * @param mixed event handler
     */
    public function removeEventHandler($eventName, Koch_Event_Interface $event_object = null)
    {
        # if eventhandler is not added, we have nothing to remove
        if(isset($this->eventhandlers[$eventName]) == false)
        {
            return false;
        }

        if($event_object === null)
        {
            # unset all eventhandlers for this eventName
            unset($this->eventhandlers[$eventName]);
        }
        else # unset a specific eventhandler
        {
            foreach($this->eventhandlers[$eventName] as $key => $registered_event)
            {
                if($registered_event == $event_object)
                {
                    unset($this->$this->eventhandlers[$eventName][$key]);
                }
            }
        }
    }

    /**
     * triggerEvent
     *
     * Usage
     * <code>
     * function handler1() {
     * echo "A";
     * }
     * $event = Koch_Eventdispatcher::instantiate();
     * $event->addEventHandler('event_name', 'handler1');
     * $event->triggerEvent('event_name'); # Output: A
     * </code>
     *
     * @param $event Name of Event or Event object to trigger.
     * @param $context default null The context of the event triggering, often the object from where we are calling.
     * @param $info default null Some pieces of information.
     * @return $event object
     */
    public function triggerEvent($event, $context = null, $info = null)
    {
        /**
         * init a new event object with constructor settings
         * if $event is not an instance of Koch_Event.
         * $event string will be the $name inside $event object,
         * accessible with $event->getName();
         */
        if(false === ($event instanceof Koch_Event))
        {
            $event = new Koch_Event($event, $context, $info);
        }

        # get the Name
        $eventName = '';
        $eventName = $event->getName();

        if(isset($this->eventhandlers[$eventName]) === false)
        {
            return $event;
        }

        # loop over all eventhandlers and look for that eventname
        foreach($this->eventhandlers[$eventName] as $eventhandler)
        {
            # handle the event !!
            $eventhandler->execute($event);

            # break, on cancelled
            if(method_exists($event, 'isCancelled') and $event->isCancelled() == true)
            {
                break;
            }
        }

        return $event;
    }

    # no construct (singleton)
    protected function __construct()
    {
        return;
    }

    # no clone (singleton)
    private function __clone()
    {
        return;
    }
}
?>