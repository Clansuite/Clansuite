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
    *
    * @author     Jens-Andr� Koch <vain@clansuite.com>
    * @copyright  Jens-Andr� Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

# Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.' );
}

/**
 * Clansuite Filter - Update Visitor Statistics
 *
 * Purpose: this updates the statistics with the data of the current visitor
 *
 * @author: raensen
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class Clansuite_Filter_Statistics implements Clansuite_Filter_Interface
{
    private $config = null;
    private $user = null;
    private $curTimestamp = null;
    private $curDate = null;
    private $statsWhoDeleteTime = null;
    private $statsWhoTimeout = null;

    function __construct(Clansuite_Config $config, Clansuite_User $user)
    {
        $this->config = $config;
        $this->curTimestamp = time();
        $this->curDate = date('d.m.Y', $this->curTimestamp);
        $this->user = $user;

        # Load Models
        $models_path = ROOT_MOD . 'statistics/model/records';
        Doctrine::loadModels($models_path);

        $cfg = $config->readModuleConfig('statistics');
        $this->statsWhoDeleteTime = $cfg['statistics']['deleteTimeWho'];
        $this->statsWhoTimeout = $cfg['statistics']['timoutWho'];
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # take the initiative or pass through (do nothing)
        if (isset ($this->config['statistics']['enabled']) and $this->config['statistics']['enabled'] == 1)
        {
            #######################
            # at the moment we do not need to use this libary !!!
            #######################
            # aquire pieces of informtion from current visitor
            /**
             * Determine the client's browser and system information based on the HTTP
             * with PHPSniff by Roger Raymond.
             *
             * @link http://phpsniff.sourceforge.net/
             * @link http://phpsniff.sourceforge.net/docs/
             */
            # load library
            #require_once ROOT_LIBRARIES . '/phpSniffer/phpSniff.class.php';
            # instantiate phpsniff
            #$phpSniff = new phpSniff($_SERVER['HTTP_USER_AGENT']);

            /**
             *The Who logics, must be processed in a seperate filter
             */
            Doctrine::getTable('CsStatistic')->deleteWhoEntriesOlderThen($this->statsWhoDeleteTime);
            $this->updateStatistics($request->getRemoteAddress());
            $this->updateWhoTables($request->getRemoteAddress(), $request->getRequestURI());
        }
    }

    /**
     * update and/or create/insert a entry to the WhoIs and WhoWasOnline Tables
     *
     * @param String visitorIp
     * @param String targetSite
     */
    private function updateWhoTables($visitorIp, $targetSite)
    {
        #Original if statement CHECK if user is an admin
        if ($this->user->isUserAuthed())
        {
            $this->updateWhoIs($visitorIp, $targetSite, $this->user->getUserIdFromSession());
        }
        else
        {
            $this->updateWhoIs($visitorIp, $targetSite);
        }
    }

    /**
     * updateWhoIs
     *
     * @param $ip
     * @param $targetSite
     * @param $userID
     */
    private function updateWhoIs($ip, $targetSite, $userID = null)
    {
        $curTimestamp = $this->curTimestamp;

        $result = Doctrine::getTable('CsStatistic')->updateWhoIsOnline($ip, $targetSite, $curTimestamp, $userID);

        if ($result == 0)
        {
            Doctrine::getTable('CsStatistic')->insertWhoIsOnline($ip, $targetSite, $curTimestamp, $userID);
        }
    }

    /**
     * updateStatistics
     *
     * @param $visitorIp
     */
    private function updateStatistics($visitorIp)
    {
        # if there is no entry for this ip, increment hits
        if (false == Doctrine::getTable('CsStatistic')->existsIpEntryWithIp($visitorIp))
        {
            Doctrine::getTable('CsStatistic')->incrementHitsByOne();
            $this->updateStatisticStats();
        }

        $userOnline = Doctrine::getTable('CsStatistic')->countVisitorsOnline($this->statsWhoTimeout);

        Doctrine::getTable('CsStatistic')->updateStatisticMaxUsers($userOnline);
    }

    /**
     * updateStatisticStats
     */
    private function updateStatisticStats()
    {
        if (Doctrine::getTable('CsStatistic')->existsStatsEntryWithDate($this->curDate))
        {
            Doctrine::getTable('CsStatistic')->incrementStatsWithDateByOne($this->curDate);
        }
        else
        {
            Doctrine::getTable('CsStatistic')->insertStats();
        }

    }
}
?>