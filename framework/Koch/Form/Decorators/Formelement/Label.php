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

use Koch\Form\FormElementDecorator;

/**
 * Formelement_Decorator_Label
 *
 * Adds a <label> element containing the formelement label in-front of html_fromelement_content.
 *
 * @category Koch
 * @package Koch_Form
 * @subpackage Koch_Form_Decorator
 */
class Label extends FormelementDecorator
{
    /**
     * Name of this decorator
     *
     * @var string
     */
    public $name = 'label';

    /**
     * renders label BEFORE formelement
     *
     * @todo if required form field add (*)
     */
    public function render($html_formelement)
    {
        // add label
        if ( $this->formelement->hasLabel() == true) {
            // for attribute points to formelements id tag
            $html_formelement = CR . '<label for="'. $this->formelement->getId() .'">' . $this->formelement->getLabel() . '</label>'. CR . $html_formelement;
        }

        return $html_formelement;
    }
}
