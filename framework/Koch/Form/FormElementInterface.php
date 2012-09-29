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
 * Koch Framework - Interface for Formelements.
 */
interface FormElementInterface
{
    // add/remove attributes for a formelement
    public function setAttribute($attribute, $value);
    public function getAttribute($attribute);

    // getter/ setter for the value
    public function setValue($value);
    public function getValue();

    // initializes the attributes of the formelement
    #public function initialize();

    // renders the output of the formobject as html
    public function render();

    // sets a validation rule the form element
    #public function addValidation();

    #public function hasError();
    #public function getErrorMessage();
}
