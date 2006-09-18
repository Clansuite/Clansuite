<?php
/**
 * permissions management
 *
 * PHP versions 5.1.4
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
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author     Florian Wolf <xsign.dll@clansuite.com>
 * @author     Jens-Andre Koch <vain@clansuite.com>
 * @copyright  2006 Clansuite Group
 * @license    see COPYING.txt
 * @version    SVN: $Id $
 * @link       http://gna.org/projects/clansuite
 * @since      File available since Release 0.1
 */

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

class permissions
{
    /**
    * @desc Check if the logged in user has the right - if not => Redirect!
    */
    function check( $right = '', $type = 'redirect', $redirect = 'index.php', $text = '' )
    {
        global $lang, $functions;
        
        if ( $_SESSION['user']['rights'][$right] == 1 )
        {
            return true;   
        }
        else
        {
            if( $type == 'redirect' )
            {
                $text = $text != '' ? $lang->t($text) : $lang->t('You do not have sufficient rights.');
                $functions->redirect( 'index.php', 'metatag|newsite', 5, $text );
            }
            else
            {
                return false;   
            }
        }
    }
}
?>