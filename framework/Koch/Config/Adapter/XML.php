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
 * Koch Framework - Config Handler for XML Format (via SimpleXML)
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Configuration
 */
class XML
{
    /**
     * Constructor.
     *
     * @param string $file XML Config file to read.
     */
    public function __construct($file = null)
    {
        return self::readConfig($file);
    }

    /**
     * Koch\Config_XML is a Singleton
     *
     * @param  object   $file Filename
     * @return instance of Config_XMLHandler class
     */
    public static function getInstance($file = null)
    {
        static $instance;

        if (isset($instance) === false) {
            $instance = new XML($file);
        }

        return $instance;
    }

    /**
     * Write the configarray to the xml file
     *
     * @param string The filename
     * @param array  Array to transform and write as xml
     * @return mixed array | boolean false
     */
    public static function writeConfig($file, $array)
    {
        // transform associative PHP array to XML
        $xml = \Koch\Datatype\Conversion::arrayToXML($array);

        // write xml into the file
        file_put_contents($file, $xml);
    }

    /**
     * Read the config array from xml file
     *
     * @param   string  The filename
     * @return mixed array | boolean false
     */
    public static function readConfig($file)
    {
        if (is_file($file) === false or is_readable($file) === false) {
            throw new \Exception('XML File not existing or not readable.');
        }

        // read file
        $xml = simplexml_load_file($file);

        // transform (SimpleXMLElement or XML) to PHP Array
        return \Koch\Datatype\XML::toArray($xml);
    }
}
