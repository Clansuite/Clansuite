<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

/* -------------------------------------------------------------------
Die class Session dient der Sessionverwaltung.
Die Variablen sind über das globale Array $_SESSION[variable] abrufbar.
Die Sessionvariablen werden hierbei in der MySQL-DB abgelegt, 
den Index bildet die Session-ID.

Die class.Session ruft sich selbst auf - siehe unten!
------------------------------------------------------------------------*/

class Session {
	
	### ini_sets
	var $_Session 			= array();
	var $SessionName 		= "suiteSID";
	var $SessionCookies 	= true;
	var $SessionExpireTime 	= 30; // minutes
	var $SessionProbability = 20;
	var $SessionCookiesOnly = true;
	var $SessionSecurity 	= array(	"CheckIp" => false, 
							  			"CheckBrowser" => false);
	### custom session handler
	function session() 
	{ 
	// set ini var to the whished values
	ini_set("session.save_handler",    	"user"                   );
	ini_set("session.name",				$this->SessionName       );
	ini_set("session.use_cookies",	   	$this->SessionCookies    );
	ini_set("session.gc_maxlifetime",  	$this->SessionExpireTime );
	ini_set("session.gc_probability",  	$this->SessionProbability);
	ini_set("session.use_only_cookies",	$this->SessionCookiesOnly);
	
	/*
	* set the sessionsavehandle
	* so user-functions could be filled in
	*/
	session_set_save_handler(
	array(&$this, "_SessionOpen"   ),
	array(&$this, "_SessionClose"  ),
	array(&$this, "_SessionRead"   ),
	array(&$this, "_SessionWrite"  ),
	array(&$this, "_SessionDestroy"),
	array(&$this, "_SessionGC"     ));
	
	// Session wird gestartet ... or Error!
	if(!session_start()) echo 'Session start failed!';
	#echo "<p>Session opened, Session-ID:" .session_id(). "</p>\n";
	
	
	// testeintrag .. daten in mysql-db sessions ablegen
	#if (!isset($_SESSION["test"])) {	$_SESSION["test"] = 'testeintrag';
	#print_r($_SESSION);
	#	}
	#else { $_SESSION["test"] = 'wastestbefore';}
	
	// Session assigned to Class-Var for easy handling (Globalisierung)
	$this->_Session =&$_SESSION; 
	
	if ( !$this->_SessionCheckSecurity()) { $this->_Session	= array(); }
	
	} //end function session
	
	
	/**
	* Opens a session
	* @access private
	* @param $save_path, $sess_name
	* @return bool
	*/
	function _SessionOpen($save_path, $sess_name)	
	{	return true;	}
	
	/**
	* Closes a session
	* @return boolen
	* @access private
	*/
	
	function _SessionClose()
	{ Session::_SessionGC (0); return true; 	}
	
	/**
	* gets the sessiondata from the database
	*
	* @param  $Id           sessionid
	* @return $ReturnValue  SessionData
	* @access private
	*/
	function _SessionRead ( $Id )
	{ global $Db;
	# assign false, if there was an error, we can info the user about it
	$ReturnValue = false;
	# get the sessiondata from database
	$Result	     = $Db->getOne("SELECT SessionData FROM " . DB_PREFIX . "session WHERE SessionName='" . $this->SessionName . "' AND SessionId='" . $_COOKIE['suiteSID'] . "'");
	# return the sessiondata, if it is not null
	if(!is_null($Result)) 	{	$ReturnValue = $Result;	}
	return $ReturnValue;
	}
	
	/**
	* writes session to the database
	*
	* @param  $Id           sessionid
	* @param  $Data         sessiondata
	* @return boolen
	* @access private
	*/
	
	function _SessionWrite( $Id, $Data)
	{ global $Db;
	$Seconds	= $this->SessionExpireTime * 60;
	$Expires	= time() + $Seconds; // d.h. jetzt + expire*60
	$SessionId      = $Db->GetOne("SELECT SessionId FROM " . DB_PREFIX . "session WHERE SessionId='" . session_id() . "'" );
	if ( $SessionId === $Id )
		{ // aktualisiert die jeweilige Session
		$Db->execute("UPDATE " . DB_PREFIX . "session SET SessionExpire = ?, SessionData= ? WHERE SessionId= ?", $Expires, $Data, $Id);
		Session::SessionControl();
		}
	else
		{
		// trägt eine neue! Session mit GUEST - STATUS ein 
		// user_id = 0 
		// visibility = 1 
		// d.h. die user_id wird nur durch login gesetzt		
		$Db->execute("INSERT INTO " . DB_PREFIX . "session SET SessionId = ?, SessionName = ?, SessionExpire = ?, SessionData = ?, SessionVisibility = ?, user_id = ?", $Id, $this->SessionName , $Expires , $Data , '1', '0');
		}
	return true;
	}		
	
	
	/**
	* removes a session from the database
	*
	* @param  $Id           sessionid
	* @access private
	*/
	
