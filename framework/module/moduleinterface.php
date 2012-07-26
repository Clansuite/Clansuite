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
 * Interface for all modules which implement a specific action structure.
 * Inspired by Sinatra.
 *
 * Force classes implementing the interface to define these (must have) methods!
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Module
 */
interface moduleinterface
{
    public function action_list();     // GET     /foos
    public function action_show();     // GET     /foos/:foo_id
    public function action_new();      // GET     /foos/new
    public function action_edit();     // GET     /foos/:foo_id/edit
    public function action_create();   // POST    /foos
    public function action_update();   // PUT     /foos/:foo_id
    public function action_destroy();  // DELETE  /foos/:foo_id
}
