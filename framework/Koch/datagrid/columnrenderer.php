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

namespace Koch\Datagrid;

/**
 * Datagrid ColumnRenderer
 *
 * Provides standard methods for all Datagrid Column Renderers.
 */
class ColumnRenderer extends Renderer
{
    /**
     * The column object
     *
     * @var object Clansuite_Datagrid_Column
     */
    private $_column;

    //---------------------
    // Setter
    //---------------------

    /**
     * Set the col object
     *
     * @param Clansuite_Datagrid_Column $column
     */
    public function setColumn($column)
    {
        $this->_column = $column;
    }

    //---------------------
    // Getter
    //---------------------

    /**
     * Get the column object
     *
     * @return Clansuite_Datagrid_Column
     */
    public function getColumn()
    {
        return $this->_column;
    }

    /**
     * Instantiate the Column Base
     *
     * @param Clansuite_Datagrid_Column
     */
    public function __construct($column)
    {
        $this->setColumn($column);
    }

    //---------------------
    // Class methods
    //---------------------

    /**
     * Replace placeholders with values
     *
     * @param  array  $values
     * @param  string $format
     * @return string
     */
    public function _replacePlaceholders($values, $format)
    {
        $placeholders   = array();
        $replacements   = array();

        // search for placeholders %{...}
        preg_match_all('#%\{([^\}]+)\}#', $format, $placeholders, PREG_PATTERN_ORDER );

        // check if placeholders are used
        // @todo replace count() with check for first placeholder element: if(isset($_Placeholders[1][0]))
        //       and move count into the if
        $_PlacerholderCount = count($placeholders[1]);
        if ($_PlacerholderCount > 0) {
            // loop over placeholders
            for ($i=0;$i<$_PlacerholderCount;$i++) {
                if ( isset($values[$placeholders[1][$i]]) ) {
                    $replacements['%{' . $placeholders[1][$i] . '}'] = $values[$placeholders[1][$i]];
                }
            }
        }

        // return substituted string
        return strtr($format, $replacements);
    }
}
