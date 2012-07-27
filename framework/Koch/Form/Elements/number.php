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

class Number extends Input implements FormelementInterface
{
    public function __construct()
    {
        $this->type = 'number';

        return $this;
    }

    /**
     * Specifies the minimum value allowed
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Specifies the maximum value allowed
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Specifies legal number intervals (if step="2", legal numbers could be -2,0,2,4, etc)
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }
}
