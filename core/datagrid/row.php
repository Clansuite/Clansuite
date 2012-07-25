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
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Koch\datagrid;

/**
 * Datagrid Row
 *
 * Defines one single row for the datagrid
 */
class row extends Base
{
    //--------------------
    // Class properties
    //--------------------

    /**
     * All cells of this row
     *
     * @var array Clansuite_Datagrid_Cell
     */
    private $_Cells = array();

    /**
     * The datagrid
     *
     * @var Clansuite_Datagrid $_Datagrid
     */
    private $_Datagrid;

    /**
     * The position of a column
     *
     * @var int
     */
    private $_Position = 0;

    //--------------------
    // Setter
    //--------------------

    /**
     * Set all row-cells
     *
     * @param array Clansuite_Datagrid_Cell
     */
    public function setCells($_Cells)
    {
        $this->_Cells = $_Cells;
    }

    /**
     * Set the position
     *
     * @param int
     */
    public function setPosition($_Position)
    {
        $this->_Position = $_Position;
    }

    //--------------------
    // Getter
    //--------------------

    /**
     * Get the cells for this row
     *
     * @return array Clansuite_Datagrid_Cell
     */
    public function getCells()
    {
        return $this->_Cells;
    }

    /**
     * Set the position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->_Position;
    }

    //--------------------
    // Class methods
    //--------------------

    /**
     * Add a cell to the row
     *
     * @param Clansuite_Datagrid_Cell
     */
    public function addCell(&$_Cell)
    {
        # array_push($this->_Cells, $_Cell);
        $this->_Cells[] = $_Cell;
    }
}
