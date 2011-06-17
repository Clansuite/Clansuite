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
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * Clansuite Core File - Config Handler for INI Format
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Configuration
 */
class Clansuite_Config_INI
{
    /**
     * Clansuite_Config_INI is a Singleton
     *
     * @return instance of Config_INIHandler class
     */
    public static function getInstance()
    {
        static $instance;
        if(isset($instance) == null)
        {
            $instance = new Clansuite_Config_INI();
        }
        return $instance;
    }

    /**
     * Writes a .ini Configfile
     * This method writes the configuration values specified to the filename.
     *
     * @param string $filename Filename of .ini to write
     * @param array $array Associative Array with Ini-Values
     * @return mixed/boolean Returns the amount of bytes written to the file, or FALSE on failure.
     */
    public static function writeConfig($filename, array $array)
    {
        # ensure we got an array
        if(is_array($array) === false)
        {
            throw new Clansuite_Exception('writeConfig Parameter $array is not an array.');
        }

        if(empty($filename))
        {
            throw new Clansuite_Exception('writeConfig Parameter $filename is not given.');
        }

        # when ini_filename exists, get old config array
        if(is_file($filename) === true)
        {
            $old_config_array = self::readConfig($filename);
            
            # array merge: overwrite the array to the left, with the array to the right, when keys identical
            $config_array = array_replace_recursive($old_config_array, $array);
        }
        else
        {
            # create file
            touch($filename);

            # the config array = the incoming assoc_array
            $config_array = $array;
        }

        # attach an security header at the top of the ini file
        $content = '';
        $content .= "; <?php die('Access forbidden.'); /* DO NOT MODIFY THIS LINE! ?>\n";
        $content .= "; \n";
        $content .= "; Clansuite Configuration File : \n";
        $content .= '; ' . $filename . "\n";
        $content .= "; \n";
        $content .= '; This file was generated on ' . date('d-m-Y H:i') . "\n";
        $content .= ";\n\n";

        # loop over every array element
        foreach($config_array as $key => $item)
        {
            # checking if it's an array
            if(is_array($item))
            {
                # write an comment header block
                $content .= CR;
                $content .= ';----------------------------------------' . CR;
                $content .= '; ' . $key . CR;
                $content .= ';----------------------------------------' . CR;

                # write an parseable [array_header] block
                $content .= '[' . $key . ']' . CR;

                # for every element after that
                foreach ($item as $key2 => $item2)
                {
                    if(is_numeric($item2) || is_bool($item2))
                    {
                        # write numeric and boolean values without quotes
                        $content .= $key2 . ' = ' . $item2 . CR;
                    }
                    else
                    {
                        # write value with quotes
                        $content .= $key2 .' = "' . $item2 . '"'.CR;
                    }
                }
            }
            # if it's not an array
            else
            {
                if(is_numeric($item) || is_bool($item))
                {
                    # write numeric and boolean values without quotes
                    $content .= $key . ' = ' . $item . CR;
                }
                else
                {
                    # write value with quotes
                    $content .= $key2 .' = "' . $item2 . '"'.CR;
                }
            }
        }

        # add php closing tag
        $content .= CR . '; DO NOT REMOVE THIS LINE */ ?>';

        if (is_writable($filename))
        {
            if (!$filehandle = fopen($filename, 'wb'))
            {
                echo _('Could not open file: ') . $filename;
                return false;
            }

            if (fwrite($filehandle, $content) == false)
            {
                echo _('Could not write to file: ') . $filename;
                return false;

            }
            fclose($filehandle);
            return true;
        }
        else
        {
            printf(_('File %s is not writeable. Set correct file and directory permissions.'), $filename);
            return false;
        }
    }

    /**
     * Read the complete config file *.ini.php
     *
     * @param   string  The filename
     * @return  array | boolean false
     */
    public static function readConfig($filename)
    {
        # check ini_filename exists
        if(is_file($filename) === false or is_readable($filename) === false)
        {
            throw new Clansuite_Exception('File not found: ' . $filename, 4);
        }

        return parse_ini_file($filename, true);
    }
}
?>