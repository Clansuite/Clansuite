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
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\Form;

/**
 * Koch Framework - Interface for Form
 */
interface FormInterface
{
    // output the html representation of the form
    public function render();

    // set action, method, name
    public function setAction($action);
    public function setMethod($method);
    public function setName($method);

    // add/remove a formelement
    public function addElement($formelement, $position = null);
    public function delElementByName($name);

    // load/save the XML description of the form
    #public function loadDescriptionXML($xmlfile);
    #public function saveDescriptionXML($xmlfile);

    // shortcut method / factory method for accessing the formelements
    public static function formelementFactory($formelement);

    // callback for validation on the whole form (all formelements)
    #public function processForm();
}
