<?php

class CsStatisticTable extends Doctrine_Table
{
	
	public function fetchAllImpressionsAndMaxVisitors()
	{
		$query = Doctrine_Query::create()
						->select("*")
						->from("CsStatistic")
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
		
		if(isset($query[0]) and count($query) == 1)
		{
		    return $query[0];   
		}
		else
		{
		    return $query = array( 'hits' => '0', 'maxonline' => '0');   
		}
	}
	
	public function fetchTodayAndYesterdayVisitors()
	{
		$dateToday = date("d.m.Y");
		$dateYesterday = date("d.m.Y" ,time()-86400);
		
		$query = Doctrine_Query::create()
						->select("count")
						->from("CsStatisticStats")
						->where("dates = ? or dates = ?", array($dateToday, $dateYesterday))
						->setHydrationMode(Doctrine::HYDRATE_ARRAY)
						->execute();
		if(isset($query[0]) and isset($query[1]) and count($query) == 2)
		{
		    return $query;   
		}
		else
		{					
		    return $query = array(array( 'count' => '0') , array( 'count' => '0'));   
		}
	}
	
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
								->set("site", "?", $targetSite)
								->set("date", "?", date("d.m.Y"))
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
								->set("date", "?", date("d.m.Y"))
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
			$whoIsIns->date = date("d.m.Y");
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
	
	public function countVisitorsOnline($timeoutMinutes)
	{
		$delTime = time()-(60*$timeoutMinutes);
		$query = Doctrine_Query::create()
						->select("count(*) as sum")
						->from("CsWhoisonline")
						->where("time > ?", array($delTime))
						->execute();
		return $query[0]['sum'];
	}
	
	public function countUsersOnline($timeoutMinutes)
	{
		$delTime = time()-(60*$timeoutMinutes);
		$query = Doctrine_Query::create()
						->select("count(*) as sum")
						->from("CsWhoisonline")
						->where("time > ? and (userID > 0 or userID is not NULL)", array($delTime))
						->execute();
		return $query[0]['sum'];
	}
	
	public function sumMonthVisits()
	{
		$date = "%";
		$date .= date(".m.Y");
		$query = Doctrine_Query::create()
				->select("sum(count) as sum")
				->from("CsStatisticStats")
				->where("dates like ?", array($date))
				->setHydrationMode(Doctrine::HYDRATE_ARRAY)
				->execute();	
		return $query[0]['sum'];
	}
}