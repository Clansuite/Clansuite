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
 * Validator for an IP address.
 * The validator accepts IPv4 and IPv6 addresses.
 * If you want only one version, use the flags FILTER_FLAG_IPV4
 * or FILTER_FLAG_IPV6 via setOptions().
 *
 * @see http://www.php.net/manual/en/filter.filters.validate.php
 */
class Ip extends Validator
{
    public function getErrorMessage()
    {
        return _('The value must be a IP.');
    }

    public function getValidationHint()
    {
        return _('Please enter a valid IP address.');
    }

    protected function processValidationLogic($value)
    {
        /**
         * Note: filter_var() does not support IDNA.
         * The INTL extension provides the method idn_to_ascii().
         * It converts a mbyte URL to a punycode ASCII string.
         */
        if (function_exists('idn_to_ascii')) {
            $value = idn_to_ascii($value);
        }

        if (true === (bool) filter_var( $value, FILTER_VALIDATE_IP, $this->getOptions() )) {
            return true;
        } else {
            return false;
        }
    }
}
