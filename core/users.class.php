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
    
    //----------------------------------------------------------------
    // Create user-object and $_SESSION data
    //----------------------------------------------------------------
    function create_user($user_id = null, $email = null, $nick = null)
    {
        global $db;
        
        # $user initialisieren
        if ($user_id)
        {
            $stmt = $db->simple_query('SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?', array($user_id ) );
            $user = $stmt->fetch();
            
        }
        else
        if ($email)
        {
            $stmt = $db->simple_query('SELECT * FROM ' . DB_PREFIX . 'users WHERE email = ?', array($email ) );
            $user = $stmt->fetch();
            
        }
        else
        if ($nick)
        {
            $stmt = $db->simple_query('SELECT * FROM ' . DB_PREFIX . 'users WHERE nick = ?', array($nick ) );
            $user = $stmt->fetch();
        }
        
        //$_SESSION['suiteSid'] 	= 	session_id();
        
        // user :: Das $user-Array ist also nur gesetzt,
        // wenn die Funktion user['param'] aufgerufen wurde.
        if (isset($user) && $user)
        {
            
            $_SESSION['user']['authed']     = '1';
            $_SESSION['user']['user_id']     = $user['user_id'];
            
            $_SESSION['user']['password']     = $user['password'];
            $_SESSION['user']['email']     = $user['email'];
            $_SESSION['user']['nick']     = $user['nick'];
            
            $_SESSION['user']['first_name']    = $user['first_name'];
            $_SESSION['user']['last_name']    = $user['last_name'];
            
            
            
            //$_SESSION['user']['::Gruppen::'] = user::get_groups_by_userid($_SESSION['user']['user_id']);
            //$_SESSION['user']['::Rights::']  = user::get_rights_by_userid($_SESSION['user']['user_id']);
            //$_SESSION['user']['rights']   = user::get_allrights_by_userid($user_id);
            
            //SpeedUp der Rechte Tabelle
            //user::generate_liveusers_table();
            
        }
        else
        # GUEST :: wenn keine Parameter �bergeben wurden, ist der user ein Gast
        {
            $_SESSION['user']['authed']     =     0;
            $_SESSION['user']['nick']     =     'Gast';
            #_SESSION['::Gruppen::']    =     'Gast';
        }
    }
    
    //----------------------------------------------------------------
    // Check the user
    //----------------------------------------------------------------
    function check_user($email, $password)
    {
        global $db;
        
        // anhand email user_id, und password auslesen
        $user = $db->simple_query('SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE email = ? LIMIT 1', array($email ) );
        
        // falls $user mit daten gef�llt wurde, das pw �berpr�fen
        if ($user && $user['password'] == md5($password))
        {
            // user existiert und Passwort ist ok!
            // user ID zur�ckliefern
            return $user['user_id'];
        }
        else
        {
            // email&pw kombi gibts nicht
            return false;
        }
    }
    
    //----------------------------------------------------------------
    // Login the user
    //----------------------------------------------------------------
    function login($user_id, $rememberme)
    {
        global $user, $db;
        
        // 1. init new object with ID
        // userdata -> Session
        $user = new user($user_id);
        
        // 2. Remember-Me -> if user has selected this,
        //logindata will be stored in cookie
        
        if ($rememberme)
        {
            setcookie('userid', $user_id, $cfg->remembermetime, '/');
            setcookie('password',$_SESSION['user']['password'], $cfg->remembermetime, '/' );
        }
        
        // 3. Der Session ohne user_id wird nun die user_id zugeordnet.
        // Es lassen sich also GuestSession und userSessions unterscheiden.
        
        $SessionId      = $db->GetOne("SELECT SessionId FROM " . DB_PREFIX . "session WHERE SessionId='" . session_id() . "'" );
        if ($SessionId === $_SESSION['SessionID'] )
        {
            // f�gt der jeweiligen Session die user_id hinzu
            $db->execute("UPDATE " . DB_PREFIX . "session SET user_id = ? WHERE SessionId = ?", $_SESSION['user']['user_id'], $_SESSION['SessionID']);
        }
        
        // 4. Login attempts l�schen
        unset($_SESSION['login_attempts']);
        
        // 5. Stats-Updaten
        //
    }
    
}
?>