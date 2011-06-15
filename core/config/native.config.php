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

class Clansuite_Config_Native
{
    /**
     * Read the config array from php file
     *
     * @param   string  The filename
     *
     * @return  mixed array | boolean false
     */
    public static function readConfig($filename)
    {
        if(is_file($filename) === false or is_readable($filename) === false)
        {
            throw new Clansuite_Exception('PHP Array Config File not existing or not readable.');
        }

        # it's a native php array structure so we simply include the file
        include $filename;
    }

    public static function writeConfig($filename, array $array)
    {
        # transform the datastructure to a more readable format
        $readable_array = var_export($array, true);

        # write to file
        file_put_contents($file, '<?php return ' . $readable_array . '; ?>');
    }
}
?>