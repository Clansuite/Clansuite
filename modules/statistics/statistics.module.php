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

        /**
         * All visits & max users online counter
         */
        $tmpVar = Doctrine::getTable('CsStatistic')->fetchAllImpressionsAndMaxVisitors();
        $stats['all_impressions']        = $tmpVar['hits'];
        $stats['max_visitor']            = $tmpVar['maxonline'];

        $tmpVar = Doctrine::getTable('CsStatistic')->fetchTodayAndYesterdayVisitors();
        $stats['today_impressions']      = $tmpVar[1]['count'];
        $stats['yesterday_impressions']  = $tmpVar[0]['count'];

        $tmpVar = Doctrine::getTable('CsStatistic')->sumMonthVisits();
        $stats['month_impressions']      = $tmpVar;

        /**
         * Number of online users (equals sessions number)
         */
        $sessions_online = 0;
        $sessions_online = Doctrine::getTable('CsStatistic')->countVisitorsOnline(5);
        $stats['online'] = $sessions_online;

        /**
         * Number of authenticated users (user_id not 0) in session table
         */
        $authed_user_session  = 0;
        $authed_user_session  = Doctrine::getTable('CsStatistic')->countUsersOnline(5);

        $stats['authed_users'] = $authed_user_session;

        /**
         * Calculate number of guests, based on total users subtracted by authed users
         */
        $stats['guest_users'] = (int) $sessions_online - (int) $authed_user_session;

        return $stats;
    }

    public function widget_statistics($params)
    {
        $smarty = $this->getView();
        $smarty->assign('stats', self::fetch_wwwstats());
    }
}
?>