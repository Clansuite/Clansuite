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

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/*
/**
 * Interface for Clansuite_Event
 *
 * The Clansuite_Event has to implement a method handle()
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Event
 *//*
interface Clansuite_Event_Interface
{
    # methods gets an Event Object
    public function execute(Clansuite_Event $event);
}*/

/**
 * Clansuite_Eventdispatcher
 *
 * Purpose:
 * Eventdispatcher is a container class for all the EventHandlers. This class is a helper for event-driven development.
 * You can register eventhandlers under an eventname. When you trigger an event, it performs an lookup of the eventname
 * over all registered eventhandlers and fires the event, if found.
 * This is a very flexible form of communication between objects.
 *
 * @pattern EventDispatcher, Event, Advanced Subject-Observer-Pattern, Notification Queue
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Eventdispatcher
 */
class Clansuite_Eventdispatcher
{
    /**
     * @var object Instance of Clansuite_Eventdispatcher (singleton)
     */
    private static $instance = null;

    /**
     * @var array All registered Eventhandlers
     */
    private $eventhandlers = array();

    /**
     * Clansuite_Eventdispatcher is a Singleton implementation
     */
    public static function instantiate()
    {
        if(self::$instance === null)
        {
            self::$instance = new Clansuite_Eventdispatcher;
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
        if(isset($this->eventhandlers[$eventName]) == false)
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
     * $event = Clansuite_Eventdispatcher::instantiate();
     * $event->addEventHandler('event_name1', 'handler1');
     * $event->triggerEvent('event_name1'); # Output: A
     * $event->addEventHandler('event_name2', 'handler2');
     * $event->triggerEvent('event_name2'); # Output: B
     * $event->addEventHandler('event_name1', 'handler3');
     * $event->triggerEvent('event_name1'); # Output: AC
     * </code>
     *
     * @param $eventName    Name of the Event
     * @param $eventobject  Instance of Clansuite_Event
     */
    public function addEventHandler($eventName, Clansuite_Event_Interface $event)
    {
        # if eventhandler is not set already, initialize as array
        if(isset($this->eventhandlers[$eventName]) == false)
        {
            $this->eventhandlers[$eventName] = array();
        }

        # add event to the eventhandler list
        $this->eventhandlers[$eventName][] = $event;
    }

    /**
     * Adds multiple Events at once to the Eventhandlers Array
     *
     * @param  $events array    Events to register (only names)
     * @params $event object int|string object id, owner of events
     */
    public function addMultipleEventHandlers($events, Clansuite_Event_Interface $object)
    {
        if(empty($events) or is_array($events) == false)
        {
            return;
        }

        foreach($events as $event)
        {
            $this->addEventHandler($event, $object);
        }
    }

    /**
     * Removes an Event
     *
     * Usage
     * <code>
     * function handler1() {
     * echo "A";
     * }
     * $event = Clansuite_Eventdispatcher::instantiate();
     * $event->addEventHandler('event_name', 'handler1');
     * $event->triggerEvent('event_name'); # Output: A
     * $event->removeEventHandler('event_name', 'handler1');
     * $event->triggerEvent('event_name'); # No Output
     * </code>
     *
     * @param string event name
     * @param mixed event handler
     */
    public function removeEventHandler($eventName, Clansuite_Event_Interface $event = null)
    {
        # if eventhandler is not added, we have nothing to remove
        if(isset($this->eventhandlers[$eventName]) == false)
        {
            return false;
        }

        if($event === null)
        {
            # unset all eventhandlers for this eventName
            unset($this->eventhandlers[$eventName]);
        }
        else # unset a specific eventhandler
        {
            foreach($this->eventhandlers[$eventName] as $key => $registered_event)
            {
                if($registered_event == $event)
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
     * $event = Clansuite_Eventdispatcher::instantiate();
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
         * if $event is not an instance of Clansuite_Event.
         * $event string will be the $name inside $event object,
         * accessible with $event->getName();
         */
        if(!$event instanceof Clansuite_Event)
        {
            $event = new Clansuite_Event($event, $context, $info);
        }

        # get the Name
        $eventName = '';
        $eventName = $event->getName();

        if(isset($this->eventhandlers[$eventName]) == false)
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

/**
 * Represents an Event
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Event
 */
class Clansuite_Event implements ArrayAccess
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
        if(false == array_key_exists($name, $this->context))
        {
            throw new Clansuite_Exception(sprintf(_('The event "%s" has no context parameter "%s" .'), $this->eventname, $name));
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

/**
 * Clansuite_Eventloader
 *
 * Purpose:
 * Eventloader handles the loading and registering of events by using event configuration files.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Eventloader
 */
class Clansuite_Eventloader
{
    /**
     * Loads and registers all events of the core and all activated modules.
     */
    public static function autoloadEvents()
    {
        #self::loadAllModuleEvents();
        self::loadCoreEvents();
    }

    /**
     * Loads and registers the core eventhandlers according to the event configuration file.
     * The event configuration for the core is file is /configuration/events.config.php.
     */
    public static function loadCoreEvents()
    {
        $events = array();
        $events = include ROOT . 'configuration/events.config.php';
        Clansuite_Eventdispatcher::instantiate()->addMultipleEventHandlers($events);
    }

    /**
     * Loads and registers the eventhandlers for a module according to the module event configuration file.
     * The event configuration files for a module resides in /module/module.events.php (abstract).
     * For instance the eventcfg filename for module news is /modules/news/news.events.php.
     *
     * @param string $modulename Name of a module.
     */
    public static function loadModuleEvents($modulename)
    {
        $events = array();
        $events = include ROOT_MOD . $modulename . DS . $modulename . 'events.php';
        Clansuite_Eventdispatcher::instantiate()->addMultipleEventHandlers($events);
    }

    /**
     * Loads and registers the eventhandlers for all activated modules.
     */
    public static function loadAllModuleEvents()
    {
        #$modules = # @todo fetch all activated modules

        foreach($modules as $modulename)
        {
            self::loadModuleEvents($modulename);
        }
    }
}
?>