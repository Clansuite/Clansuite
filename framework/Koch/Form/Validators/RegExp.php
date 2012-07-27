<?php

/**
 * Koch Framework
 * Jens-Andr� Koch � 2005 - onwards
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

class Regexp extends Validator
{
    /**
     * The regular expression to be used on the value.
     *
     * @var string
     */
    private $regexp;

    /**
     * Sets the Regular Expression to use in the value.
     *
     * @param string $regexp The regular expression
     */
    public function setRegexp($regexp)
    {
        $this->regexp = $regexp;
    }

    /**
     * Returns the Regular Expression.
     *
     * @return string the regular expression
     */
    public function getRegexp()
    {
        return $this->regexp;
    }

    public function getValidationHint()
    {
        return _('The values must match the following criteria.');
    }

    public function getErrorMessage()
    {
        return _('The values must match the following criteria.');
    }

    protected function processValidationLogic($value)
    {
        if (true === preg_match($this->regexp, $value)) {
            return true;
        } else {
            return false;
        }
    }
}
