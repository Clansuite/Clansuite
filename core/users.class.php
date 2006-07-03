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

DROP TABLE IF EXISTS `cs_users`;
CREATE TABLE `cs_users` (
  `user_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(150) collate latin1_general_ci NOT NULL default '',
  `nick` varchar(25) collate latin1_general_ci NOT NULL default '',
  `password` varchar(40) collate latin1_general_ci NOT NULL,
  `new_password` varchar(40) collate latin1_general_ci default NULL,
  `joined` int(11) default NULL,
  `timestamp` int(11) default NULL,
  `first_name` varchar(25) collate latin1_general_ci NOT NULL default '',
  `last_name` varchar(25) collate latin1_general_ci NOT NULL default '',
  `infotext` text collate latin1_general_ci NOT NULL,
  `disabled` tinyint(1) default '0',
  `activated` tinyint(1) default '0',
  PRIMARY KEY  (`user_id`),
  KEY `email` (`email`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

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
    function create_user($user_id = '', $email = '', $nick = '')
    {
        global $db, $session, $lang;
             
        //----------------------------------------------------------------
        // DB User Queries
        //----------------------------------------------------------------
        if ( !empty($user_id) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array( $user_id ) );
            $user = $stmt->fetch();
            
        }
        else if ( !empty($email) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE email = ?');
            $stmt->execute( array( $email ) );
            $user = $stmt->fetch();
            
        }
        else if ( !empty($nick) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE nick = ?' );
            $stmt->execute( array( $nick ) );
            $user = $stmt->fetch();
        }
        else
        {
            $stmt = $db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
            $stmt->execute( array( session_id() ) );
            $session_res = $stmt->fetch();               
        }
        
        $_SESSION[$session->session_name] = session_id();
        
        //----------------------------------------------------------------
        // If session does mismatch to DB
        //----------------------------------------------------------------
        if ( $session_res['user_id'] == $_SESSION['user']['user_id'] AND !empty($_SESSION['user']['user_id']) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array( $session_res['user_id'] ) );
            $user = $stmt->fetch();  
        }

        //----------------------------------------------------------------
        // Create $_SESSION user
        //----------------------------------------------------------------
        if ( is_array($user) )
        {
            
            $_SESSION['user']['authed']     = '1';
            $_SESSION['user']['user_id']    = $user['user_id'];
            
            $_SESSION['user']['password']   = $user['password'];
            $_SESSION['user']['email']      = $user['email'];
            $_SESSION['user']['nick']       = $user['nick'];
            
            $_SESSION['user']['first_name'] = $user['first_name'];
            $_SESSION['user']['last_name']  = $user['last_name'];
            
            $_SESSION['user']['disabled']   = $user['disabled'];
            $_SESSION['user']['activated']  = $user['activated'];
            
            
            
            //$_SESSION['user']['::Gruppen::'] = user::get_groups_by_userid($_SESSION['user']['user_id']);
            //$_SESSION['user']['::Rights::']  = user::get_rights_by_userid($_SESSION['user']['user_id']);
            //$_SESSION['user']['rights']   = user::get_allrights_by_userid($user_id);
            
            //SpeedUp der Rechte Tabelle
            //user::generate_liveusers_table();
            
        }
        else
        {
            $_SESSION['user']['authed']     = 0;
            $_SESSION['user']['user_id']    = 0;
            $_SESSION['user']['nick']       = $lang->t('Gast');
            
            $_SESSION['user']['password']   = '';
            $_SESSION['user']['email']      = '';

            
            $_SESSION['user']['first_name'] = '';
            $_SESSION['user']['last_name']  = '';
            
            $_SESSION['user']['disabled']   = '';
            $_SESSION['user']['activated']  = '';
            #_SESSION['::Gruppen::']        = 'Guests';
        }
    }
    
    //----------------------------------------------------------------
    // Check the user
    // output return $user_id
    //----------------------------------------------------------------
    function check_user($login_method = 'nick', $value, $password)
    {
        global $db, $security;
        
        if( $login_method == 'nick' )
        {
            // anhand email user_id, und password auslesen
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE nick = ? LIMIT 1' );
            $stmt->execute( array( $value ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if( $login_method == 'email' )
        {
            // anhand email user_id, und password auslesen
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE email = ? LIMIT 1' );
            $stmt->execute( array( $value ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }        

        // falls $user mit daten gefüllt wurde, das pw überprüfen
        if ( is_array($user) && $user['password'] == $security->db_salted_hash( $password ) )
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
    function login($user_id, $remember_me, $password)
    {
        global $db, $security, $cfg;
        
        // 1. Benutzerdaten holen
        // anhand $user_id entsprechende userdata in Session ablegen
        $this->create_user($user_id);
        
        // 2. Remember-Me 
        // ( Logindata als Cookie ablegen )
        
        if ( $remember_me == 1 )
        {
            setcookie('user_id', $user_id, time() + $cfg->remember_me_time);
            setcookie('password',$security->build_salted_hash( $password ), time() + $cfg->remember_me_time);
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
        global $db, $security, $cfg;

        //----------------------------------------------------------------
        // Check for login cookie
        //----------------------------------------------------------------
        if ( !empty($_COOKIE['user_id']) && !empty($_COOKIE['password']) )
        {
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE user_id = ? LIMIT 1' );
            $stmt->execute( array( (int) $_COOKIE['user_id'] ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            //----------------------------------------------------------------
            // Proceed if match
            //----------------------------------------------------------------
	        if (    is_array($user) && 
                    $security->build_salted_hash( $_COOKIE['password'] ) == $user['password'] &&
                    $_COOKIE['user_id'] == $user['user_id'] ) 
		    
		    {
                //----------------------------------------------------------------
                // Update the cookie
                //----------------------------------------------------------------
                setcookie('user_id', $_COOKIE['user_id'], time() + $cfg->remember_me_time);
                setcookie('password',$_COOKIE['password'], time() + $cfg->remember_me_time);
                
                //----------------------------------------------------------------
                // Create $_SESSION['user']
                //----------------------------------------------------------------
                $this->create_user($user['user_id']);
	            
	       	    //----------------------------------------------------------------
                // Update Session in DB
                //----------------------------------------------------------------
			    $this->session_set_user_id();
	        
	        }
            else
            {
                //----------------------------------------------------------------
                // Delete cookies, if no match
                //----------------------------------------------------------------
        	    setcookie('user_id', false );
        		setcookie('password', false );    
	        }
        }
    }


    //----------------------------------------------------------------
    // Bind user_id to session
    //----------------------------------------------------------------
    function session_set_user_id() 
    {
	    global $db, $session;
	    
	    $stmt = $db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ? LIMIT 1' );
        $stmt->execute( array( session_id() ) );
        $session_res = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($session_res['session_id'] == $_SESSION[$session->session_name] )
        {
            // fügt der jeweiligen Session die user_id hinzu
            $stmt = $db->prepare('UPDATE '. DB_PREFIX .'session SET user_id = ? WHERE session_id = ?');
            $stmt->execute( array(  $_SESSION['user']['user_id'], 
                                    $_SESSION[$session->session_name] ) );
        }
	}    
}
?>