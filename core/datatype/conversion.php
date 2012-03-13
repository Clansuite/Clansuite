<?php
   /**
    * Koch Framework
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Koch Framework".
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
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

namespace Koch\Datatype;

# Security Handler
if(defined('IN_CS') === false)
{
    exit('Koch Framework not loaded. Direct Access forbidden.');
}

/**
 * Koch Framework - Class for advanced Datatype Conversions.
 */
class Conversion
{
    public static function XMLToArray($xml, $recursionDepth = 0)
    {
        XML::toArray($xml, $recursionDepth);
    }

    /**
     * Converts a PHP array to XML (via XMLWriter)
     *
     * @param $array PHP Array
     * @return xml-string
     */
    public static function arrayToXml($array)
    {
        # initalize new XML Writer in memory
        $xml = new \XmlWriter();
        $xml->openMemory();

        # with <root> element as top level node
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('root');

        # add the $array data in between
        $this->writeArray($xml, $array);

        # close with </root>
        $xml->endElement();

        # dump memory
        return $xml->outputMemory(true);
    }

    /**
     * writeArray() is a recursive looping over an php array,
     * adding all it's elemets to an XMLWriter object.
     * This method is used by arrayToXML().
     * @see arrayToXML()
     *
     * @param $xml XMLWriter Object
     * @param $array PHP Array
     */
    public static function writeArray(XMLWriter $xml, array $array)
    {
        foreach($array as $key => $value)
        {
            if(is_array($value))
            {
                $xml->startElement($key);

                # recursive call
                self::writeArray($xml, $value);

                $xml->endElement();

                continue;
            }

            $xml->writeElement($key, $value);
        }
    }

    /**
     * Converts a SimpleXML String recursivly to an Array
     *
     * @author Jason Sheets <jsheets at shadonet dot com>
     * @param string $xml SimpleXML String
     * @return Array
     */
    public static function simpleXMLToArrayLight($simplexml)
    {
        $array = array();

        if($simplexml === true)
        {
            foreach($simplexml as $k => $v)
            {
                if($simplexml['list'] === true)
                {
                    $array[] = self::SimpleXMLToArrayLight($v);
                }
                else
                {
                    $array[$k] = self::SimpleXMLToArrayLight($v);
                }
            }
        }

        if(count($array) > 0)
        {
            return $array;
        }
        else
        {
            # WARNING! Type Conversion drops childs and attributes.
            return (string) $simplexml;
        }
    }

    /**
     * Converts an Object to an Array
     *
     * @param $object object to convert
     * @return array
     */
    public static function object2array($object)
    {
        $array = null;
        if(is_object($object) === true)
        {
            $array = array();
            foreach(get_object_vars($object) as $key => $value)
            {
                if(is_object($value) === true)
                {
                    $array[$key] = self::object2Array($value);
                }
                else
                {
                    $array[$key] = $value;
                }
            }
        }
        return $array;
    }

    /**
     * Converts an Array to an Object
     *
     * @param $array array to convert to an object
     * @return array
     */
    public function array2object($array)
    {
        if(is_array($array) === false)
        {
            return $array;
        }

        $object = new stdClass();

        if(is_array($array) and count($array) > 0)
        {
            foreach($array as $name => $value)
            {
                $name = mb_strtolower(trim($name));

                if(empty($name) === false)
                {
                    # WATCH OUT ! Recursion.
                    $object->$name = self::array2Object($value);
                }
            }

            return $object;
        }
        else
        {
            return false;
        }
    }
}
?>