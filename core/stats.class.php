<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         session.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Session Handling
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
 *  Security Handler
 */
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

/**
 * This is the Clansuite Core Class for Statistics
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  statistics
 */

class statistics
{
    /**
     * This fetches the statistics from db and assigns the vars to tpl
     *
     * @global array $tpl
     * @global object $db
     */

    function assign_statistic_vars()
    {
        global $tpl, $db;

        $stats['all_impressions'] = 0;

        $stats['page_impressions'] = 0;

        /**
         * Number of online users (equals sessions number)
         */

        $stmt = $db->prepare( 'SELECT COUNT(session_id) FROM ' . DB_PREFIX .'session' );
        $stmt->execute();
        $stats['online'] = $stmt->fetch(PDO::FETCH_COLUMN);


        /**
         * Number of authenticated users (user_id not 0)
         */

        $stmt = $db->prepare( 'SELECT COUNT(session_id) FROM ' . DB_PREFIX .'session WHERE user_id != 0' );
        $stmt->execute();
        $stats['authed_users'] = $stmt->fetch(PDO::FETCH_COLUMN);

        /**
         * Calculate number of guests, based on total users subtracted by authed users
         */
        $stats['guest_users'] = $stats['online'] - $stats['authed_users'];

        /**
         * Assign $stats Array
         */

        $tpl->assign('stats' , $stats );
    }
}
?>