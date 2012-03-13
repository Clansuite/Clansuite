<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
    *
    * LICENSE:
    *
    *    This program is free software; you can redistribute it and/or modify
    *    it under the terms of the GNU General Public License as published by
    *    the Free Software Foundation; either version 2 of the License, or
    *    (at your option) any later version.
    *
    *    This program is distributed in the hope that it will be useful,
    *    but WITHOUT ANY WARRANTY; without even the implied warranty of
    *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    *    GNU General Public License for more details.
    *
    *    You should have received a copy of the GNU General Public License
    *    along with this program; if not, write to the Free Software
    *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    *
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    * @author     Jens-André Koch <vain@clansuite.com>
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Config\Adapter;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

class Csv
{
    /**
     * Read the config array from csv file
     *
     * @param   string  The filename
     * @return  mixed array | boolean false
     */
    public static function readConfig($file)
    {
        if(is_file($file) === false or is_readable($file) === false)
        {
            throw new Clansuite_Exception('JSON Config File not existing or not readable.');
        }

        $csvarray = array();

        # read file
        if(($handle = fopen($file, "r+")) !== false)
        {
            # set the parent multidimensional array key to 0
            $key = 0;

            while(($data = fgetcsv($handle, 1000, ",")) !== false)
            {
                # count the total keys in the row
                $c = count($data);

                # populate the multidimensional array
                for($x = 0; $x < $c; $x++)
                {
                    $csvarray[$key][$x] = $data[$x];
                }
                $key++;
            }

            # close the File.
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
        if(($handle = fopen($file, "r+")) !== false)
        {
            # transform array to csv notation
            foreach ($array as $key => $value)
            {
                if (is_string($value))
                {
                    $value = explode(',', $value);
                    $value = array_map('trim', $value);
                }
            }

            # write to csv to file
            return fputcsv($handle, $value, ',', '"');
        }
    }
}
?>
