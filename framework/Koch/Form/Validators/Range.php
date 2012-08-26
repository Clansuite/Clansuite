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
 * Validates a integer to be in a certain range.
 */
class Range extends Validator
{
    /**
     * @var filter var options
     */
    public $options = array();

    /**
     * Setter for the range array.
     *
     * @param int $minimum_length The minimum length of the string.
     * @param int $maximum_length The maximum length of the string.
     */
    public function setRange($minimum_length, $maximum_length)
    {
        $this->options['options']['min_range'] = (int) $minimum_length;
        $this->options['options']['max_range'] = (int) $maximum_length;
    }

    public function getValidationHint()
    {
        $min = $this->options['options']['min_range'];
        $max = $this->options['options']['max_range'];

        $msg = _('Please enter a value within the range of %s <> %s.');

        return sprintf($msg, $min, $max);
    }

    public function getErrorMessage()
    {
        $min = $this->options['options']['min_range'];
        $max = $this->options['options']['max_range'];

        $msg = _('The value is outside the range of %s <> %s.');

        return sprintf($msg, $min, $max);
    }

    protected function processValidationLogic($value)
    {
        if (false !== filter_var($value, FILTER_VALIDATE_INT, $this->options)) {
            return true;
        } else {
            return false;
        }
    }
}
