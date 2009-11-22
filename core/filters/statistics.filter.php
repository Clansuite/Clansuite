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

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' ); }

/**
 * Clansuite Filter - Update Visitor Statistics
 *
 * Purpose: this updates the statistics with the data of the current visitor
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Filters
 * @implements  Clansuite_Filter_Interface
 */
class statistics implements Clansuite_Filter_Interface
{
	/**
	 * UNIX timestamp (seconds) for the range of a day
	 */
	private static $TIME_SUBSTRACT_TO_YESTERDAY = 86400;
	/**
	 * timeout in seconds
	 */
	private static $TIMEOUT = 300;
	
    private $m_config     	= null;
    private $m_UserCore 	= null;
    private $m_statistics 	= null;
    
    private $m_curTimestamp 	= null;
    private $m_curDate 			= null;
    private $m_delTimeYesterday = null;
    private $m_delTimeout 		= null;

    function __construct(Clansuite_Config $config, Clansuite_User $user)
    {
       $this->m_config     		 = $config;
       $this->m_curTimestamp 	 = time();
       $this->m_curDate 		 = date("d.m.Y", $this->m_curTimestamp);
       $this->m_delTimeYesterday = ($this->m_curTimestamp)-(self::$TIME_SUBSTRACT_TO_YESTERDAY);
       $this->m_delTimeout 		 = ($this->m_curTimestamp)-(self::$TIMEOUT);
       $this->m_UserCore 		 = $user; 
    }

    public function executeFilter(Clansuite_HttpRequest $request, Clansuite_HttpResponse $response)
    {
        # take the initiative or pass through (do nothing)
        if(isset($this->m_config['statistics']['enabled']) and $this->m_config['statistics']['enabled'] == 1)
        {
        	#######################
        	# at the moment we do not need to use these libary !!!
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
		    #$phpSniff = new phpSniff($_SERVER["HTTP_USER_AGENT"]);
		    
		    /**
			 *The Who logics, must be processed in a seperate filter
		     */
			$this->clearWhoTables();
			$this->updateWhoTables($request->getRemoteAddress(), $request->getRequestURI());
			$this->updateStatistics($request->getRemoteAddress());
        }
    }

	/**
	 * clear old entries from the WhoIs and WhoWas tables
	 */    
    private function clearWhoTables()
    {
		//TODO: make Timeout configureable
		#delete entries older then the timeout from WhoIsOnline
		$query = Doctrine_Query::create()
						->delete("CsWhoisonline")
						->where("time < ?", array($this->m_delTimeYesterday))
						->execute();
						
		#delete entries older then the timeout from WhoWasOnline
		$query = Doctrine_Query::create()
						->delete("CsWhowasonline")
						->where("time < ?", array($this->m_delTimeout))
						->execute();
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
    	if($this->m_UserCore->isUserAuthed()) {
    	#Debug if statement
    	#if(true) {
    		#visitor is a registered user
    		
    		$userID = $this->m_UserCore->getUserIdFromSession();
    		
			$this->updateWhoIsWithUserId($userID, $targetSite);
			$this->updateWhoWas($userID, $targetSite);
		}
		else {
			#visitor is not registered
			$this->updateWhoIsWithIp($visitorIp, $targetSite);
		}
    }
    
    
    /**
     * Updates the WhoIsOnline table
     * 
     * The suffix WithUserId implies that the visitor must be a
     * registered user.
     */
    private function updateWhoIsWithUserId($userID, $targetSite)
    {
    	   	#Now Check the WhoIs Table
    		#check if the user entry must be updated or create it
    		$query = Doctrine_Query::create()
    						->select("count(*) as sum")
    						->from("CsWhoisonline")
    						->where("userID = ?", array($userID))
    						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        	->execute();
                        	
    		if($query[0]['sum'] > 0) {
				Doctrine_Query::create()
						->update('CsWhoisonline')
						->set('time', $this->m_curTimestamp)
						->set('site', '?', $targetSite)
						->where('userID = ?', array($userID))
						->execute();
			}
			else {
				$whoIsIns = new CsWhoisonline();
				$whoIsIns->time = $this->m_curTimestamp;
				$whoIsIns->userID = $userID;
				$whoIsIns->site = $targetSite;
				$whoIsIns->save();
			}
    }


