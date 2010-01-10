<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andr� Koch � 2005 - onwards
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
    * @author     Jens-Andr� Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards), Florian Wolf (2006-2007)
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
 * Clansuite Core File - Config Handler for Database Format
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Configuration
 */
class Clansuite_Config_DatabaseHandler extends Clansuite_Config_Base implements ArrayAccess
{
     /**
     * Configuration Array
     * protected-> only visible to childs
     *
     * @var array
     */
    protected $config = array();

    /**
     * CONSTRUCTOR
     * sets up all variables
     */
    public function __construct($filename)
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
            $instance = new Clansuite_Config_DatabaseHandler($filename);
        }

        return $instance;
    }

    /**
     * Write the configarray to the database
     *
     * @param   string  The filename
     *
     * @return  mixed array | boolean false
     */
    public static function writeConfig($filename, $assoc_array)
    {
        # @todo writeConfig to Database via Doctrine
    }

    /**
     *  Read the complete config array from database
     *
     * @param   string  The filename
     *
     * @return  mixed array | boolean false
     */
    public static function readConfig($filename)
    {
        # @todo readConfig from Database via Doctrine
    }
}
?>