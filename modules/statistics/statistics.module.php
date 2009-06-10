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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: index.module.php 2625 2008-12-09 00:04:43Z vain $
    */

// Security Handler
if (!defined('IN_CS')){die('Clansuite not loaded. Direct Access forbidden.');}

/**
 * Clansuite Module - wwwstats
 *
 * @author     Jens-André Koch <vain@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards)
 */
class Module_statistics extends Clansuite_ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_News -> Execute
     */
    public function execute(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
    }

    /**
     * This fetches the statistics from db and returns them as array.
     *
     * @return stats array
     */
    private static function fetch_wwwstats()
    {
        $stats = array();
        $stats['all_impressions']        = 0;
        $stats['page_impressions']       = 0;
        $stats['today_impressions']      = 0;
        $stats['yesterday_impressions']  = 0;
        $stats['month_impressions']      = 0;

        /**
         * Number of online users (equals sessions number)
         */
        $sessions_online = 0;
        $sessions_online = Doctrine_Query::create()
                                        ->select('COUNT(s.session_id) online')
                                        ->from('CsSession s')
                                        ->execute(array(), Doctrine::HYDRATE_ARRAY);

        $stats['online'] = $sessions_online[0]['online'];

        /**
         * Number of authenticated users (user_id not 0) in session table
         */
        $authed_user_session  = 0;
        $authed_user_session  = Doctrine_Query::create()
                                ->select('COUNT(s.session_id) authed_users')
                                ->from('CsSession s')
                                ->where('user_id != 0')
                                ->execute(array(), Doctrine::HYDRATE_ARRAY);

        $stats['authed_users'] = $authed_user_session[0]['authed_users'];

        /**
         * Calculate number of guests, based on total users subtracted by authed users
         */
        $stats['guest_users'] = (int) $sessions_online - (int) $authed_user_session;

        /**
         * Who is online?
         * Select Session's IDS and Nicks for USERID > 0 (no guests) BUT not hidden ones
         */
        /*$stmt = $this->db->prepare( 'SELECT table1.user_id, table1.session_where, table2.nick
                                     FROM ' . DB_PREFIX .'session AS table1
                                     LEFT JOIN ' . DB_PREFIX .'users AS table2 ON table1.user_id = table2.user_id
                                     WHERE table1.user_id != 0 AND table1.session_visibility = 1' );
        $stmt->execute();
        $stats['whoisonline'] = $stmt->fetchALL(PDO::FETCH_ASSOC);*/

        return $stats;
    }



    public function widget_statistics($params)
    {
        $smarty = $this->getView();
        $smarty->assign('stats', self::fetch_wwwstats());
    }
}
?>