	/**
	 * Updates the WhoIsOnline table
	 * 
	 * The suffix WithIp implies that the visitor is not
	 * registered. 
 	 */    
    private function updateWhoIsWithIp($visitorIp, $targetSite)
	{
	    #Now Check the WhoIs Table
	    #check if the user entry must be updated or create it
	    $query = Doctrine_Query::create()
				    	->select("count(*) as sum")
						->from("CsWhoisonline")
						->where("ip = ?", array($visitorIp))
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
	    
	    if($query[0]['sum'] > 0) {
		    Doctrine_Query::create()
				 	->update('CsWhoisonline')
					->set('time', $this->m_curTimestamp)
					->set('site', '?', $targetSite)
					->where('ip = ?', array($visitorIp))
					->execute();
	    }
	    else {
		    $whoIsIns = new CsWhoisonline();
		    $whoIsIns->time = $this->m_curTimestamp;
		    $whoIsIns->ip = $visitorIp;
		    $whoIsIns->site = $targetSite;
		    $whoIsIns->save();
	    }
	}
    
    /**
     * Updates the WhoWasOnline table
     * 
	 * WhoWas implies that the visitor must be an registered user.
	 * Because of these information, there is no need to suffix 
	 * the method name with WithUserId.
     */
    private function updateWhoWas($userID, $targetSite)
    {
    		#Now Check the WhoWas Table
    		#check if the user entry must be updated or create it
    		$query = Doctrine_Query::create()
    						->select("count(*) as sum")
    						->from("CsWhowasonline")
    						->where("userID = ?", array($userID))
    						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
                        	->execute();


    		if($query[0]['sum'] > 0) {
				Doctrine_Query::create()
						->update('CsWhowasonline')
						->set('time', $this->m_curTimestamp)
						->set('site', '?', $targetSite)
						->where('userID = ?', array($userID))
						->execute();
			}
			else {
				$whoIsIns = new CsWhowasonline();
				$whoIsIns->time = $this->m_curTimestamp;
				$whoIsIns->userID = $userID;
				$whoIsIns->site = $targetSite;
				$whoIsIns->save();
			}
    }
    
    /**
     * This method handled the statistics logic.
     * It deletes the old ips and updates the counter
     */
    private function updateStatistics($visitorIp)
    {
	    $this->clearIpList();
	    
	    $query = Doctrine_Query::create()
		    			->select("count(*) as sum")
						->from("CsStatisticIp")
						->where("ip = ?", array($visitorIp))
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
	    
	    if($query[0]['sum']==0) {
	    	#update the statistic hits
		    Doctrine_Query::create()
				->update('CsStatistic')
				->set('hits', 'hits + 1')
				->execute();

			# add ip to the List
			# to which it is not inserted twice				
			$ipListIns = new CsStatisticip();
			$ipListIns->dates = $this->m_curDate;
			$ipListIns->del = $this->m_curTimestamp;
			$ipListIns->ip = $visitorIp;
			$ipListIns->save();
			
			$this->updateStatisticStats();
	    }
    }
    
    /**
     * deletes entries older then one day from the ip table
     */
    private function clearIpList()
    {
    	#delete old entries
		$query = Doctrine_Query::create()
						->delete("CsStatisticIp")
						->where("del < ?", array($this->m_delTimeYesterday))
						->execute();
    }
    
    private function updateStatisticStats()
    {
    	$query = Doctrine_Query::create()
		    			->select("count(*) as sum")
						->from("CsStatisticStats")
						->where("dates = ?", array($this->m_curDate))
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
    	
	    if($query[0]['sum']==0) {
	    	
	    	$statsIns = new CsStatisticStats();
	    	$statsIns->dates = $this->m_curDate;
	    	$statsIns->count = 1;
	    	$statsIns->save(); 	
	    }
	    else {
	    	#update the statistic hits
		    Doctrine_Query::create()
				->update('CsStatisticStats')
				->set('count', 'count + 1')
				->where('dates = ?', array($this->m_curDate))
				->execute();
	    }
    }
}
?>