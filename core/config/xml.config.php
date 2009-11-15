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
    *
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

# Load Clansuite_Config_Base
require dirname(__FILE__) . '/config.base.php';

/**
 * Clansuite Core File - Config Handler for XML Format (via SimpleXML)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Configuration
 */
class Clansuite_Config_XMLHandler extends Clansuite_Config_Base implements ArrayAccess
{
     /**
     * Configuration Array
     * protected-> only visible to childs
     *
     * @var array
     * @access protected
     */
    protected $config = array();

    /**
     * CONSTRUCTOR
     * sets up all variables
     */
    public function __construct($filename)
    {
        if( is_file($filename) == false or is_readable($filename) == false)
        {
            throw new Clansuite_Exception('XML File not existing or not readable.');
        }

        $this->config = self::readConfig($filename);
    }

    /**
     * Clansuite_Config_XMLHandler is a Singleton
     *
     * @param object $filename Filename
     *
     * @return instance of Config_XMLHandler class
     */
    public static function getInstance($filename)
    {
        static $instance;

        if ( ! isset($instance))
        {
            $instance = new Clansuite_Config_XMLHandler($filename);
        }

        return $instance;
    }

    /**
     * Write the configarray to the xml file
     *
     * @access  public
     * @param   string  The filename
     * @return  mixed array | boolean false
     */
    public static function writeConfig($filename, $assoc_array)
    {
        # Log
        # Clansuite_Logger::notice( __CLASS__ . ": Writing Array to XML-File $filename." );

        # transform assoc_array to xml
        $xml = $this->arrayToXML($assoc_array);

        # write xml into the file
        file_put_contents($filename, $xml);
    }

    /**
     * Convert a PHP array to XML (via XMLWriter)
     *
     * @param $array PHP Array
     * @return xml-string
     */
    public static function arrayToXml($array)
    {
        # initalize new XML Writer in memory
        $xml = new XmlWriter();
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
    public static function writeArray(XMLWriter $xml, $array)
    {
        foreach($data as $key => $value)
        {
            if(is_array($value))
            {
                $xml->startElement($key);
                # recursive call
                $this->writeArray($xml, $value);
                $xml->endElement();
                continue;
            }

            $xml->writeElement($key, $value);
        }
    }

    /**
     * Read the complete config array from xml file
     *
     * @access  public
     * @param   string  The filename
     * @return  mixed array | boolean false
     */
    public static function readConfig($filename)
    {
        # Log
        # Clansuite_Logger::notice( __CLASS__ . ": Loading XML from $filename." );

        # read file
        # @toto consider usage of simplexml_load_file() here
        $xml = file_get_contents($filename);

        # transform XML to PHP Array
        $array = Clansuite_Functions::SimpleXMLToArray($xml);

        return $array;
    }
}
?>