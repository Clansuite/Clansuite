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

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


/**
* @desc Start of users class
*/
class users
{
    /**
    * @desc Get a user by any kind of given type
    */

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

    /**
    * @desc Create user-object and $_SESSION['user'] array
    */

    function create_user($user_id = '', $email = '', $nick = '')
    {
        global $db, $session, $lang, $functions;

        $user = '';

        /**
        * @desc DB User Queries
        */

        if ( !empty($user_id) )
        {
            $stmt = $db->prepare( 'SELECT user_id,password,email,nick,first_name,last_name,disabled,activated FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array( $user_id ) );
            $user = $stmt->fetch();

        }
        else if ( !empty($email) )
        {
            $stmt = $db->prepare( 'SELECT user_id,password,email,nick,first_name,last_name,disabled,activated FROM ' . DB_PREFIX . 'users WHERE email = ?');
            $stmt->execute( array( $email ) );
            $user = $stmt->fetch();

        }
        else if ( !empty($nick) )
        {
            $stmt = $db->prepare( 'SELECT user_id,password,email,nick,first_name,last_name,disabled,activated FROM ' . DB_PREFIX . 'users WHERE nick = ?' );
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

        // check if session-table[user_id] is a valid user-table[user_id]
        if ( isset($session_res) AND $session_res['user_id'] == $_SESSION['user']['user_id'] AND !empty($_SESSION['user']['user_id']) )
        {
            $stmt = $db->prepare( 'SELECT * FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array( $session_res['user_id'] ) );
            $user = $stmt->fetch();
        }

        // check if this user is activated, else reset cookie, session and redirect
        if ( is_array($user) AND $user['activated'] == 0 )
        {
            setcookie('user_id', false);
            setcookie('password', false);
            $session->_session_destroy(session_id());
            $functions->redirect( 'index.php?mod=account&action=activation_email', 'metatag|newsite', 5, $lang->t('Your account is not yet activated - please enter your email in the form that appears in 5 seconds to resend the activation email.') );
        }

        /**
        * @desc Create $_SESSION['user'] array, containing user data
        */

        if ( is_array($user) )
        {
            /**
            * @desc User infos
            */

            $_SESSION['user']['authed']     = 1;
            $_SESSION['user']['user_id']    = $user['user_id'];

            $_SESSION['user']['password']   = $user['password'];
            $_SESSION['user']['email']      = $user['email'];
            $_SESSION['user']['nick']       = $user['nick'];

            $_SESSION['user']['first_name'] = $user['first_name'];
            $_SESSION['user']['last_name']  = $user['last_name'];

            $_SESSION['user']['disabled']   = $user['disabled'];
            $_SESSION['user']['activated']  = $user['activated'];

            /**
            * @desc Get Groups & Rights of user_id
            */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $stmt = $db->prepare( 'SELECT group_id FROM ' . DB_PREFIX . 'user_groups WHERE user_id = ?' );
            $stmt->execute( array( $user['user_id'] ) );
            $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( is_array( $groups ) )
            {
                foreach( $groups as $key => $group_id )
                {
                    $_SESSION['user']['groups'][] = $group_id['group_id'];

                    $stmt = $db->prepare( 'SELECT rg.*, ri.* FROM ' . DB_PREFIX . 'group_rights AS rg JOIN ' . DB_PREFIX . 'rights AS ri ON ri.right_id = rg.right_id WHERE rg.group_id = ?' );
                    $stmt->execute( array( $group_id['group_id'] ) );
                    $rights = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if( is_array( $rights ) )
                    {
                        foreach( $rights as $key => $values )
                        {
                            $_SESSION['user']['rights'][$values['name']] = 1;
                        }
                    }
                }
            }
        }
        else // reset $_SESSION['user'] array
        {
            /**
            * @desc User infos
            */

            $_SESSION['user']['authed']     = 0;
            $_SESSION['user']['user_id']    = 0;
            $_SESSION['user']['nick']       = $lang->t('Guest');

            $_SESSION['user']['password']   = '';
            $_SESSION['user']['email']      = '';


            $_SESSION['user']['first_name'] = '';
            $_SESSION['user']['last_name']  = '';

            $_SESSION['user']['disabled']   = 0;
            $_SESSION['user']['activated']  = 0;

            /**
            * @desc Groups & Rights
            */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();
        }
    }

    /**
    * @desc Check the user
    * @desc output return $user_id
    */

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

        // falls $user mit daten gef�llt wurde, das pw �berpr�fen
        if ( is_array($user) && $user['password'] == $security->db_salted_hash( $password ) )
        {
            // demnach existiert ein user mit der kombination von email & pw
            // und es wird die user_id zur�ckgeliefert
            return $user['user_id'];
        }
        else
        {
            // Die Kombination von Email und Pw exisitert nicht!
            return false;
        }
    }

    /**
    * @desc Login the user
    * @desc input $user_id, $rememberme
    */

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

        // 4. Login attempts l�schen
        unset($_SESSION['login_attempts']);

        // 5. Stats-Updaten
        //
    }

    /**
    * @desc Es wird �berpr�ft, ob ein Login Cookie gesetzt ist.
    */

    function check_login_cookie()
    {
        global $db, $security, $cfg;

        /**
        * @desc Check for login cookie
        */

        if ( !empty($_COOKIE['user_id']) && !empty($_COOKIE['password']) )
        {
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE user_id = ? LIMIT 1' );
            $stmt->execute( array( (int) $_COOKIE['user_id'] ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            /**
            * @desc Proceed if match
            */

	        if (    is_array($user) &&
                    $security->build_salted_hash( $_COOKIE['password'] ) == $user['password'] &&
                    $_COOKIE['user_id'] == $user['user_id'] )

		    {
                /**
                * @desc Update the cookie
                */

                setcookie('user_id', $_COOKIE['user_id'], time() + $cfg->remember_me_time);
                setcookie('password',$_COOKIE['password'], time() + $cfg->remember_me_time);

                /**
                * @desc Create $_SESSION['user']
                */

                $this->create_user($user['user_id']);

	       	    /**
                * @desc Update Session in DB
                */

			    $this->session_set_user_id();

	        }
            else
            {
                /**
                * @desc Delete cookies, if no match
                */

        	    setcookie('user_id', false );
        		setcookie('password', false );
	        }
        }
    }


    /**
    * @desc Bind user_id to session
    */

    function session_set_user_id()
    {
	    global $db, $session;

	    $stmt = $db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ? LIMIT 1' );
        $stmt->execute( array( session_id() ) );
        $session_res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($session_res['session_id'] == $_SESSION[$session->session_name] )
        {
            // f�gt der jeweiligen Session die user_id hinzu
            $stmt = $db->prepare('UPDATE '. DB_PREFIX .'session SET user_id = ? WHERE session_id = ?');
            $stmt->execute( array(  $_SESSION['user']['user_id'],
                                    $_SESSION[$session->session_name] ) );
        }
	}
}
?>