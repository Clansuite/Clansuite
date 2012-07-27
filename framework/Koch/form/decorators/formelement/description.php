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

namespace Koch\Form\Decorators\Formelement;

use Koch\Formelement\Decorator;

/**
 * Koch Framework - Formelement Decorator Description.
 *
 * Adds a <span> element containing the formelement
 * description after html_fromelement_content.
 *
 * @category Koch
 * @package Koch_Form
 * @subpackage Koch_Form_Decorator
 */
class Description extends Decorator
{
    /**
     * Name of this decorator
     *
     * @var string
     */
    public $name = 'description';

    /**
     * renders description AFTER formelement
     *
     * @todo if required form field add (*)
     */
    public function render($html_formelement)
    {
        // add description
        if ( isset($this->formelement->description) == true) {
            $html_formelement .= '<br />'. CR;
            $html_formelement .= '<span class="formdescription">' . $this->formelement->getDescription() . '</span>' . CR;
        }

        return $html_formelement;
    }
}
