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

/**
 *  Koch\Formelement
 *  |
 *  \- Koch\Formelement\Input
 *     |
 *     \- Koch\Formelement\SelectDate
 */
class SelectDate extends Input implements FormElementInterface
{
    public function __construct()
    {
        // Note: HTML5 <input type="date"> is not a select formelement.
        $this->type = 'date';

        return $this;
    }

    /**
     * HTML 5 has several Types of input formfields for date and time selection
     *
     * date             - Selects date, month and year
     * month            - Selects month and year
     * week             - Selects week and year
     * time             - Selects time (hour and minute)
     * datetime         - Selects time, date, month and year (UTC time)
     * datetime-local   - Selects time, date, month and year (local time)
     */
    public function setType($type)
    {
        $types = array('date', 'month', 'week', 'time', 'datetime', 'datetime-local');

        if (in_array($type, $types) === true) {
            $this->type = $type;
        } else {
            throw new \Koch\Exception\Exception('Invalid formfield type specified. Choose one of ' . explode(',', $types));
        }

        return $this;
    }
}
