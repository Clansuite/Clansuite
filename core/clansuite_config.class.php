<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-onwards
    * http://www.clansuite.com/
    *
    * File:         config.class.php
    * Requires:     PHP5+
    *
    * Purpose:      Variable Configuration and Settings Class
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id: clansuite.config.php 2009 2008-05-07 15:34:26Z xsign $
    */

   /**  =====================================================================
    *  WARNING: DO NOT MODIFY THIS FILE, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *           READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('Clansuite Framework not loaded. Direct Access forbidden.' );}

/**
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
 * @package     clansuite
 * @category    core
 * @subpackage  config
 * @todo COMMENT by vain: maybe change this class to a ini or yaml file? but that would add overhead when loading!
 * @todo  by vain: add set/get via database if not found in mainarray! save changes on destruct?
 */
class Clansuite_Config implements ArrayAccess
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
    public function __construct($filename = 'clansuite.config.php')
    {
        $this->config = self::readConfig($filename);
    }

    /**
     * Writes a .ini Configfile
     * This method writes the configuration values specified to the filename.
     *
     * @param string $ini_filename Filename of .ini to write
     * @param array $assoc_array Associative Array with Ini-Values
     * @access public
     *
     * @return mixed/boolean Returns the amount of bytes written to the file, or FALSE on failure.
     */
    public function writeConfig($ini_filename, $assoc_array)
    {
       # get old Config Array, when such a ini_filename exists
       if(is_file($ini_filename))
       {
           $old_config_array = $this->readConfig($ini_filename);

           # + operator usage: overwrite the array to the left, with the array to the right, when keys identical
           #$assoc_array = $old_config_array + $assoc_array;
           
           $assoc_array = array_merge($old_config_array, $assoc_array);
       }

       # attach an security header at the top of the ini file
       $content = '';
       $content = "; <?php die( 'Access forbidden.' ); /* DO NOT MODIFY THIS LINE! ?>\n";
       $content .= "; \n";
       $content .= "; Clansuite Configuration File : \n";
       $content .= "; $ini_filename \n";
       $content .= ";\n\n";

        # loop over every array element
        foreach($assoc_array as $key => $item)
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
        $content .= "; DO NOT REMOVE THIS LINE */ ?>";

        # Write data to config file
        return @file_put_contents($ini_filename, $content);
    }

    /**
     *  Read the complete config file *.ini.php
     *
     * @access  public
     * @param   string  The filename
     * @return  array
     */
    public static function readConfig($filename)
    {
        return self::_manageKeys(parse_ini_file($filename, true));
    }

    /**
     * Manage keys with SPL Iterator to minimize memory consumption of large arrays
     *
     * @access  public
     * @param   array   The ini array
     * @return  array
     */
    private static function _manageKeys($ini)
    {
        try
        {
            $object = new ArrayIterator($ini);
            foreach($object as $key=>$value)
            {
                if( is_array($value) )
                {
                    self::_manageKeys($value);
                }
                else
                {
                    $ini[self::_getKey($key)] = self::_getValue($value);
                }
            }
        }
        catch (Exception $e)
        {
            throw new clansuite_exception( $e, 'The ArrayIterator failed!', 200);
            exit;
        }

        return $ini;
    }

    /**
     * Get a safe/single value and convert values to bool,int,float
     *
     * @access  private
     * @param   string  The value that should be converted
     * @return  mixed
     */
    private static function _getValue($value)
    {
        if (preg_match('/^-?[0-9]+$/i', $value)) { return (int)$value; }
        else if (strtolower($value) === 'true') { return true; }
        else if (strtolower($value) === 'false') { return false; }
        else if ( ((string)(float)$value) == $value ) { return (float)$value; }
        else if (preg_match('/^"(.*)"$/i', $value, $m)) { return $m[1]; }
        else if (preg_match('/^\'(.*)\'$/i', $value, $m)) { return $m[1]; }
        return $value;
    }

    /**
     *  Get a single Key
     *
     * @access  private
     * @param   string  The single key
     * @return  string  The key (int when available)
     */
    private static function _getKey($key)
    {
        if (preg_match('/^[0-9]+$/i', $key)) { return (int)$key; }
        return $key;
    }

    /**
     * Gets a config file item based on keyname
     *
     * @access   public
     * @param    string    the config item key
     * @return   void
     */
    public function __get($configkey)
    {
        return isset($this->config[$configkey]) ? $this->config[$configkey] : null;
    }

    /**
     * Set a config file item based on key:value
     *
     * @access   public
     * @param    string    the config item key
     * @param    string    the config item value
     * @return   void
     *
     */
    public function __set($configkey, $configvalue)
    {
        #if (isset($this->config[$configkey]) == true) {
        #       throw new Exception('Variable ' . $configkey . ' already set.');
        #}

        $this->config[$configkey] = $configvalue;
        return true;
    }

    // method that will allow 'isset' to work on these variables
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // method to allow 'unset' calls to work on these variables
    public function __unset($key)
    {
        unset($this->config[$key]);
        #echo 'Variable was unset:'. $key;
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    // hmm? why should configuration be unset?
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>