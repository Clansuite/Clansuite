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

namespace Koch\Formelement;

class Checkboxlist extends Checkbox implements FormelementInterface
{
    public function getOptions()
    {
        $options = array( '1' => 'eins', '2' => 'zwei', '3' => 'drei', '4' => 'Polizei' );

        return $options;
    }

    public function render()
    {
        $html = '';

        foreach ($this->getOptions() as $key => $value) {
            $checkbox_element = new Koch_Formelement_Checkbox();
            $checkbox_element->setLabel($value);
            $checkbox_element->setName($value);
            $checkbox_element->setDescription($value);
            $checkbox_element->setValue($key);
            $html .= $checkbox_element;
        }

        return $html;
    }
}
