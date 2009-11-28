<?php

class CsStatisticTable extends Doctrine_Table
{
	
	public function deleteWhoEntriesOlderThen($days)
	{
		#Days to seconds
		#curTime-Seconds (Days) 
		$SecsToSubstract = time()-($days*24*60*60); 
		#delete entries older then the $days from WhoIsOnline
		$query = Doctrine_Query::create()
						->delete("CsWhoisonline")
						->where("time < ?", array($SecsToSubstract))
						->execute();
	}
	
	public function deleteIpEntriesOlderThen($days)
	{
		#delete old entries
		$SecsToSubstract = time()-($days*24*60*60);
		$query = Doctrine_Query::create()
						->delete("CsStatisticIp")
						->where("del < ?", array($SecsToSubstract))
						->execute();
	}
	
	/**
	 * @return num rows affected
	 */
	public function updateWhoIsOnline($ip, $targetSite, $curTimestamp, $userID = null)
	{
		$query = null;
		if ($userID == null) {
			$query = Doctrine_Query::create()
						 		->update('CsWhoisonline')
					 			->set('userID', 'NULL')
					 			->set('ip', '?', $ip)
								->set('time', $curTimestamp)
								->set('site', '?', $targetSite)
								->where('ip = ?', array($ip))
								->execute();
		}
		else {
			$query = Doctrine_Query::create()
						 		->update('CsWhoisonline')
					 			->set('userID', $userID)
					 			->set('ip', '?', $ip)
								->set('time', $curTimestamp)
								->set('site', '?', $targetSite)
								->where('userID = ? or ip = ?', array($userID, $ip))
								->execute();
		}
		return $query;
	}
	
	public function updateStatisticMaxUsers($num)
	{
		$query = Doctrine_Query::create()
						->update('CsStatistic')
						->set('maxonline', $num)
						->where('maxonline < ?', array($num))
						->execute();
	}
	
	public function insertWhoIsOnline($ip, $targetSite, $curTimestamp, $userID = null)
	{
			$whoIsIns = new CsWhoisonline();
			$whoIsIns->time = $curTimestamp;
			$whoIsIns->userID = $userID;
			$whoIsIns->site = $targetSite;
			$whoIsIns->ip = $ip;
			$whoIsIns->save();
	}
	
	public function insertIp($curTimestamp, $ip)
	{
		$ipListIns = new CsStatisticip();
		$ipListIns->dates = date("d.m.Y");
		$ipListIns->del = $curTimestamp;
		$ipListIns->ip = $ip;
		$ipListIns->save();	
	}
	
	public function insertStats()
	{
    	$statsIns = new CsStatisticStats();
    	$statsIns->dates = date("d.m.Y");
    	$statsIns->count = 1;
    	$statsIns->save(); 	
	}
	
	public function existsIpEntryWithIp($ip)
	{
		$bRes = true;
		$date = date("d.m.Y");
		$query = Doctrine_Query::create()
			 			->select("count(*) as sum")
						->from("CsWhoisonline")
						->where("ip = ? and date = ?", array($ip, $date))
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
		if($query[0]['sum']==0) {
			$bRes = false;
		}
		return $bRes;
	}
	
	public function existsStatsEntryWithDate($date)
	{
		$bRes = true;
		$query = Doctrine_Query::create()
		    			->select("count(*) as sum")
						->from("CsStatisticStats")
						->where("dates = ?", array($date))
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
		if($query[0]['sum']==0) {
			$bRes = false;
		}
		return $bRes;
	}
	
	public function incrementHitsByOne()
	{
	   	#update the statistic hits
	    Doctrine_Query::create()
				->update('CsStatistic')
				->set('hits', 'hits + 1')
				->execute();
	}
	
		
	public function incrementStatsWithDateByOne($date)
	{
	    Doctrine_Query::create()
				->update('CsStatisticStats')
				->set('count', 'count + 1')
				->where('dates = ?', array($date))
				->execute();
	}
	
	public function countUsersOnline()
	{
		$query = Doctrine_Query::create()
						->select("count(*) as sum")
						->from("CsWhoisonline")
						->execute();
		return $query[0]['sum'];
	}
}