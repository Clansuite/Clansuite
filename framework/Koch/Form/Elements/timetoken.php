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
 * Formelement_Timetoken
 */
class Timetoken extends Formelement implements FormelementInterface
{
    public function generateToken()
    {
       // @todo consider using PHP Spam Kit Class
    }

    /**
     * Inserts a hidden input field for a token. Reducing the risk of an CSRF exploit.
     */
    public function render()
    {
        return '<input type="hidden" name="'.$this->generateToken().'" value="1" />';
    }
}
