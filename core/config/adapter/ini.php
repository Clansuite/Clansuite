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
    die('Koch Framework not loaded. Direct Access forbidden.');
}

namespace Koch\Config;

/**
 * Koch FrameworkCore File - Config Handler for INI Format
 *
 * @category    Koch
 * @package     Core
 * @subpackage  Configuration
 */
class INI
{
    /**
     * Koch_Config_INI is a Singleton
     *
     * @return instance of Config_INIHandler class
     */
    public static function getInstance()
    {
        static $instance;
        if(isset($instance) == null)
        {
            $instance = new Koch_Config_INI();
        }
        return $instance;
    }

    /**
     * Writes a .ini Configfile
     * This method writes the configuration values specified to the filename.
     *
     * @param string $file Filename of .ini to write
     * @param array $array Associative Array with Ini-Values
     * @return mixed/boolean Returns the amount of bytes written to the file, or FALSE on failure.
     */
    public static function writeConfig($file, array $array)
    {
        # ensure we got an array
        if(is_array($array) === false)
        {
            throw new Koch_Exception('writeConfig Parameter $array is not an array.');
        }

        if(empty($file))
        {
            throw new Koch_Exception('writeConfig Parameter $filename is not given.');
        }

        # when ini_filename exists, get old config array
        if(is_file($file) === true)
        {
            $old_config_array = self::readConfig($file);

            # array merge: overwrite the array to the left, with the array to the right, when keys identical
            $config_array = array_replace_recursive($old_config_array, $array);
        }
        else
        {
            # create file
            touch($file);

            # the config array = the incoming assoc_array
            $config_array = $array;
        }

        # attach an security header at the top of the ini file
        $content = '';
        $content .= "; <?php die('Access forbidden.'); /* DO NOT MODIFY THIS LINE! ?>\n";
        $content .= "; \n";
        $content .= "; Koch Framework Configuration File : \n";
        $content .= '; ' . $file . "\n";
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

        if (is_writable($file))
        {
            if (!$filehandle = fopen($file, 'wb'))
            {
                echo _('Could not open file: ') . $file;
                return false;
            }

            if (fwrite($filehandle, $content) == false)
            {
                echo _('Could not write to file: ') . $file;
                return false;

            }
            fclose($filehandle);
            return true;
        }
        else
        {
            printf(_('File %s is not writeable. Set correct file and directory permissions.'), $file);
            return false;
        }
    }

    /**
     * Read the complete config file *.ini.php
     *
     * @param   string  The filename
     * @return  array | boolean false
     */
    public static function readConfig($file)
    {
        # check ini_filename exists
        if(is_file($file) === false or is_readable($file) === false)
        {
            throw new Koch_Exception('File not found: ' . $file, 4);
        }

        return parse_ini_file($file, true);
    }
}
?>