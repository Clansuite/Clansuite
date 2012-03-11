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
 * Koch_Eventloader
 *
 * Purpose:
 * Eventloader handles the loading and registering of events by using event configuration files.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Eventloader
 */
class Loader
{
    /**
     * Loads and registers all events of the core and all activated modules.
     */
    public static function loadEvents()
    {
        #self::loadAllModuleEvents();
        self::loadCoreEvents();
    }

    /**
     * Registers multiple Events by Name
     *
     * @param array $events_array eventname => filename
     * @param array $event_objects eventname => object
     */
    public static function loadEventHandlers($events)
    {
        if(empty($events) or is_array($events) === false)
        {
            return;
        }
        else # ok, we got an array with some event names
        {
            foreach($events as $event)
            {
                # array[0] filename
                $filename = $array[0];

                # array[1] classname
                $classname = Koch_Functions::ensurePrefixedWith($array[1], 'Koch_Event_');

                # load eventhandler
                Koch_Loader::requireFile($filename, $classname);

                # instantiate eventhandler
                $event_object = new $classname();

                # add the eventhandler to the dispatcher
                $eventdispatcher = Koch_Eventdispatcher::instantiate();
                $eventdispatcher->addEventHandler($event, $event_object);
            }
        }
    }

    /**
     * Loads and registers the core eventhandlers according to the event configuration file.
     * The event configuration for the core is file is /configuration/events.config.php.
     */
    public static function loadCoreEvents()
    {
        $events = array();
        $events = include ROOT . 'configuration/events.php';

        self::loadEventHandlers($events);
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
        $events = include ROOT_MOD . $modulename . DS . $modulename . '.events.php';

        self::loadEventHandlers($events);
    }

    /**
     * Loads and registers the eventhandlers for all activated modules.
     */
    public static function loadAllModuleEvents()
    {
        # fetch all activated modules
        $modules = Koch_ModuleInfoController::getAllActivatedModules();

        # load eventhandlers for each module
        foreach($modules as $module)
        {
            self::loadModuleEvents($module);
        }
    }
}
?>