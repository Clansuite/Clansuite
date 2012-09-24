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

namespace Koch\Config\Adapter;

/**
 * Koch Framework - Config Handler for CSV Format
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Configuration
 */
class CSV
{
    /**
     * Read the config array from csv file
     *
     * @param   string  The filename
     * @return mixed array | boolean false
     */
    public static function readConfig($file)
    {
        if (is_file($file) === false or is_readable($file) === false) {
            throw new \Koch\Exception\Exception('CSV Config File not existing or not readable.');
        }

        $csvarray = array();

        // read file
        if (($handle = fopen($file, "r+")) !== false) {
            // set the parent multidimensional array key to 0
            $key = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // count the total keys in the row
                $c = count($data);

                // populate the multidimensional array
                for ($x = 0; $x < $c; $x++) {
                    $csvarray[$key][$x] = $data[$x];
                }
                $key++;
            }

            // close the File.
            fclose($handle);
        }

        return $csvarray;
    }

    /**
     * Write the config array to csv file
     *
     * @param   string  The filename
     * @param   array   The configuration array
     */
    public function writeConfig($file, array $array)
    {
        if (($handle = fopen($file, "r+")) !== false) {
            // transform array to csv notation
            foreach ($array as $key => $value) {
                if (is_string($value)) {
                    $value = explode(',', $value);
                    $value = array_map('trim', $value);
                }
            }

            // write to csv to file
            return fputcsv($handle, $value, ',', '"');
        }
    }
}
