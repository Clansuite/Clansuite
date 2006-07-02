<?php
/**
* Users Handler Class
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
* @version    SVN: $Id: users.class.php 129 2006-06-09 12:09:03Z vain $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

/*
//----------------------------------------------------------------
// Table structure for `cs_users`
//----------------------------------------------------------------

CREATE TABLE `cs_users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(150) NOT NULL default '',
  `nick` varchar(25) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `new_password` varchar(32) default NULL,
  `joined` date default NULL,
  `timestamp` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `first_name` varchar(25) NOT NULL default '',
  `last_name` varchar(25) NOT NULL default '',
  `infotext` text NOT NULL,
  `disabled` tinyint(1) default NULL,
  `activated` tinyint(1) NOT NULL,
  PRIMARY KEY  (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM;

-- 
-- Daten für Tabelle `cs_users`
-- 

INSERT INTO `cs_users` (`user_id`, `email`, `nick`, `password`, `new_password`, `joined`, `timestamp`, `first_name`, `last_name`, `infotext`, `disabled`, `activated`) VALUES (1, 'admin@localhost.de', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, '2005-07-28', '2006-07-02 14:13:20', 'john', 'vain', '', NULL, 0);
INSERT INTO `cs_users` (`user_id`, `email`, `nick`, `password`, `new_password`, `joined`, `timestamp`, `first_name`, `last_name`, `infotext`, `disabled`, `activated`) VALUES (2, 'gast@localhost.de', 'gastnick', '21232f297a57a5a743894a0e4a801fc3', NULL, '2006-04-11', '2006-07-02 14:13:20', 'gastvorname', 'gastnachname', 'infotext gast', NULL, 0);

*/

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


//----------------------------------------------------------------
// Start of users class
//----------------------------------------------------------------
class users
{
    //----------------------------------------------------------------
    // Get a user from any kind of given type
    //----------------------------------------------------------------
    function get($value='', $type='' )
    {
        switch ($type )
        {
            case 'id':
                
                break;
                
            case 'email':
                
                break;
                
            case 'name':
                
                break;
                
            case 'nick':
                
                break;
                
            case 'clan_id':
                
                break;
                
            case 'squad_id':
                
                break;
                
                default:
                // per id! => get('2')
                break;
        }
    }
    
    function exists()
    {
        return $_SESSION['user']['user_id'] > 0;
    }
    function isLoggedIn()
    {
        return $_SESSION['authed'] > 0;
    }
    function isAuthenticated()
    {
        return $_SESSION['user']['authed'];
    }
    
    function isActivated() 	{ return $_SESSION['User']['user_id'] && $this->activated > 0; }
    function getId() 	{ if ( $_SESSION['User']['user_id'] > 0 ) { return $_SESSION['User']['user_id']; }
    			  		  else  { return 0; }		}
    
