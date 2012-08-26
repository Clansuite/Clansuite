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

class Checkbox extends Input implements FormElementInterface
{
    /**
     * Label next to element
     *
     * @var string
     */
    public $label;

    /**
     * Default option
     *
     * @var string
     */
    public $default;

    /**
     * Options
     *
     * @var array
     */
    public $options;

    public $description;

    public function setDefaultOption($default)
    {
        $this->default = $default;

        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * constructor
     */
    public function __construct()
    {
        $this->type = 'checkbox';
        $this->label = null;

        return $this;
    }

    /**
     * check or unchecks the checkbox
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
     * @param string $text
     */
    public function setDescription($text)
    {
        $this->description = $text;

        return $this;
    }

    public function render()
    {
        return parent::render() . $this->getLabel();
    }
}
