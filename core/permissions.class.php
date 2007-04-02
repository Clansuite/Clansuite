<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         permissions.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Trail / Breadcrumb Handling
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
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('You are not allowed to view this page.' );}

/**
 * This Clansuite Core Class for Permissions Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  permissions
 */
class permissions
{
    /**
     * This checks if the logged in user has the right in his session table
     * and returns true else it it will redirect
     *
     * @param string $right contains the name of the right
     * @param string $type contains redirect
     * @param string $redirect contains the url for redirect
     * @param string $text contains the text to show
     * @global $lang
     * @global $functions
     * @return bool
     *
     */
    function check( $right = '', $type = 'redirect', $redirect = 'index.php', $text = '' )
    {
        global $lang, $functions;

        /**
         * Check if $right is in session array of user
         */
        if ( $_SESSION['user']['rights'][$right] == 1 )
        {
            /**
             * Ok, right was found in session array
             */

            return true;
        }
        else
        {
            if( $type == 'redirect' )
            {
                /**
                 * This is a ternary operator
                 * It sets the text by param $text if given or takes the standard text otherwise
                 */

                $text = $text != '' ? $lang->t($text) : $lang->t('You do not have sufficient rights.');

                /**
                 * redirect to index.php
                 */
                $functions->redirect( 'index.php', 'metatag|newsite', 3, $text );
            }

            if( $type == 'no_redirect' )
            {
                return false;
            }

            if( $type == 'die' )
            {
                /**
                 * This is a ternary operator
                 * It sets the text by param $text if given or takes the standard text otherwise
                 */

                $text = $text != '' ? $lang->t($text) : $lang->t('You do not have sufficient rights.');

                /**
                 * die with text
                 */
                die( $text );
            }
        }
    }
}
?>