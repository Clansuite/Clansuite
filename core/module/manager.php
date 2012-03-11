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

namespace Koch\Module;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Module Manager
 *
 * Handles
 * - enable,
 * - disable,
 * - install,
 * - uninstall and
 * - update of a module.
 *
 * You might select the module with selectModule() or
 * via constructor injection.
 */
class Manager
{
    public function __construct($module)
    {
        # load the relavant stuff of the module
        # $this->config = loadRelevantStuff($module);

        # allow fluent chaining
        return this;
    }

    /**
     * Enables a module
     *
     * @return boolean True, if module was enabled. False otherwise.
     */
    public function enable()
    {
        # a) get config
        # b) change disabled to enabled
        # c) invalidate global module autoload cache?
        # d) re-charge the cache with the new value?

        return false;
    }

    /**
     * Disables a module
     *
     * @return boolean True, if module was enabled. False otherwise.
     */
    public function disable()
    {
        return false;
    }

    /**
     *
     * @return boolean True, if module was installed. False otherwise.
     */
    public function install()
    {
        return false;
    }

    /**
     *
     * @return boolean
     */
    public function uninstall()
    {
        return false;
    }

    /**
     *
     * @return boolean
     */
    public function update()
    {
        return false;
    }
}
?>