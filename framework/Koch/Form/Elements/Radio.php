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

namespace Koch\Form\Elements;

class Radio extends Input implements FormElementInterface
{
    /**
     * label next to element
     *
     * @var string
     */
    protected $label, $description;

    /**
     * constructor
     *
     */
    public function __construct()
    {
        $this->type = 'radio';

        return $this;
    }

    /**
     * checks or unchecks radio button
     *
     * @param bool checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * sets clickable label next to element
     *
     * @param string $text
     */
    public function setLabel($text)
    {
        $this->label = '<label for="'.$this->id.'">'.$text.'</label>';

        return $this;
    }

    /**
     * sets description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

}
