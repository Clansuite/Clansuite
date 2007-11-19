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
class user
{
    /**
     * References to session and general user object
     */ 
    private $user       = null;
    
    private $config     = null;
    private $db         = null;  
    private $security   = null;
    
    // forward -> create user and check for login cookie
    function __construct(configuration $config, db $db, security $security )
    {   
        $this->config       = $config;
        $this->db           = $db;       
        $this->security     = $security;  
    }
    /**
     * Get a user by any type of db field information
     *
     * @param string
     * @param integer
     * @global $db
     * @global $this->security
     * @global $input
     * @return $result[$type] if found
     * @todo is this funtion used somewhere?
     */
    public function get( $type = '', $user_id = '' )
    {
        if( !$input->check( $type       , 'is_abc|is_int|is_custom', '_' ) OR
            !$input->check( $user_id    , 'is_int' ) )
        {
            $this->security->intruder_alert();
        }
        else
        {
            $user_id = $user_id == '' ? $_SESSION['user']['user_id'] : $user_id;

            $stmt = $this->db->prepare('SELECT `' . $type . '` FROM ' . DB_PREFIX . 'users
                                  LEFT JOIN ' . DB_PREFIX . 'profiles
                                  ON ' . DB_PREFIX . 'users.user_id = ' . DB_PREFIX . 'profiles.user_id
                                  WHERE ' . DB_PREFIX . 'users.user_id = ?');
            $stmt->execute( array( $user_id ) );
            $result = $stmt->fetch(PDO::FETCH_NAMED);

            return $result[$type];
        }
    }

    /**
     * Creates the User-Object and the $session['user'] Array
     *
     * @param $user_id
     * @param $email
     * @param $nick
     * @global $db
     * @global $this->session
     * @global $function
     */

    public function create_user($user_id = '', $email = '', $nick = '')
    {
        $user       = null; 
        
        /**
         *  DB User Queries
         */

        $query_string = 'SELECT u.*, o.language, o.theme FROM ' . DB_PREFIX . 'users u LEFT JOIN ' . DB_PREFIX . 'user_options o ON u.user_id = o.user_id ';
        if ( !empty($user_id) )
        {
            $stmt = $this->db->prepare( $query_string . 'WHERE u.user_id = ?' );
            $stmt->execute( array( $user_id ) );
            $user = $stmt->fetch(PDO::FETCH_NAMED);
        }
        else if ( !empty($email) )
        {
            $stmt = $this->db->prepare( $query_string . 'WHERE u.email = ?');
            $stmt->execute( array( $email ) );
            $user = $stmt->fetch(PDO::FETCH_NAMED);

        }
        else if ( !empty($nick) )
        {
            $stmt = $this->db->prepare( $query_string . 'WHERE u.nick = ?' );
            $stmt->execute( array( $nick ) );
            $user = $stmt->fetch(PDO::FETCH_NAMED);
        }
        else
        {
            $stmt = $this->db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
            $stmt->execute( array( session_id() ) );
            $session_result = $stmt->fetch(PDO::FETCH_NAMED);
        }

        // set a new session id
        session::regenerate_session_id();        

        // check if session-table[user_id] is a valid user-table[user_id]
        if (!empty($_SESSION['user']['user_id'])) 
        {
            if ( isset($session_result) and $session_result['user_id'] == $_SESSION['user']['user_id'] )
            {
                $stmt = $this->db->prepare( $query_string . 'WHERE u.user_id = ?' );
                $stmt->execute( array( $session_result['user_id'] ) );
                $user = $stmt->fetch(PDO::FETCH_NAMED);
            }
        }

        // check if this user is activated, else reset cookie, session and redirect
        if ( is_array($user) AND $user['activated'] == 0 )
        {
            setcookie('user_id', false);
            setcookie('password', false);
            session::_session_destroy(session_id());
            $functions->redirect( 'index.php?mod=account&action=activation_email', 'metatag|newsite', 5, _('Your account is not yet activated - please enter your email in the form that appears in 5 seconds to resend the activation email.') );
        }

        /**
         * Create $_SESSION['user'] array, containing user data
         */        
        if ( is_array($user) )
        {
            /**
             * User infos
             */

            $_SESSION['user']['authed']         = 1;
            $_SESSION['user']['user_id']        = $user['user_id'];

            $_SESSION['user']['passwordhash']   = $user['passwordhash'];
            $_SESSION['user']['email']          = $user['email'];
            $_SESSION['user']['nick']           = $user['nick'];

            $_SESSION['user']['disabled']       = $user['disabled'];
            $_SESSION['user']['activated']      = $user['activated'];

            // Fallback: first take user['language'], else standard language as defined by $this->config['->language
            if ( $_SESSION['user']['language_via_url'] == '0' )
            {
                $_SESSION['user']['language'] = (!empty($user['language']) ? $user['language'] : $this->config['language']);
            }
                      
            // Fallback: first take standard theme as defined by $config->theme
            if ( $_SESSION['user']['theme_via_url'] == '0' )
            {
                $_SESSION['user']['theme'] = (!empty($user['theme']) ? $user['theme'] : $this->config['theme']);
            }
            
            /**
             * Get Groups & Rights of user_id
             */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $stmt = $this->db->prepare( 'SELECT group_id FROM ' . DB_PREFIX . 'user_groups WHERE user_id = ?' );
            $stmt->execute( array( $user['user_id'] ) );
            $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ( is_array( $groups ) )
            {
                foreach( $groups as $key => $group_id )
                {
                    $_SESSION['user']['groups'][] = $group_id['group_id'];

                    $stmt = $this->db->prepare( 'SELECT rg.*, ri.* FROM ' . DB_PREFIX . 'group_rights AS rg JOIN ' . DB_PREFIX . 'rights AS ri ON ri.right_id = rg.right_id WHERE rg.group_id = ?' );
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
            /***/
             #echo 'Fill $_SESSION[user] with Guest-User-infos';
                        
            $_SESSION['user']['authed']         = 0;
            $_SESSION['user']['user_id']        = 0;
            $_SESSION['user']['nick']           = $this->lang->t('Guest');

            $_SESSION['user']['passwordhash']   = '';
            $_SESSION['user']['email']          = '';

            $_SESSION['user']['disabled']       = 0;
            $_SESSION['user']['activated']      = 0;
            
            // Fallback: standard language as defined by $this->config['->language
            if (empty($_SESSION['user']['language']))
            {
                $_SESSION['user']['language']   = $this->config['language'];
            }
            
            // Fallback: standard theme as defined by $this->config theme
            if (empty($_SESSION['user']['theme']))
            {
                $_SESSION['user']['theme']      = $this->config['theme'];
            }
            
            /**
             * Groups & Rights (Guest Group id = 1)
             */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $_SESSION['user']['groups'][] = 1;
            
            $stmt = $this->db->prepare( 'SELECT rg.*, ri.* FROM ' . DB_PREFIX . 'group_rights AS rg JOIN ' . DB_PREFIX . 'rights AS ri ON ri.right_id = rg.right_id WHERE rg.group_id = ?' );
            $stmt->execute( array( 1 ) );
            $rights = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if( is_array( $rights ) )
            {
                foreach( $rights as $key => $values )
                {
                    $_SESSION['user']['rights'][$values['name']] = 1;
                }
            }
            
            #var_dump($_SESSION);
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

    public function check_user($login_method = 'nick', $value, $password)
    {
        $user = null;
        
        if( $login_method == 'nick' )
        {
            // get user_id and password with the nick
            $stmt = $this->db->prepare( 'SELECT user_id, passwordhash FROM ' . DB_PREFIX . 'users WHERE nick = ? LIMIT 1' );
            $stmt->execute( array( $value ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        if( $login_method == 'email' )
        {
            // get user_id and password with the email
            $stmt = $this->db->prepare( 'SELECT user_id, passwordhash FROM ' . DB_PREFIX . 'users WHERE email = ? LIMIT 1' );
            $stmt->execute( array( $value ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // if user was found, check if passwords match each other
        // @todo: note by vain: db_salted_hash is deprecated!
        if ( is_array($user) && $user['passwordhast'] == $this->security->db_salted_hash( $password ) )
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
     * @global $this->security
     * @global $this->config['
     */

    public function login($user_id, $remember_me, $password)
    {       
        /**
         * 1. Create the User Data Array and the Session via $user_id
         */
        $this->create_user($user_id);

        /**
         * 2. Remember-Me ( set Logindata via Cookie )
         */
        if ( $remember_me == 1 )
        {   
            setcookie('user_id', $user_id, time() + round($this->config['remember_me_time']*24*60*60));
            # @todo: note by vain:
            # build_salted_hash deprecated check security.class.php
            setcookie('password',$this->security->build_salted_hash( $password ), time() + round($this->config['remember_me_time']*24*60*60));
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
     * @global $this->security
     * @global $this->config['
     */

    public function check_login_cookie()
    {
        /**
         * Check for login cookie
         */

        if ( !empty($_COOKIE['user_id']) && !empty($_COOKIE['password']) )
        {
            $stmt = $this->db->prepare( 'SELECT user_id, password FROM ' . DB_PREFIX . 'users WHERE user_id = ? LIMIT 1' );
            $stmt->execute( array( (int) $_COOKIE['user_id'] ) );
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            /**
             * Proceed if match
             */

	        if ( is_array($user) &&
                 $this->security->build_salted_hash( $_COOKIE['password'] ) == $user['password'] &&
                 $_COOKIE['user_id'] == $user['user_id'] )
		    {
                /**
                 * Update the cookie
                 */

                setcookie('user_id', $_COOKIE['user_id'], time() + round($this->config['remember_me_time']*24*60*60));
                setcookie('password',$_COOKIE['password'], time() + round($this->config['remember_me_time']*24*60*60));

                /**
                 * Create $this->session['user']
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
     * @global $this->session
     */

    function session_set_user_id()
    {
	    $stmt = $this->db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ? LIMIT 1' );
        $stmt->execute( array( session_id() ) );
        $session_result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($session_result['session_id'] == $_SESSION[$session_name] )
        {
            // fügt der jeweiligen Session die user_id hinzu
            $stmt = $this->db->prepare('UPDATE '. DB_PREFIX .'session SET user_id = ? WHERE session_id = ?');
            $stmt->execute( array(  $_SESSION['user']['user_id'],
                                    $_SESSION[$session_name] ) );
        }
	}
}
?>