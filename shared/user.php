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


class User{ 
	
    function User($user_id = null, $email = null, $nick = null) {
        global $Db;
        
        # $user initialisieren
		if ($user_id) { 
            $user = $Db->getRow("SELECT * FROM " . DB_PREFIX . "users WHERE user_id = ?", $user_id);
        } else if ($email) {
            $user = $Db->getRow("SELECT * FROM " . DB_PREFIX . "users WHERE email = ?", $email);
        } else if ($nick) {
            $user = $Db->getRow("SELECT * FROM " . DB_PREFIX . "users WHERE nick = ?", $nick);
        }
        
        $_SESSION['SessionID'] 			= 	session_id();   
        
        # USER :: Das $user-Array ist also nur gesetzt, wenn die Funktion User['param'] aufgerufen wurde.        
        if (isset($user) && $user) {   
	
    	$_SESSION['User']['authed'] 	= 	'1';
    	$_SESSION['User']['user_id'] 	= 	$user['user_id'];
    	
		$_SESSION['User']['password'] 	= 	$user['password'];
    	$_SESSION['User']['email'] 		= 	$user['email'];
        $_SESSION['User']['nick'] 		= 	$user['nick'];
        
		$_SESSION['User']['first_name']	=	$user['first_name'];
      	$_SESSION['User']['last_name']	=	$user['last_name'];
        
		   
		    
    	$_SESSION['User']['::Gruppen::']	= 	User::get_groups_by_userid($_SESSION['User']['user_id']);
		$_SESSION['User']['::Rights::']		= 	User::get_rights_by_userid($_SESSION['User']['user_id']);
    //	$_SESSION['User']['rights']   = 	User::get_allrights_by_userid($user_id);
        
		User::generate_liveusers_table();
		
		}
        else # GUEST :: wenn keine Parameter übergeben wurden, ist der User ein Gast 
        {
        $_SESSION['User']['authed'] 	= 	0;
    	$_SESSION['User']['nick'] 		= 	'Gast';
    	#_SESSION['::Gruppen::']		= 	'Gast';
		}
	}

    function exists() 		{ return $_SESSION['User']['user_id'] > 0; }
    function isLoggedIn() 	{ return $_SESSION['authed'] > 0; }
    function isActivated() 	{ return $_SESSION['User']['user_id'] && $this->level > 0; }
    function isAuthenticated() 	{ return $_SESSION['User']['authed']; }

    function getId() 	{ if ( $_SESSION['User']['user_id'] > 0 ) { return $_SESSION['User']['user_id']; }
    			  		  else  { return 0; }		}
    function getEmail() { return $_SESSION['User']['email']; }
    function getNick() 	{ return $_SESSION['User']['nick']; }
 
   // kommt in news_comments vor und wird dort 
   // durch User::checkGroup('News
	function mayAddComment() { return true; }

	function session_set_user_id() {
	global $Db;
	$SessionId      = $Db->GetOne("SELECT SessionId FROM " . DB_PREFIX . "session WHERE SessionId='" . session_id() . "'" );
	if ( $SessionId === $_SESSION['SessionID'] )
		{ // fügt der jeweiligen Session die user_id hinzu
		$Db->execute("UPDATE " . DB_PREFIX . "session SET user_id = ? WHERE SessionId = ?", $_SESSION['User']['user_id'], $_SESSION['SessionID']);
		}
	}
	
	/* 	 
	* USER::check_user
	* Es wird überprüft, ob diese Userdaten einem User zugeordnet werden können.
	* Falls ja, wird die entsprechende UserID zurückgeliefert.
	* @param $email
	* @param $password
	* @return $user_id
	*/
	
    function check_user($email, $password) 
    { global $Db;
    	// anhand email user_id, und password auslesen
    	$user = $Db->getRow("SELECT user_id, password FROM " . DB_PREFIX . "users WHERE email = ? LIMIT 1", $email);
	// falls $user mit daten gefüllt wurde, das pw überprüfen
	if ($user && $user['password'] == md5($password)) 
		{ // User existiert und Passwort ist ok!
		  // die User ID zurückgeliefert
		return $user['user_id'];
		}
	else	{ // Email/Pw Kombination gibts nicht
		return false;
		}
     } // end check_user (complete)


