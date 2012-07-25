<?php defined('IN_CS') or exit('Direct Access forbidden.');

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Module;

/**
 * Clansuite_Module_Statistics
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  Clansuite_Module_Statistics
 */
class Statistics extends Controller
{
    /**
     * This fetches the statistics from db and returns them as array.
     *
     * @return stats array
     */
    private function fetch_statistic()
    {
        $stats = array ();

        /**
         * All visits & max users online counter
         */

        $temp_array = Doctrine::getTable('CsStatistic')->fetchAllImpressionsAndMaxVisitors();
        $stats['all_impressions'] = $temp_array['hits'];
        $stats['max_visitor'] = $temp_array['maxonline'];

        $temp_array = Doctrine::getTable('CsStatistic')->fetchTodayAndYesterdayVisitors();
        $stats['today_impressions'] = $temp_array[1]['count'];
        $stats['yesterday_impressions'] = $temp_array[0]['count'];

        $temp_array = Doctrine::getTable('CsStatistic')->sumMonthVisits();
        $stats['month_impressions'] = $temp_array;

        /**
         * Number of online users (equals sessions number)
         */
        $sessions_online = 0;
        $sessions_online = Doctrine::getTable('CsStatistic')->countVisitorsOnline(self::getConfigValue('timoutWho', '5'));
        $stats['online'] = $sessions_online;

        /**
         * Number of authenticated users (user_id not 0) in session table
         */
        $authed_user_session = 0;
        $authed_user_session = Doctrine::getTable('CsStatistic')->countUsersOnline(self::getConfigValue('timoutWho', '5'));
        $stats['authed_users'] = $authed_user_session;

        /**
         * Calculate number of guests, based on total users subtracted by authed users
         */
        $stats['guest_users'] = $sessions_online - $authed_user_session;

        return $stats;
    }

    public function widget_statistics($params)
    {
        $this->getView()->assign('stats', self::fetch_statistic());
    }
}
?>