	function _SessionDestroy($Id)
	{ global $Db;
	# unset cookiesvar
	if(isset($_COOKIE[$this->SessionName])) { unset($_COOKIE[$this->SessionName]); }
	# delete the session from the database
	$Db->execute("DELETE FROM " . DB_PREFIX . "session WHERE SessionName='" . $this->SessionName . "' and SessionId = '" . $Id . "'");
	}
	
	/**
	* removes all old sessions from the database
	* @access private
	*/
	
	function _SessionGC ( $max_lifetime )
	{ global $Db;
	$Db->execute("DELETE FROM " . DB_PREFIX . "session WHERE SessionName ='" . $this->SessionName . "' and SessionExpire < '" . time() . "'");
	}
	
	
	### end of session handler functions ###
	### everything down here is additional session-related stuff ###
	
	
	/**
	* performs some security checks on the user's ip and browser
	*
	* @access public
	*/
	
	function _SessionCheckSecurity()
	{
	# check if an ip-check is whished 
	if(in_array("CheckIp", $this->SessionSecurity))
	{
	# get the clientip
	$Ip	= $_SESSION[ClientIP];
	# if it is null, than set it
	if($Ip === null)
    	{
    	$_SESSION[ClientIP] = getRemoteAddr();
    	}
	# else check, if it is right or not and return false on a mess
	  else if(getRemoteAddr() !== $Ip)
    	{
    	return false;
    	}
	}
	# check if an browser-check is whished
	if(in_array("CheckBrowser", $this->SessionSecurity))
	{
	# get the client browserinfo
	$Browser = $_Session[Client-Browser];
	# if it is null, than set it
	if($Browser === null)
    	{
    	$_SESSION['Client-Browser'] = $_SERVER["HTTP_USER_AGENT"];
    	}
    	# else check, if it is right or not and return false on a mess
    	
        	else if($_SERVER["HTTP_USER_AGENT"] !== $Browser)
    	{
    	return false;
    	}
	}
		
		// Debug-Anzeige :  echo 'SessionSecurity says: ok!';
		# return true if everything is O.K.
		return true;
		}
	

	
	/**
	* delete the session
	* @access public
	*/
		
	function SessionDestroy()
	{
	// Unset all of the session variables.
	foreach($_SESSION as $var) {$var = "";	}
	$_SESSION = array();
	// wäre das besser: unset($_SESSION); ??
	session_destroy();
	$_Session	= null;
	return true;
	}
		
			
	/*
	*  SessionControl
	*  1. alte User löschen
	*  2. Session-Timeout bzw.  aktualisierung des Session[lastmove]
	*  
	*/
	function SessionControl()
	{
	global $Db;
		
		// 2 Tage alte registrierte, aber nicht aktivierte User löschen!
		$Db->execute("DELETE FROM " . DB_PREFIX . "users WHERE disabled = ?  
		AND (joined + INTERVAL 2 DAY)<Now()
		AND (timestamp + INTERVAL 2 DAY)<Now()", '1');
		
		// timestamp im users-table setzen
		$Db->execute("UPDATE " . DB_PREFIX . "users 
							 SET timestamp = NOW() WHERE user_id = ?", $_SESSION['User']['user_id']);
  
		
		// Es wird ein Session-TimeOut für angemeldete User eingesetzt.
		// 30 Minuten nach der letzten Useraktion sollten reichen, 
		// danach ist die Session raus!	
		if(isset($_SESSION['authed'])) {	
			if(!isset($_SESSION['lastmove']) || (time() - $_SESSION['lastmove'] > 350)) {
			$_SESSION['lastmove'] = time(); 
			}
			else { # userhinweis: you forgot to logout
				   die(header("location:logout.php"));
				   } 
		}	
		
	} //end sessioncontrol

}
$Session = new Session();
?>