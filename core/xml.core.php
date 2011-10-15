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
    * @copyright  Jens-André Koch (2005-onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite_XML
 *
 * This class converts XML-based data into JSON or Array formatted data.
 */
class Clansuite_XML
{
    const MAX_RECURSION_DEPTH_ALLOWED = 25;
    const FETCH_ATTRIBUTES = true;
    const REMOVE_ATTRIBUTES_SUBLEVEL = true;

    /**
     * toJson
     *
     * This function transforms the XML based String data into JSON format. If the input XML
     * string is in table format, the resulting JSON output will also be in table format.
     * Conversely, if the input XML string is in tree format, the resulting JSON output will
     * also be in tree format.
     *
     * @param string XML data string.
     *
     * @return Returns a string containing JSON table/tree formatted data. Otherwise, it returns an empty string.
     */
    public static function toJson($xml)
    {
        if($xml == null)
        {
            return false;
        }

        $xml = simplexml_load_string($xml);

        $json = '';

        # convert the XML structure into PHP array structure.
        $array = Clansuite_XML2JSON::simpleXMLToArray($xml);

        if(($array != null) and (sizeof($array) > 0))
        {
            $json = json_encode($array);
        }

        return $json;
    }

    /**
     * toArray
     *
     * This function accepts a SimpleXmlElementObject as a single argument
     * and converts it into a PHP associative array.
     * If the input XML is in table format (i.e. non-nested), the resulting associative
     * array will also be in a table format. Conversely, if the input XML is in
     * tree (i.e. nested) format, this function will return an associative array
     * (tree/nested) representation of that XML.
     *
     * @param object Simple XML Element Object
     * @param int Recursion Depth
     * @return array Returns assoc array containing the data from XML. Otherwise, returns null.
     */
    public static function toArray($xml, $recursionDepth=0)
    {
        # Keep an eye on how deeply we are involved in recursion.
        if($recursionDepth > self::MAX_RECURSION_DEPTH_ALLOWED)
        {
            return null;
        }

        # we are at top/root level
        if($recursionDepth == 0)
        {
            # If the external caller doesn't call this function initially
            # with a SimpleXMLElement object just return
            if(get_class($xml) != 'SimpleXMLElement')
            {
                return null;
            }
            else # store original SimpleXmlElementObject sent by the caller.
            {
                $provided_xml_object = $xml;
            }
        }

        if($xml instanceof SimpleXMLElement)
        {
            # Get a copy of the simpleXmlElementObject
            $copy_of_xml_object = $xml;
            # Get the object variables in the SimpleXmlElement object for us to iterate.
            $xml = get_object_vars($xml);
        }

        // It needs to be an array of object variables.
        if(is_array($xml))
        {
            # Initialize the result array.
            $resultArray = array();

            # Is the input array size 0? Then, we reached the rare CDATA text if any.
            if(count($xml) == 0)
            {
                # return the lonely CDATA. It could even be whitespaces.
                return (string) $copy_of_xml_object;
            }

            # walk through the child elements now.
            foreach($xml as $key => $value)
            {
                /**
                 * Add Attributes to the results array ?
                 */
                if(self::FETCH_ATTRIBUTES === false)
                {
                    # check KEY - is it an attribute?
                    if((is_string($key)) && ($key == '@attributes'))
                    {
                        # yes, don't add, just continue with next element of foreach
                        continue;
                    }
                }

                # increase the recursion depth by one.
                $recursionDepth++;

                # WATCH IT ! RECURSION !!!
                # recursively process the current (VALUE) element
                $resultArray[$key] = self::simpleXMLToArray($value, $recursionDepth);

                # decrease the recursion depth by one
                $recursionDepth--;
            }

            # we are reaching the top/root level
            if($recursionDepth == 0)
            {
                /**
                 * Set the XML root element name as the root [top-level] key of
                 * the associative array that we are going to return to the caller of this
                 * recursive function.
                 */
                $tempArray = $resultArray;
                $resultArray = array();
                $resultArray[$provided_xml_object->getName()] = $tempArray;
            }

            /**
             * Shifts every key/value pair of @attributes one level up and removes @attributes
             */
            if(self::FETCH_ATTRIBUTES === true and self::REMOVE_ATTRIBUTES_SUBLEVEL === true)
            {
                # ok, add attributes
                if(isset($resultArray['@attributes']))
                {
                    # but as key => value elements
                    foreach($resultArray['@attributes'] as $key => $value)
                    {
                        $resultArray[$key] = $value;
                    }
                    # removing @attributes
                    unset($resultArray['@attributes']);
                }
            }

            return $resultArray;
        }
        else
        {
            # it's is either the XML attribute text or text between XML tags
            return (string) $xml;
        }
    }
}
?>