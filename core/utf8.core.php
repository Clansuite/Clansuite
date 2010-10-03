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
    * @copyright  Jens-André Koch (2005 - onwards)
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
 * Clansuite UTF8
 *
 * Clansuite relies on mbstring.
 * This class allows running the application without the mbstring extension.
 * It loads functional replacements for the mbstring methods.
 * UTF8 functions and lookup tables are based on the Dokuwiki UTF-8 library written by Andreas Gohr.
 * @link http://github.com/splitbrain/dokuwiki/raw/master/inc/utf8.php
 *
 * @author     Paul Brand
 * @author     Jens-André Koch
 */
class Clansuite_UTF8
{
    public static function initialize()
    {
        # detect, if the mbstring extension is loaded and set flag constant
        define('UTF8_MBSTRING', extension_loaded('mbstring'));

        # mbstring extension is loaded
        if(UTF8_MBSTRING === true)
        {
            # we do not accept mbstring function overloading set in php.ini
            if(ini_get('mbstring.func_overload') & MB_OVERLOAD_STRING)
            {
                trigger_error('The string functions are overloaded by mbstring. Please stop that.
                               Check php.ini - setting: mbstring.func_overload.', E_USER_ERROR);
            }

            # if not already set, the internal encoding is now UTF-8
            mb_internal_encoding('UTF-8');

        }
        else # mbstring extension is NOT loaded
        {
            # load functional replacements for mbstring functions
            include ROOT_CORE . 'utf8/mbstring.wrapper.php';

            # load utf-8 character tables for lookups
            include ROOT_CORE . 'utf8/utf8tables.php';

            # load utf8 functions
            include ROOT_CORE . 'utf8/utf8.php';
        }
    }
}
?>