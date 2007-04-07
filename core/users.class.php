<?php
/**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
    * http://www.clansuite.com/
    *
    * File:         users.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Users Handling
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
    * @license    GNU/GPL, see COPYING.txt
    *
    * @author     Jens-Andre Koch   <vain@clansuite.com>
    * @author     Florian Wolf      <xsign.dll@clansuite.com>
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('You are not allowed to view this page.'); }

/**
 * This Clansuite Core Class for Users Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  users
 */
class users
{
    /**
     * Get a user by any type of db field information
     *
     * @param string
     * @param integer
     * @global $db
     * @global $security
     * @global $input
     * @return $result[$type] if found
     * @todo is this funtion used somewhere?
     */
    function get( $type = '', $user_id = '' )
    {
        global $db, $security, $input;

        if( !$input->check( $type       , 'is_abc|is_int|is_custom', '_' ) OR
            !$input->check( $user_id    , 'is_int' ) )
        {
            $security->intruder_alert();
        }
        else
        {
            $user_id = $user_id == '' ? $_SESSION['user']['user_id'] : $user_id;

            $stmt = $db->prepare('SELECT `' . $type . '` FROM ' . DB_PREFIX . 'users
                                  LEFT JOIN ' . DB_PREFIX . 'profiles
                                  ON ' . DB_PREFIX . 'users.user_id = ' . DB_PREFIX . 'profiles.user_id
                                  WHERE ' . DB_PREFIX . 'users.user_id = ?');
            $stmt->execute( array( $user_id ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);

            return $result[$type];
        }
    }

    /**
     * Creates the User-Object and the $_SESSION['user'] Array
     *
     * @param $user_id
     * @param $email
     * @param $nick
     * @global $db
     * @global $session
     * @global $lang
     * @global $function
     */

    function create_user($user_id = '', $email = '', $nick = '')
    {
        global $cfg, $db, $session, $lang, $functions;

        $user = '';

        /**
         *  DB User Queries
         */

        if ( !empty($user_id) )
        {
            $stmt = $db->prepare( 'SELECT user_id,password,email,nick,disabled,activated,language,theme FROM ' . DB_PREFIX . 'users WHERE user_id = ?' );
            $stmt->execute( array( $user_id ) );
            $user = $stmt->fetch();

        }
        else if ( !empty($email) )
        {
            $stmt = $db->prepare( 'SELECT user_id,password,email,nick,disabled,activated,language,theme FROM ' . DB_PREFIX . 'users WHERE email = ?');
            $stmt->execute( array( $email ) );
            $user = $stmt->fetch();

        }
        else if ( !empty($nick) )
        {
            $stmt = $db->prepare( 'SELECT user_id,password,email,nick,disabled,activated,language,theme FROM ' . DB_PREFIX . 'users WHERE nick = ?' );
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
         * Create $_SESSION['user'] array, containing user data
         */

        if ( is_array($user) )
        {
            /**
             * User infos
             */

            $_SESSION['user']['authed']     = 1;
            $_SESSION['user']['user_id']    = $user['user_id'];

            $_SESSION['user']['password']   = $user['password'];
            $_SESSION['user']['email']      = $user['email'];
            $_SESSION['user']['nick']       = $user['nick'];

            $_SESSION['user']['disabled']   = $user['disabled'];
            $_SESSION['user']['activated']  = $user['activated'];

            $_SESSION['user']['language']   = (!empty($user['language']) ? $user['language'] : $cfg->language);
            $_SESSION['user']['theme']      = (!empty($user['theme']) ? $user['theme'] : $cfg->theme);

            /**
             * Get Groups & Rights of user_id
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
             * User infos
             */

            $_SESSION['user']['authed']     = 0;
            $_SESSION['user']['user_id']    = 0;
            $_SESSION['user']['nick']       = $lang->t('Guest');

            $_SESSION['user']['password']   = '';
            $_SESSION['user']['email']      = '';

            $_SESSION['user']['disabled']   = 0;
            $_SESSION['user']['activated']  = 0;

            // This means guest-sessions have only standard language
            // as defined in $cfg->language
            // @todo: assign guest-selected language to the session
            $_SESSION['user']['language']   = $cfg->language;


            // same as above for theme (template)
            $_SESSION['user']['theme']      = $cfg->theme;
            // #$theme = '/'.(!empty($_SESSION['user']['theme']) ? $_SESSION['user']['theme'] : $cfg->theme);

            /**
             * Groups & Rights (Guest Group id = 1)
             */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $_SESSION['user']['groups'][] = 1;

            $stmt = $db->prepare( 'SELECT rg.*, ri.* FROM ' . DB_PREFIX . 'group_rights AS rg JOIN ' . DB_PREFIX . 'rights AS ri ON ri.right_id = rg.right_id WHERE rg.group_id = ?' );
            $stmt->execute( array( 1 ) );
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

    /**
     * Check the user
     *
     * Validate the existance of the user via nick or email and the password
     * This is done in two steps:
     * 1. check if given nick or email exists
     * 2. if thats the case compare password
     *
     * @param string $login_method contains the login_method ('nick' or 'email')
     * @param string $value contains nick or email string to look for
     * @param string $password contains password string
     * @return if user is found $user_id else false
     * @todo has $user array to be resetted at the start of this function to get fresh values from db?
     */

    function check_user($login_method = 'nick', $value, $password)
    {
        global $db, $security;

        if( $login_method == 'nick' )
        {
            // get user_id and password with the nick
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE nick = ? LIMIT 1' );
            $stmt->execute( array( $value ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if( $login_method == 'email' )
        {
            // get user_id and password with the email
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE email = ? LIMIT 1' );
            $stmt->execute( array( $value ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // if user was found, check if passwords match each other
        if ( is_array($user) && $user['password'] == $security->db_salted_hash( $password ) )
        {
            // ok, user with nick or email exists and passwords matched
            // return the user_id
            return $user['user_id'];
        }
        else
        {
            // the combination, either nick and password or email and password, doesn't exist
            return false;
        }
    }

    /**
     * Login
     *
     * @param integer $user_id contains user_id
     * @param integer $remember_me contains remember_me setting
     * @param string $password contains password string
     * @global $db
     * @global $security
     * @global $cfg
     */

    function login($user_id, $remember_me, $password)
    {
        global $db, $security, $cfg;

        /**
         * 1. Create the User Data Array and the Session via $user_id
         */
        $this->create_user($user_id);

        /**
         * 2. Remember-Me ( set Logindata via Cookie )
         */
        if ( $remember_me == 1 )
        {
            setcookie('user_id', $user_id, time() + round($cfg->remember_me_time*24*60*60));
            setcookie('password',$security->build_salted_hash( $password ), time() + round($cfg->remember_me_time*24*60*60));
        }

        /**
         * 3. user_id is now inserted into the session without user_id
         * This transforms the so called Guest-Session to a User-Session
         */
        $this->session_set_user_id();

        /**
         * 4. Delete Login attempts
         */
        unset($_SESSION['login_attempts']);

        /**
         * 5. Stats-Updaten
         * @todo stats update after login?
         */
    }

    /**
     * Checks if a login cookie is set
     *
     * @global $db
     * @global $security
     * @global $cfg
     */

    function check_login_cookie()
    {
        global $db, $security, $cfg;

        /**
         * Check for login cookie
         */

        if ( !empty($_COOKIE['user_id']) && !empty($_COOKIE['password']) )
        {
            $stmt = $db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE user_id = ? LIMIT 1' );
            $stmt->execute( array( (int) $_COOKIE['user_id'] ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            /**
             * Proceed if match
             */

	        if ( is_array($user) &&
                 $security->build_salted_hash( $_COOKIE['password'] ) == $user['password'] &&
                 $_COOKIE['user_id'] == $user['user_id'] )
		    {
                /**
                 * Update the cookie
                 */

                setcookie('user_id', $_COOKIE['user_id'], time() + round($cfg->remember_me_time*24*60*60));
                setcookie('password',$_COOKIE['password'], time() + round($cfg->remember_me_time*24*60*60));

                /**
                 * Create $_SESSION['user']
                 */

                $this->create_user($user['user_id']);

	       	    /**
                 * Update Session in DB
                 */

			    $this->session_set_user_id();
	        }
            else
            {
                /**
                 * Delete cookies, if no match
                 */

        	    setcookie('user_id', false );
        		setcookie('password', false );
	        }
        }
    }


    /**
     * Sets user_id to a session
     *
     * @global $db
     * @global $session
     */

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