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

namespace Koch\Form\Validators;

use Koch\Form\Validator;

class Equals extends Validator
{
    /**
     * The equals to field
     *
     * @var string
     */
    public $equalsTo;

    /**
     * EqualsTo Setter
     *
     * @param string $equalsTo The equals to field
     */
    public function setEqualsTo($equalsTo)
    {
        $this->equalsTo = $equalsTo;
    }

    /**
     * EqualsTo Getter
     *
     * @return string the equals to field
     */
    public function getEqualsTo()
    {
        return $this->equalsTo;
    }

    public function getValidationHint()
    {
        return _('The values must be equal.');
    }

    public function getErrorMessage()
    {
        return _('The values must be equal.');
    }

    protected function processValidationLogic($value)
    {
        if ($this->equalsTo == $value) # $this->element->getValue()) {
            return true;
        } else {
            return false;
        }
    }
}
