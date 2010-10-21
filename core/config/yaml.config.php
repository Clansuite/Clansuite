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

# Security Handler
if(defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core File - Config Handler for YAML Format
 *
 * Purpose: This Confighandler supports the YAML-Fileformat.
 *
 * What is YAML?
 * 1) YAML Ain't Markup Language
 * 2) YAML(tm) (rhymes with "camel") is a straightforward machine parsable data serialization format
 * designed for human readability and interaction with scripting languages. YAML is optimized for
 * data serialization, configuration settings, log files, Internet messaging and filtering.
 *
 * The YAML Support of this class is based around two parser libraries:
 * a) the php extension SYCK (available via PECL)
 * b) the SPYC Library.
 *
 * @link http://www.yaml.org/ YAML Website
 * @link http://www.yaml.org/spec/ YAML Format Specification
 * @link http://pecl.php.net/package/syck/ PECL SYCK Package maintained by Alexey Zakhlestin
 * @link http://github.com/why/syck/tree/master PECL SYCK Repository
 * @link http://spyc.sourceforge.net/ SPYC Library Website at Sourceforge
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Configuration
 */
class Clansuite_Config_YAML
{
    # holds SPYC instance
    private $spyc = null;

    /**
     * CONSTRUCTOR
     * sets up all variables
     */
    public function __construct($filename = null)
    {
        if(isset($filename))
        {
            return self::readConfig($filename);
        }
    }

    /**
     * Clansuite_Config_INI is a Singleton
     *
     * @param string $filename Filename
     * @return instance of Config_YAMLHandler class
     */
    public static function getInstance($filename = null)
    {
        static $instance;

        if(isset($instance) === false)
        {
            $instance = new Clansuite_Config_YAMLHandler($filename);
        }

        return $instance;
    }

    /**
     * Write the config array to a yaml file
     *
     * @param   string  The filename
     * @return  array | boolean false
     * @todo use file_put_contents()
     * @todo fix this return true/false thingy
     */
    public static function writeConfig($filename, $assoc_array)
    {
        # transform PHP Array into YAML Format
        if( extension_loaded('syck') ) # take the faster one first
        {
            # convert to YAML via SYCK
            $yaml_content = syck_dump($data);
        }
        else
        {
            # convert to YAML via SPYC
            $yaml_content = $this->spyc->dump($assoc_array);
        }

        # ensure file is writable
        if (is_writable($filename))
        {
            # open file
            if (!$filehandle = fopen($filename, 'wb'))
            {
                 throw new Clansuite_Exception('Could not open file '. $filename .'.');
                 return false;
            }

            # write YAML content to file
            if (!fwrite($filehandle, $yaml_content))
            {
                throw new Clansuite_Exception('Could not write to file '. $filename .'.');
                return false;

            }

            # close file
            fclose($filehandle);
            return true;
        }
        else
        {
            throw new Clansuite_Exception('Could not write to file '. $filename .' because permissions are wrong. Apply correct file und directory permissions.');
            return false;
        }
    }

    /**
     *  Read the complete config file *.yaml
     *
     * @param   string  The yaml filename
     * @return  array
     */
    public static function readConfig($filename)
    {
        $php_datastructure = '';
        $yaml_content = '';

        # check if the filename exists
        if(is_file($filename) === true)
        {
            # read the yaml content of the file
            $yaml_content = file_get_contents($filename);
        }
        else
        {
            throw new Clansuite_Exception('Die Datei '.$filename.' existiert nicht. Kann YAML Config nicht lesen!');
        }

        /**
         * check if the php extension SYCK is available as parser
         * SYCK is written in C, so it's implementation is faster then SPYC, which is pure PHP.
         */
        if( extension_loaded('syck') ) # take the faster one first
        {
            # syck_load accepts a YAML string as input and converts it into a PHP data structure
            $php_datastructure = syck_load($yaml_content);
        }
        # else check if we habe spyc as a library
        elseif(is_file(ROOT_LIBRARIES.'/spyc/Spyc.class.php') === true)
        {
            # ok, load spyc
            if( false === class_exists('Spyc',false) )
            {
                include  ROOT_LIBRARIES.'/spyc/Spyc.class.php';
            }

            # instantiate
            $spyc = new Spyc();

            # parse the yaml content with spyc
            $php_datastructure = $spyc->load($yaml_content);
        }
        else # we have no YAML Parser - too bad :(
        {
            throw new Clansuite_Exception('No YAML Parser available. Get Spyc or Syck!');
        }

        # check if the php_datastructire is an filled array
        if (is_array($php_datastructure) and (empty($php_datastructure) == false))
        {
            # then return it
            return $php_datastructure;
        }
        else # return an empty array
        {
            return array();
        }
    }
}
?>