<?php
/**
* Session Handler Class
*
* PHP versions 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: session.class.php 155 2006-06-13 20:55:04Z xsign $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/


/*
//----------------------------------------------------------------
// Table structure for cs_session
//----------------------------------------------------------------

CREATE TABLE `cs_session` (
  `user_id` int(11) NOT NULL default '0',
  `session_id` varchar(255) NOT NULL default '',
  `session_data` text NOT NULL,
  `session_name` text  NOT NULL,
  `session_expire` int(11) NOT NULL default '0',
  `session_visibility` tinyint(4) NOT NULL default '0',
  `session_where` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  UNIQUE KEY `session_id` (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/


//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
	die( 'You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Class session start
//----------------------------------------------------------------
class session
{
	//----------------------------------------------------------------
	// Init class session | set common session vars
	//----------------------------------------------------------------
	private $_session 				= array();    // clear array
	public $session_name 			= 'suiteSID'; // custom session_name
	public $session_expire_time 	= 30; 		  // expiretime minutes
	public $session_probability 	= 30;		  // clean up in 30 of 100 cases
	public $session_cookies 		= 1;       // cookies verwenden
	public $session_cookies_only 	= 0;       // nur Cookies verwenden
	public $session_security 		= array('check_ip' 		=> false,
											'check_browser' => false);
											
	//----------------------------------------------------------------
	// Overwrite php.ini settings
	// Start the session
	//----------------------------------------------------------------
	function create_session()
	{
		global $lang, $error;
		
		//----------------------------------------------------------------
		// Set the ini Vars
		//----------------------------------------------------------------
		ini_set("session.save_handler",    	"user"                   );
		ini_set("session.name",				$this->session_name       );
		ini_set("session.gc_maxlifetime",  	$this->session_expire_time );
		ini_set("session.gc_probability",  	$this->session_probability);

		//----------------------------------------------------------------
		// Check if cookies are allowed
		//----------------------------------------------------------------
		setcookie ( 'time', time(), time() + 31536000 );

		if ( !isset($_COOKIE['time']) OR $this->session_cookies == 0 )
		{
			$this->cookies_available = 0;
			ini_set("session.use_cookies", '0');
			ini_set("session.use_only_cookies",	'0');
		}
		else
		{
			$this->cookies_available = 1;
			ini_set("session.use_cookies", 	'1');
			ini_set("session.use_only_cookies",	'1');
		}

		//----------------------------------------------------------------
		// Set the hnadlers
		//----------------------------------------------------------------
		session_set_save_handler(
									array(&$this, "_session_open"   ),
									array(&$this, "_session_close"  ),
									array(&$this, "_session_read"   ),
									array(&$this, "_session_write"  ),
									array(&$this, "_session_destroy"),
									array(&$this, "_session_gc"     ));

	
		//----------------------------------------------------------------
		// Start Session with Error on failure
		//----------------------------------------------------------------
		if (!session_start())
		{ $error->show( $lang->t( 'Session Error' ), $lang->t( 'The session start failed!' ), 3 ); }

		//----------------------------------------------------------------
		// Create new ID if session is not in DB or corrupted
		//----------------------------------------------------------------
		if ( $this->_session_read( session_id() )===false OR strlen( session_id() ) != 32)
		{ session_regenerate_id(); }

		//----------------------------------------------------------------
		// Set the Paths
		//----------------------------------------------------------------
		if( $this->cookies_available == 0 )
		{ $this->base_url = WWW_ROOT . '/index.php?' . $this->session_name . '=' . session_id() . '&'; }

		//----------------------------------------------------------------
		// Completeition... nothing to do here
		//----------------------------------------------------------------
		$this->_session =&$_SESSION;

		//----------------------------------------------------------------
		// Security Check
		//----------------------------------------------------------------
		if ( !$this->_session_check_security() )
		{ $this->_session = array(); }


	}
	
	//----------------------------------------------------------------
	// Open a session
	//----------------------------------------------------------------
	function _session_open()
	{	
		return true; 
	}

	//----------------------------------------------------------------
	// Close a session
	//----------------------------------------------------------------
	function _session_close()
	{
		session::_session_gc(0);
		return true;
	}

	//----------------------------------------------------------------
	// Read a session
	//----------------------------------------------------------------
	function _session_read ( $id )
	{ 
		global $db;
		$sql = $db->select( array( 	'SELECT' 	=> 'session_data',
									'FROM'		=> 'session',
									'WHERE'		=> "session_name = '$this->session_name' AND session_id = '$id'" ) );
		
		if ( $db->affected_rows($sql) != 0 )
		{ 
			$result = $db->fetch_array($sql);
			$data = $result['session_data'];
			return $data;
		}
		else
		{ 
			return false;
		}
	}

	//----------------------------------------------------------------
	// Write a session
	//----------------------------------------------------------------
	function _session_write( $id, $data )
	{ 
		global $db;

		//----------------------------------------------------------------
		// Time Settings
		//----------------------------------------------------------------
		$seconds = $this->session_expire_time * 60;
		$expires = time() + $seconds;
		
		//----------------------------------------------------------------
		// Check if session is in DB
		//----------------------------------------------------------------
		$sql = $db->select(array( 	'SELECT' 	=> '*',
									'FROM'		=> 'session',
									'WHERE'		=> "session_id='$id'" ) );
		$in_db = $db->fetch_array($sql);

		if ( $in_db )
		{
			//----------------------------------------------------------------
			// Update Session in DB
			//----------------------------------------------------------------
			$db->update('session', "session_id = '$id'", array( 	'session_expire' 	=> $expires,
																	'session_data'		=> $data ) );
			$this->session_control();

		}
		else
		{
			//----------------------------------------------------------------
			// Create Session @ DB & Cookies OR $_GET
			//----------------------------------------------------------------
			$db->insert('session', array( 	'session_id' 		=> $id,
											'session_name'		=> $this->session_name,
											'session_expire'	=> $expires,
											'session_data'		=> $data,
											'session_visibility'=> 1,
											'user_id'			=> 0 ) );
			if ( $this->cookies_available )
			{
				setcookie( $this->session_name, $id );
			}
    	}
		return true;
	}

	//----------------------------------------------------------------
	// Destroy a session
	//----------------------------------------------------------------
	function _session_destroy( $id )
	{
		global $db;

		//----------------------------------------------------------------
		// Unset Cookie Vars
		//----------------------------------------------------------------
		if(isset($_COOKIE[$this->session_name]))
		{ unset($_COOKIE[$this->session_name]); }

		//----------------------------------------------------------------
		// Optimize tables
		//----------------------------------------------------------------
		$row_count = $db->delete( 'session', "session_name='$this->session_name' AND session_id = '$id'");

		if ( $row_count > 0)
		{ $this->_session_optimize(); }

	}

	//----------------------------------------------------------------
	// Session garbage collector
	//----------------------------------------------------------------
	function _session_gc ( $max_lifetime )
	{
		global $db;

		//----------------------------------------------------------------
		// Prune
		//----------------------------------------------------------------
		$row_count = $db->delete( 'session', "session_name = '$this->session_name' AND session_expire < " . time() );

		if ( $row_count > 0)
		{ $this->_session_optimize(); }
	}

	//----------------------------------------------------------------
	// Optimize the session table
	//----------------------------------------------------------------
	function _session_optimize()
	{
		global $db;

		$db->exec('OPTIMIZE TABLE ' . DB_PREFIX . 'session');

	}

	//----------------------------------------------------------------
	// Check for a secure session 
	//----------------------------------------------------------------
	function _session_check_security()
	{
		//----------------------------------------------------------------
		// Check for IP
		//----------------------------------------------------------------
		if(in_array("check_ip", $this->session_security))
		{
			
			// get the clientip
			$ip	= $_SESSION[ClientIP];
			// if it is null, than set it
			if($ip === null)
		    {
    			$_SESSION[ClientIP] = $_SERVER['REMOTE_ADDR'];
		    }
				// else check, if it is right or not and return false on a mess
  			else if($_SERVER['REMOTE_ADDR'] !== $ip)
		    {
    			return false;
		    }
		}

		//----------------------------------------------------------------
		// Check for Browser
		//----------------------------------------------------------------
		if(in_array("check_browser", $this->session_security))
		{
			// get the client browserinfo
			$browser = $_Session[Client-Browser];
			// if it is null, than set it
			if($browser === null)
    		{
    			$_SESSION['Client-Browser'] = $_SERVER["HTTP_USER_AGENT"];
    		}
    		// else check, if it is right or not and return false on a mess
	        else if($_SERVER["HTTP_USER_AGENT"] !== $browser)
    		{
    			return false;
    		}
		}

		//----------------------------------------------------------------
		// Return true if everything is ok
		//----------------------------------------------------------------
		return true;
	}

	//----------------------------------------------------------------
	// Session control
	// - delete old users
	// - prune timeouts 
	//----------------------------------------------------------------
	function session_control()
	{
		global $db;
		// 2 Tage alte registrierte, aber nicht aktivierte User löschen!
		/*
		$table = "DELETE FROM " . DB_PREFIX . "users ";
		$where = "disabled = 1 AND (joined + INTERVAL 2 DAY)<Now() AND (timestamp + INTERVAL 2 DAY)<Now()";
		$db->exec($table.$where);
		
		
		// Zeitstempel in users-table setzen
		$table 	= 'UPDATE ' . DB_PREFIX . 'users ';
		$set	= "SET timestamp = NOW() WHERE user_id = '$_SESSION[User][user_id]'";
		$db->exec($table.$set);
		*/
		if(isset($_SESSION['authed']))
		{
			if(!isset($_SESSION['lastmove']) || (time() - $_SESSION['lastmove'] > 350))
			{
				$_SESSION['lastmove'] = time();
			}
			else
			{ // todo: a. you forgot to logout b. send user back
				die(header("location:logout.php"));
			}
		}
	}
}
?>