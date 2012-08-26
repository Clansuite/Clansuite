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

namespace Koch\Form\Decorators\Formelement;

/**
 * Formelement_Decorator_Formelement
 *
 * This decorator decorates a formelement (A) with another formelement (B).
 *
 * By doing this you could Live without formdecorators is easy. You would add the two formelements (A+B) to a form.
 * But consider a situation where you want to add only formelement (A) to the form.
 * From inside formelement (A) you can't reach the form to add another formelement (B).
 * But you can reach the addDecorator() method. And at this point this class comes in.
 * It utilizes the autoloader to get the formelement (B).
 *
 * @category Koch
 * @package Koch_Form
 * @subpackage Koch_Form_Decorator
 */
class Formelement extends Decorator
{
    /**
     * Name of this decorator
     *
     * @var string
     */
    public $name = 'formelement';

    /**
     * @var string Name of the new formelement (B) to decorate the existing formelement (A) with.
     */
    private $formelementname;

    /**
     * @var object The formelement object
     */
    private $formelement_object;

    /**
     * Setter method for formelementname and instantiation of a new formelement.
     *
     * <strong>WATCH IT! THIS BREAKS THE CHAINING IN REGARD TO THE DECORATOR.</strong>
     *
     * @param  string $formelementname Name of the new formelement (B) to decorate the existing formelement (A) with.
     * @return object Instance of formelement.
     */
    public function newFormelement($formelementname)
    {
        // set name of the formelement to class
        $this->formelementname = $formelementname;

        // instantiate, set to class and return formelement object
        return $this->formelement_object = new $formelementname;
    }

    /**
     * renders new formelement (B) AFTER formelement (A)
     */
    public function render($html_formelement)
    {
        if (is_object($this->formelement_object)) {
            // WATCH THE DOT to render after formelement (A)
            $html_formelement .= CR . $this->formelement_object->render();

            return $html_formelement;
        }
    }
}
