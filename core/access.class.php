<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
    * http://www.clansuite.com/
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
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  Jens-Andre Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite Core Class for Permissions Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  access
 */
class access
{
    /**
     * This checks, if the logged in user has the specified permission in his session table
     * and returns true otherwise it will redirect
     *
     * @param string $right contains the name of the right
     * @param string $type contains redirect
     * @param string $redirect contains the url for redirect
     * @param string $text contains the text to show
     * @return bool
     */
    function check( $right = '', $type = 'redirect', $redirect = 'index.php', $text = '' )
    {
        //Check if the $right is in session array of user
        if ( $_SESSION['user']['rights'][$right] == 1 )
        {
            // Proceed, if right was found in session array
            return true;
        }
        else
        {
            // no redirect
            if( $type == 'no_redirect' )
            {
                return false;
            }

            // redirect
            if( $type == 'redirect' )
            {
                _('You do not have sufficient rights.');

                // redirect to index.php
                # @todo: redirect method 
                redirect( 'index.php', 'metatag|newsite', 3, $text );
            }

            // die
            if( $type == 'die' )
            {
                _('You do not have sufficient rights.');

                // OFF!
                die( $text );
            }
        }
    }
}
?>