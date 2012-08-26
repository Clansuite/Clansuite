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

namespace Koch\Form\Validators;

use Koch\Form\Validator;

/**
 * Validates the value of an integer|float with maxvalue given.
 */
class MaxValue extends Validator
{
    public $maxvalue;

    public function getMaxValue()
    {
        return $this->maxvalue;
    }

    /**
     * Setter for the maximum length of the string.
     *
     * @param int|float $maxvalue
     */
    public function setMaxValue($maxvalue)
    {
        if (is_string($maxvalue) === true) {
            $msg = _('Parameter Maxvalue must be numeric (int|float) and not %s.');
            $msg = sprintf($msg, gettype($maxvalue));

            throw new \InvalidArgumentException($msg);
        }

        $this->maxvalue = $maxvalue;
    }

    public function getErrorMessage()
    {
        $msg = _('The value exceeds the maximum value of %s.');

        return sprintf($msg, $this->getMaxValue());
    }

    public function getValidationHint()
    {
        $msg = _('The value must be smaller than %s.');

        return sprintf($msg, $this->getMaxValue());
    }

    protected function processValidationLogic($value)
    {
        if ($value > $this->getMaxValue()) {
            return false;
        }

        return true;
    }
}
