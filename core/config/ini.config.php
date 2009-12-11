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
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
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
 * Clansuite Core File - Config Handler for INI Format
 *
 * This is the Config class of Clansuite. And it's build around the $config array,
 * which is a storage container for settings.
 *
 * We use some php magic in here:
 * The array access implementation makes it seem that $registry is an array,
 * even though it's an object! Why we do that? Because less to type!
 * The __set, __get, __isset, __unset are overloading functions to work with that array.
 *
 * Usage :
 * get data : $cfg->['name'] = 'john';
 * get data, using get() : echo $cfg->get ('name');
 * get data, using array access: echo $cfg['name'];
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Configuration
 */
class Clansuite_Config_INIHandler extends Clansuite_Config_Base implements ArrayAccess
{
    /**
     * Configuration Array
     * protected-> only visible to childs
     *
     * @var array
     */
    protected $config = array();
    
    /**
     * Can either be INI_SCANNER_NORMAL (default) or INI_SCANNER_RAW. 
     * 
     * @var string
     */
    private static $scanner_mode;

    /**
     * CONSTRUCTOR
     * sets up all variables
     */
    public function __construct($filename = 'configuration/clansuite.config.php')
    {
        $this->config = self::readConfig($filename);
    }

    /**
     * Clansuite_Config_INIHandler is a Singleton
     *
     * @param object $filename Filename
     *
     * @return instance of Config_INIHandler class
     */
    public static function getInstance($filename)
    {
    	static $instance;

        if ( ! isset($instance))
        {
            $instance = new Clansuite_Config_INIHandler($filename);
        }

        return $instance;
    }

    /**
     * Set ScannerMode for parse_ini_file
     * Can either be INI_SCANNER_NORMAL (default) or INI_SCANNER_RAW. 
     *
     * @param $scanner_mode
     * @return this
     */    
    public static function setScannerMode($scanner_mode)
    {
        self::$scannerMode = $scanner_mode;
    }

    /**
     * Writes a .ini Configfile
     * This method writes the configuration values specified to the filename.
     *
     * @param string $ini_filename Filename of .ini to write
     * @param array $assoc_array Associative Array with Ini-Values
     *
     * @return mixed/boolean Returns the amount of bytes written to the file, or FALSE on failure.
     */
    public static function writeConfig($ini_filename, array $assoc_array)
    {
       # debug incomming array
       #clansuite_xdebug::printR($assoc_array);
       if(is_array($assoc_array) == false)
       {
           #@todo exit gracefully
           exit('writeConfig Parameter $assoc_array is not an array.');
       }
       

       # when ini_filename exists, get old config array
       if(is_file($ini_filename))
       {
           $old_config_array = self::readConfig($ini_filename);

           # array merge: overwrite the array to the left, with the array to the right, when keys identical
           # array_merge_recursive ??
           $config_array = array_merge($old_config_array, $assoc_array);
       }
       else 
       {
           # create file
           touch($ini_filename);
           
           # the config array = the incoming assoc_array
           $config_array = $assoc_array;
       }

       # attach an security header at the top of the ini file
       $content = '';
       $content = "; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>\n";
       $content .= "; \n";
       $content .= "; Clansuite Configuration File : \n";
       $content .= "; $ini_filename \n";
       $content .= ";\n\n";

        # loop over every array element
        foreach($config_array as $key => $item)
        {
            # checking if it's an array
            if(is_array($item))
            {
                # if key not empty .. hmm? does this case occur?
                if($key != '')
                {
                    # write an comment header block
                    $content .= "\n;----------------------------------------\n";
                    $content .= "; {$key}\n";
                    $content .= ";----------------------------------------\n";

                    # write an parseable [array_header] block
                    $content .= "[{$key}]\n";
                }

                # for every element after that
                foreach ($item as $key2 => $item2)
                {
                    if(is_numeric($item2) || is_bool($item2))
                    {
                        # write numeric and boolean values without quotes
                        $content .= "{$key2} = {$item2}\n";
                    }
                    else
                    {
                        # write value with quotes
                        $content .= "{$key2} = \"{$item2}\"\n";
                    }
                }
            }
            # if it's not an array
            else
            {
                if(is_numeric($item) || is_bool($item))
                {
                    # write numeric and boolean values without quotes
                    $content .= "{$key} = {$item}\n";
                }
                else
                {
                    # write value with quotes
                    $content .= "{$key} = \"{$item}\"\n";
                }
            }
        }

        # add php closing tag
        $content .= "\n; DO NOT REMOVE THIS LINE */ ?>";

        if (is_writable($ini_filename))
        {
            if (!$filehandle = fopen($ini_filename, 'wb'))
            {
                 echo "Kann die Datei $ini_filename nicht Ã¶ffnen";
                 return false;
            }

            if (!fwrite($filehandle, $content))
            {
                echo "Kann in die Datei $ini_filename nicht schreiben";
                return false;

            }
            fclose($filehandle);
            return true;
        }
        else
        {
            echo "Die Datei $ini_filename ist nicht schreibbar. Datei- und Verzeichnisschreibrechte vergeben!";
            return false;
        }
    }

    /**
     *  Read the complete config file *.ini.php
     *
     * @access  public
     * @param   string  The filename
     * @return  array | boolean false
     */
    public static function readConfig($filename)
    {
        # check ini_filename exists
        if(is_file($filename) == false)
        {
            throw new Clansuite_Exception('File not found: '.$filename, 4);    
        }
        
        /**
         * check if we have a scannermode set
         */
        if(is_null(self::$scanner_mode))
        {
            /**
             * egro the default mode INI_SCANNER_NORMAL is active
             * we don't need to set the third param
             */            
            return parse_ini_file($filename, true);
        }
        else # ok, we have a scanner_mode, set it
        {
            return parse_ini_file($filename, true, self::$scanner_mode);
        }

        return false;
    }
}
?>