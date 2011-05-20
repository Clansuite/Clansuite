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
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') === false)
{
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Filter - Startup Checks
 *
 * Purpose: Perform Various Startup Check before running a Clansuite Module.
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_StartupChecks implements Clansuite_Filter_Interface
{
    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # Check if Smarty Output Dirs do EXIST
        if(false === is_dir(ROOT_CACHE . 'templates_c') and (false === @mkdir(ROOT_CACHE .'templates_c', 0755, true)))
        {
            throw new Clansuite_Exception('Smarty Template Directories not existant.', 9);

            # @todo else # Log-Entry: "Created Directories Cache/Templates_C."
        }

        # Check if Smarty Output Dirs do EXIST
        if(false === is_dir(ROOT_CACHE . 'cache') and (false === @mkdir(ROOT_CACHE .'cache', 0755, true)))
        {
            throw new Clansuite_Exception('Smarty Template Directories not existant.', 9);

            # @todo else Log-Entry: "Created Directory Cache/Cache."
        }

        # Check if Smarty Output Dirs are WRITEABLE
        if(false === is_writable(ROOT_CACHE . 'templates_c') or false === is_writable(ROOT_CACHE . 'cache'))
        {
            # try to set permissions on the folders, throw exception if it fails
            if((false === chmod(ROOT_CACHE . 'templates_c', 0755)) and (false === chmod(ROOT_CACHE . 'cache', 0755)))
            {
                throw new Clansuite_Exception('Smarty Template Directories not writable.', 10);
            }
            # @todo else # Log-Entry: "CHMOD 0755 applied on /cache and /templates_c."
        }
    }
}
?>