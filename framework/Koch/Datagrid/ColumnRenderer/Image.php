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

namespace Koch\Datagrid\ColumnRenderer;

/**
 * Datagrid Column Renderer Image
 *
 * Render cells with Image
 */
class Image extends ColumnRenderer implements ColumnRendererInterface
{
    public $nameWrapLength  = 25;

    /**
     * Render the value(s) of a cell
     *
     * @param Clansuite_Datagrid_Cell
     * @return string Return html-code
     */
    public function renderCell($oCell)
    {
        $image_alt = $value = $oCell->getValue();

        // build an image name for the alt-tag
        if ( mb_strlen($value) > $this->nameWrapLength ) {
            $image_alt = mb_substr($value, 0, $this->nameWrapLength - 5) . 'Image';
        }

        return $this->_replacePlaceholders($value, Clansuite_HTML::img( $value, array( 'alt'  => $image_alt)));
    }
}
