<?php
   /**
    * Koch Framework
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards), Florian Wolf (2006-2007)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if(defined('IN_CS') === false)
{
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Config;

/**
 * Koch FrameworkCore File - Config Handler for YAML Format
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
 * @category    Koch
 * @package     Core
 * @subpackage  Configuration
 */
class YAML
{
    /**
     * Constructor
     */
    public function __construct($file = null)
    {
        return self::readConfig($file);
    }

    /**
     * Koch_Config_YAML is a Singleton
     *
     * @param string $file Filename
     * @return instance of Config_YAML class
     */
    public static function getInstance($file = null)
    {
        static $instance;

        if(isset($instance) === false)
        {
            $instance = new Koch_Config_YAML($file);
        }

        return $instance;
    }

    /**
     * Write the config array to a yaml file
     *
     * @param   string  The filename
     * @return  array | boolean false
     * @todo fix this return true/false thingy
     */
    public static function writeConfig($file, array $array)
    {
        /**
         * transform PHP Array into YAML Format
         */

        # take syck, as the faster one first
        if( extension_loaded('syck') )
        {
            # convert to YAML via SYCK
            $yaml = syck_dump($data);
        }
        # else check, if we have spyc as library
        elseif(is_file(ROOT_LIBRARIES . '/spyc/Spyc.class.php') === true)
        {
            # ok, load spyc
            if(false === class_exists('Spyc', false))
            {
                include ROOT_LIBRARIES . '/spyc/Spyc.class.php';
            }

            $spyc = new Spyc();

            # convert to YAML via SPYC
            $yaml = $spyc->dump($array);
        }
        else # we have no YAML Parser - too bad :(
        {
            throw new Koch_Exception('No YAML Parser available. Get Spyc or Syck!');
        }

        /**
         * write array
         */

        # write YAML content to file
        file_put_contents($file, $yaml);
    }

    /**
     *  Read the complete config file *.yaml
     *
     * @param   string  The yaml filename
     * @return  array
     */
    public static function readConfig($file)
    {
        # check if the filename exists
        if(is_file($file) === false or is_readable($file) === false)
        {
            throw new Koch_Exception('YAML File ' . $file . ' not existing or not readable.');
        }

        # init
        $array = '';
        $yaml_content = '';

        # read the yaml content of the file
        $yaml_content = file_get_contents($file);

        /**
         * check if the php extension SYCK is available as parser
         * SYCK is written in C, so it's implementation is faster then SPYC, which is pure PHP.
         */
        if(extension_loaded('syck')) # take the faster one first
        {
            # syck_load accepts a YAML string as input and converts it into a PHP data structure
            $array = syck_load($yaml_content);
        }
        # else check if we habe spyc as a library
        elseif(is_file(ROOT_LIBRARIES . '/spyc/Spyc.class.php') === true)
        {
            # ok, load spyc
            if(false === class_exists('Spyc', false))
            {
                include ROOT_LIBRARIES . '/spyc/Spyc.class.php';
            }

            # instantiate
            $spyc = new Spyc();

            # parse the yaml content with spyc
            $array = $spyc->load($yaml_content);
        }
        else # we have no YAML Parser - too bad :(
        {
            throw new Koch_Exception('No YAML Parser available. Get Spyc or Syck!');
        }

        return $array;
    }
}
?>