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

namespace Koch\Module;

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
 * You might select the module with selectModule() or via constructor injection.
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Module
 */
class Manager
{
    public function __construct($module)
    {
        // load the relavant stuff of the module
        // $this->config = loadRelevantStuff($module);

        // allow fluent chaining

        return this;
    }

    /**
     * Enables a module
     *
     * @return boolean True, if module was enabled. False otherwise.
     */
    public function enable()
    {
        // a) get config
        // b) change disabled to enabled
        // c) invalidate global module autoload cache?
        // d) re-charge the cache with the new value?

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