    //----------------------------------------------------------------
    // Create user-object and $_SESSION data
    //----------------------------------------------------------------
    function create_user($user_id = null, $email = null, $nick = null)
    {
        global $db;
        
        # $user initialisieren
        if ($user_id)
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array( $user_id ) );
            $user = $stmt->fetch();
            
        }
        else
        if ($email)
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE email = ?');
            $stmt->execute( array( $email ) );
            $user = $stmt->fetch();
            
        }
        else
        if ($nick)
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE nick = ?' );
            $stmt->execute( array( $nick ) );
            $user = $stmt->fetch();
        }
        
        $_SESSION['sessionid'] 	= 	session_id();
        
        // user :: Das $user-Array ist also nur gesetzt,
        // wenn die Funktion user['param'] aufgerufen wurde.
        if (isset($user) && $user)
        {
            
            // $_SESSION mit $user daten füllen
            $_SESSION['user']['authed']       = '1';
            $_SESSION['user']['user_id']      = $user['user_id'];
            
            $_SESSION['user']['password']     = $user['password'];
            $_SESSION['user']['email']        = $user['email'];
            $_SESSION['user']['nick']         = $user['nick'];
            
            $_SESSION['user']['first_name']   = $user['first_name'];
            $_SESSION['user']['last_name']    = $user['last_name'];
            
            
            
            //$_SESSION['user']['::Gruppen::'] = user::get_groups_by_userid($_SESSION['user']['user_id']);
            //$_SESSION['user']['::Rights::']  = user::get_rights_by_userid($_SESSION['user']['user_id']);
            //$_SESSION['user']['rights']   = user::get_allrights_by_userid($user_id);
            
            //SpeedUp der Rechte Tabelle
            //user::generate_liveusers_table();
            
        }
        else
        # GUEST :: wenn keine Parameter übergeben wurden, ist der user ein Gast
        {
            $_SESSION['user']['authed']     =     0;
            $_SESSION['user']['nick']     =     'Gast';
            #_SESSION['::Gruppen::']    =     'Gast';
        }
    }
    
    //----------------------------------------------------------------
    // Check the user
    // input $email $password
    // output return $user_id
    //----------------------------------------------------------------
    function check_user($email, $password)
    {
        global $db;
        
        // anhand email user_id, und password auslesen
        $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE email = ? LIMIT 1' );
        $stmt->execute( array( $email ) );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // falls $user mit daten gefüllt wurde, das pw überprüfen
        if ( is_array($user) && $user['password'] == md5($password) )
        {
            // demnach existiert ein user mit der kombination von email & pw
            // und es wird die user_id zurückgeliefert
            return $user['user_id'];
        }
        else
        {
            // Die Kombination von Email und Pw exisitert nicht!
            return false;
        }
    }
    
    //----------------------------------------------------------------
    // Login the user
    // input $user_id, $rememberme
    //----------------------------------------------------------------
    function login($user_id, $rememberme)
    {
        global $users, $db;
        
        // 1. Benutzerdaten holen
        // anhand $user_id entsprechende userdata in Session ablegen
        $this->create_user($user_id);
        
        // 2. Remember-Me 
        // ( Logindata als Cookie ablegen )
        
        if ($rememberme)
        {
            setcookie('userid', $user_id, $cfg->remembermetime, '/');
            setcookie('password',$_SESSION['user']['password'], $cfg->remembermetime, '/' );
        }
        
        // 3. Der Session ohne user_id wird nun die user_id zugeordnet.
        // Es lassen sich also Guest-Session und User-Session unterscheiden.
        $this->session_set_user_id();
                
        // 4. Login attempts löschen
        unset($_SESSION['login_attempts']);
        
        // 5. Stats-Updaten
        //
    }
    
    //----------------------------------------------------------------
    // Es wird überprüft, ob ein Login Cookie gesetzt ist. 
    //----------------------------------------------------------------
    function check_login_cookie() 
    { 
        global $Db;

        // 1. wenn remember-me cookie gesetzt ist,
        // die entsprechenden daten aus db holen
        if ($_COOKIE['userid'] && $_COOKIE['password']) {
        
        $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE user_id = ? LIMIT 1' );
        $stmt->execute( array( (int) $_COOKIE['userid'] ) );
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
       
	    // 2. Wenn die Db-Daten mit dem Cookie übereinstimmen, das
	    // $user Array aktualisieren
	    if (isset($user) && $_COOKIE['password'] == $user['password']
	    		         && $_COOKIE['userid']   == $user['user_id']) 
		
		{	// 3. Class $User mit ID initialisieren
	        $this->create_user($user['user_id']);
	        
	       	// 4. der Session wird die user_id zugeordnet
			$this->session_set_user_id();
	    
	    } else {
	    
        		// Die unzutreffenden Cookies löschen
        	    setcookie('userid', '', time()-3600*24, '/' );
        		setcookie('password', '', time()-3600*24, '/');    
	    
	    } // end-if userid/password ok
        unset($user);
    } // end-if cookie set
    
    } // end function check-login-cookie


    // ----
    // Der Session wird die user_id zugeordnet.
    // ----
    function session_set_user_id() 
    {
	global $db;
	
	$stmt = $db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ? LIMIT 1' );
    $stmt->execute( array( session_id() ) );
    $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($session['session_id'] === $_SESSION['sessionid'] )
        {
            // fügt der jeweiligen Session die user_id hinzu
            $stmt = $db->prepare('UPDATE '. DB_PREFIX .'session SET user_id = ? WHERE session_id = ?');
            $stmt->execute( 
                            array(  $_SESSION['user']['user_id'], 
                                    $_SESSION['sessionid'] 
                           )
            );
         
        }
	}
    
}
?>