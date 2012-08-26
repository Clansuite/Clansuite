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

class SelectYesNo extends Select implements FormElementInterface
{
    public function getYesNo()
    {
        $options = array( 'yes' => '1', 'no' => '0' );

        return $options;
    }

    public function render()
    {
        // check if we have options
        if ($this->options == null) {
            // if we don't have options, we set only 'yes' and 'no'
            $this->setOptions($this->getYesNo());
        } else {
            // if options is set, it means that a options['select'] is given
            // we combine it with yes/no
            $this->setOptions( $this->options += $this->getYesNo() );
        }

        return parent::render();
    }

}