	/*
	*	login
	*	1. Set Userdata to Session
	*	2. Set Cookie
	*	3. Set RememberMe-Cookie
	*	4. Update SessionTable in DB with user_id
	*	5. Falls mit dieser SessionID login_attempts vorliegen, diese löschen
	*	@param $user_id
	*	@param $remember
	*/
	
	
	function login($user_id, $remember) { 
	
	// 1. init with ID, to get Userdata into Session 
	$User = new User($user_id);
	
	// 2. Cookie ??
			
	// 3. Remember-Me -> if user has selected this, 
	//logindata will be stored in cookie
	#Wenn die Session hinfällig wird, also nach 30min, ist der Cookie tot!
	
        if ($remember) {  
        setcookie('userid', $user_id, $_SESSION->SessionExpireTime, '/');
        setcookie('password',$_SESSION['User']['password'],$_SESSION->SessionExpireTime, '/' );
	}
/*
	if ( DEBUG == "1" ) { 
	if(isset($_COOKIE['userid']) && isset($_COOKIE['password']))
	{ echo "DEBUG: User hat den user_id & pwCookie akzeptiert\n"; } else 
	{ echo "DEBUG: User hat den user_id & pwCookie nicht akzeptiert\n"; }
        var_dump($User);
	}
*/

	// 4a. Der Session ohne user_id wird nun die user_id zugeordnet.
	// Es lassen sich also GuestSession und UserSessions unterscheiden.
	User::session_set_user_id();
	
	// 4b. Stats-Updaten
	
	#User::
	
 
	
	// 5. Login attempts löschen
	unset($_SESSION['login_attempts']);
}


function add_right() {}
function delete_right() {}
function modify_right () {}

function delete_group() {}
function modify_group(){} 
function add_group(){}

function get_Users_in_group() {}
function add_User_to_Group(){}
function removeUserFromGroup(){}


// Es wird überprüft, ob ein Login Cookie gesetzt ist. 
function check_login_cookie() { 
global $Db;

// 1. wenn remember-me cookie gesetzt ist,
// die entsprechenden daten aus db holen
if (cookie('userid') && cookie('password')) {
    $user = $Db->getRow("SELECT user_id, password FROM " . DB_PREFIX . "users WHERE user_id = ?",(int) $_COOKIE['userid']);
    
	    // 2. wenn db-daten und cookie übereinstimmt:
	    // $user array aktualisieren
	    if (isset($user) && cookie('password') == $user['password']
	    		         && cookie('userid')   == $user['user_id']) 
		
		{	// 3. Class $User mit ID initialisieren
	        $User = new User($user['user_id']);
	       	// 4. der Session wird die user_id zugeordnet
			User::session_set_user_id();
	    } else {
		// Cookies löschen
	    setcookie('userid', '', time()-3600*24, '/' );
		setcookie('password', '', time()-3600*24, '/');    
	    } // end-if userid/password ok
    unset($user);
  } // end-if cookie set
} // end function check-login-cookie

/**
* 
* Holt alle gruppenspezifischen UserRechte.
* Eine Gruppe ist i.d.R. dafür da, Rechte zu gruppieren!
*
* @param $user_id
* @return $groups
*/
function get_groups_by_userid($user_id) {
global $Db;

$groups = $Db->getAll("SELECT g.group_id, g.group_name
					   FROM " . DB_PREFIX . "user_groups ug, 
					   		" . DB_PREFIX . "groups g
					   WHERE ug.user_id = ?	AND 
					   ug.group_id = g.group_id", $user_id);

#debug print_r($groups);
	foreach ($groups as $group){
		# direkt ??
		# $_SESSION['::Gruppen::'][$group[group_id]] = $group[group_name];
		# indirekt als rückgabewert - array
		$gruppenarray[$group[group_id]] = $group[group_name];
		}
		
return $gruppenarray; 
}


/**
*
* Einzelne zugewiesene Rechte für user_id ermitteln!
*
* @param $user_id
* @return $rights
*/
function get_rights_by_userid($user_id) {
global $Db;

$rights = $Db->getALL("SELECT su.right_id, su.right_name
		  			   FROM " . DB_PREFIX . "user_rights s, 
							" . DB_PREFIX . "rights su
					   WHERE s.user_id = ? AND 
					   		 s.right_id=su.right_id", $user_id);

#debug 
#print_r($rights);

foreach ($rights as $right){
		# indirekt als rückgabewert - array
		$rightsarray[$right[right_id]] = $right[right_name];
		}
return $rightsarray; 
}

function count_users(){
global $Db;
$countusers = $Db->getOne("SELECT COUNT(user_id) FROM " . DB_PREFIX . "users");
return $countusers;
}

/**
* 
* Die Funktion generate_liveusers_table erstellt eine Rechtetabelle der Form
* user_id | group_name | group_pos | right_name | right_pos | right_id.
*
* Dies ermöglicht einen schnelleren Zugriff auf alle Rechte eines Users, 
* da Table-Joins wegfallen und umständliche Referenzierungen beim Datenabruf 
* nicht durchgeführt werden müssen.
*
* Die Verwendung dieser Tabelle ist eine absichtlich von den Normlisierungsregeln 
* abweichende geschwindigkeitsoptimierte Verfahrensweise.
*
* @return void;
*/
function generate_liveusers_table(){
global $Db;

$countusers = user::count_users();
#echo 'Anzahl der registrierten User :', $countusers;

for ($user_id= 1; $user_id <= sizeof($countusers);$user_id++)    
   { #cho '<br />Rights Array for UserID: ', $user_id;
    $liverights = $Db->getAll("SELECT 
      ug.user_id, 
	  g.group_name, g.group_pos,
      r.right_name, 
      gr.right_pos, gr.right_id
    FROM
    " . DB_PREFIX . "user_groups ug,
    " . DB_PREFIX . "groups g,
    " . DB_PREFIX . "rights r,
    " . DB_PREFIX . "group_rights gr,
    " . DB_PREFIX . "user_rights 
    WHERE
      (ug.user_id = ?) AND 
      (ug.group_id = g.group_id) AND 
      (g.group_id = gr.group_id) AND 
      (gr.right_id = r.right_id)
    ORDER BY ug.user_id, gr.right_pos, g.group_pos", $user_id);
    
	#echo 'liverights :'; 
	#print_r($liverights);
   }

}

/**
* 
* Abfrage von group_id | group_name | right_id | right_name
* 
* @param $user_id
* @return $allrights;
*/
function get_groupsrights($user_id) {
global $Db;
$allrights = $Db->getAll("SELECT g.group_id, g.group_name, 
				 r.right_id, r.right_name
			FROM " . DB_PREFIX . "rights r, " . DB_PREFIX . "user_rights ur, 
			     " . DB_PREFIX . "groups g, " . DB_PREFIX . "group_rights gr, " . DB_PREFIX . "user_groups ug
			WHERE ug.user_id = ? 
			AND ug.group_id = g.group_id = gr.group_id
			AND gr.right_id = r.right_id ", $user_id);
//var_dump($allrights);
return $allrights;
}

/*
* 
*
*
*/

  	 /**
	 * returns the latest registered user
	 * 
	 * @return user
	 **/
	public function getnewest()
	{
		$user = $this->_db->queryfirst('SELECT * FROM '.$this->_db->pref.'user ORDER BY registered DESC LIMIT 0,1;');
		$userobj = $this->getuser($user['user_id']);
		$userobj->assigndata($user);
		return $userobj;
	}
    

	 	/*
		* get online user
		*/
		public function get_online_user(){
		global $Db;
		
		$UserOnline = array();
		$UserOnline = $Db->GetAll('SELECT s.user_id, u.nick 
									  FROM '.DB_PREFIX.'session s 
									  LEFT JOIN '.DB_PREFIX.'users u USING(user_id)
									  WHERE s.user_id > 0 AND 
									  		s.SessionVisibility > 0 AND
											s.user_id = u.user_id');
								
		if(!is_null($UserOnline))
			 {  $UserOnline['RegisteredOnline'] = $Db->affectedRows(); 	}
		else {	$UserOnline['RegisteredOnline'] = '0';					}
		
		$UserOnline['Guest'] = 	$Db->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."session WHERE user_id = 0");
		return $UserOnline;
		#print_r($UserOnline);
		}
		

} // end of class User.php

global $User;
$User = new User;
USER::check_login_cookie();

?>