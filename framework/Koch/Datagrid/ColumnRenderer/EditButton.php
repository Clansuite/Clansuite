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

namespace Koch\Datagrid\Columnrenderer;

/**
 * Datagrid Column Renderer for Edit Button Cells
 *
 * Renders an edit button.
 */
class Editbutton extends ColumnRenderer implements ColumnRendererInterface
{
    /**
    * Render the value(s) of a cell
    *
    * @param Clansuite_Datagrid_Cell
    * @return string Return html-code
    */
    public function renderCell($oCell)
    {
        $oImagebutton = new Clansuite_Formelement_Imagebutton();
        $oImagebutton->setName('Editbutton');
        $oImagebutton->setID('Editbutton-' . $oCell->getValue());
        $oImagebutton->setClass('DatagridEditbutton-' . $oCell->getColumn()->getAlias());
        $oImagebutton->setValue(_('Edit'));

        return $oImagebutton->render();

        #return sprintf('<input type="button" value="EditButton" id="EditButton-%s" name="EditButton" />', $oCell->getValue());
    }
}
