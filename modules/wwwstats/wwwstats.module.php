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
    * @copyright  Copyleft: All rights reserved. Jens-Andre Koch (2005-onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.2
    *
    * @version    SVN: $Id: news.module.php 2345 2008-08-02 04:35:23Z vain $
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * Clansuite
 *
 * Module:      wwwstats
 *
 */
class Module_wwwstats extends ModuleController implements Clansuite_Module_Interface
{
    /**
     * Module_News -> Execute
     */
    public function execute(httprequest $request, httpresponse $response)
    {
        # proceed to the requested action
        $this->processActionController($request);
    }
    
    /**
     * This fetches the statistics from db and returns them as array.
     *
     * @return stats array
     */
    public static function fetch_wwwstats()
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
                                        ->from('CSSession s')
                                        ->execute(array(), Doctrine::FETCH_ARRAY);
        
        $stats['online'] = $sessions_online[0]['online'];

        /**
         * Number of authenticated users (user_id not 0) in session table
         */
        $authed_user_session  = 0;
        $authed_user_session  = Doctrine_Query::create()
                                ->select('COUNT(s.session_id) authed_users')
                                ->from('CSSession s')
                                ->where('user_id != 0')                                                                         
                                ->execute(array(), Doctrine::FETCH_ARRAY);
        
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
    
    
    
    public function widget_wwwstats($params, &$smarty)
    {       
        $smarty->assign('stats', self::fetch_wwwstats());
        
        # check for theme tpl / else take module tpl
        if($smarty->template_exists('wwwstats/wwwstats_widget.tpl'))
        {
            echo $smarty->fetch('wwwstats/wwwstats_widget.tpl');
        }
        else
        {
            echo $smarty->fetch('wwwstats/templates/wwwstats_widget.tpl');
        }
    }
}
?>