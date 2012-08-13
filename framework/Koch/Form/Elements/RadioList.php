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

namespace Koch\Formelement;

class Radiolist extends Radio implements FormElementInterface
{
    protected $options;

    public function __construct()
    {
        $this->type = 'radio';
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    protected $separator = '<br/>';

    public function render()
    {
        $options = array( 'option1' => 'berlin',
                          'option2' => 'new york');

        $this->setOptions($options);

        $i=0;
        $html = '';
        while ( list($key, $value) = each($this->options)) {
            // setup a new radio formelement
            $radio = new Koch_Formelement_Radio();
            $radio->setValue($key)
                  ->setName($value)
                  ->setDescription($value)
                  ->setLabel($value);

            // check the element, if value is "active"
            if ($this->value == $key) {
                $radio->setChecked();
            }

            // assign it as output
            $html .= $radio;

            #Koch_Debug::printR($html);

            // if we have more options comming up, add a seperator
            if (++$i!=count($this->options)) {
                $html .= $this->separator;
            }
        }

        return $html;
    }
}
