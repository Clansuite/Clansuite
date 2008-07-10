<?php
/**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005 - onwards
    * http://www.clansuite.com/
    *
    * File:         clansuite_user.class.php
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
    * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id: user.class.php 2025 2008-05-14 16:02:27Z vain $
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
 * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  users
 */
class Clansuite_User
{
    /**
     * References to user, session, security, input
     */

    private $user       = null;
    private $config     = null;
    private $security   = null;
    private $input      = null;

    /**
     * Constructor
     *
     * Sets up the References
     */

    function __construct(Clansuite_Config $config, Clansuite_Security $security, input $input )
    {
        $this->config       = $config;
        $this->security     = $security;
        $this->input        = $input;
    }

    /**
     * getUser
     * - Get a user by any field of the users and profiles table
     * - seperate the fields by commata
     *
     * @param integer
     * @param string
     */

    public function getUser( $user_id = null, $fields = '*')
    {
        $user_id = empty($user_id) ? $_SESSION['user']['user_id'] : (int)$user_id;

        $userdata = null;
        $userdata = Doctrine_Query::create()
                        ->select($fields)
                        ->from('CsUsers')
                        ->leftJoin('CsProfiles')
                        ->where('CsUsers.user_id = ?')
                        ->fetchOne(array($user_id), Doctrine::FETCH_ARRAY);

        if(is_array($userdata))
        {
            return $userdata;
        }
        else
        {
            return false;
        }
    }

    /**
     * Creates the User-Object and the $session['user'] Array
     *
     * @param $user_id
     * @param $email
     * @param $nick
     */

    public function createUser($user_id = '', $email = '', $nick = '')
    {
        # Initialize the User Object
        $this->user = null;

        /**
         * Get User via DB Queries
         *
         * 1) user_id
         * 2) email
         * 3) nick
         * 4) session_id
         */

        if ( !empty($user_id) )
        {
            // Get the user from the user_id
            $this->user = Doctrine_Query::create()
                         ->select('u.*')
                         ->from('CsUsers u')
                         ->leftJoin('u.CsUserOptions o')
                         ->leftJoin('u.CsGroups g')
                         ->where('u.user_id = ?')
                         ->fetchOne(array($user_id), Doctrine::FETCH_ARRAY);
        }
        else if ( !empty($email) )
        {
            // Get the user from the email
            $this->user = Doctrine_Query::create()
                         ->select('u.*')
                         ->from('CsUsers u')
                         ->leftJoin('u.CsUserOptions o')
                         ->leftJoin('u.CsGroups g')
                         ->where('u.email = ?')
                         ->fetchOne(array($email), Doctrine::FETCH_ARRAY);
        }
        else if ( !empty($nick) )
        {
            // Get the user from the nick
            $this->user = Doctrine_Query::create()
                         ->select('u.*')
                         ->from('CsUsers u')
                         ->leftJoin('u.CsUserOptions o')
                         ->leftJoin('u.CsGroups g')
                         ->where('u.nick = ?')
                         ->fetchOne(array($nick), Doctrine::FETCH_ARRAY);
        }
        else
        {
            // Get the user from the session_id
            $session_result = Doctrine_Query::create()
                                ->select('user_id')
                                ->from('CsSession')
                                ->where('session_id = ?')
                                ->fetchOne(array(session_id()), Doctrine::FETCH_ARRAY);
        }

        // check if session-table[user_id] is a valid user-table[user_id]
        if (!empty($_SESSION['user']['user_id']))
        {
            if ( isset($session_result) and $session_result['user_id'] == $_SESSION['user']['user_id'] )
            {
                $this->user = Doctrine_Query::create()
                             ->from('CsUsers u')
                             ->leftJoin('u.CsUserOptions o')
                             ->leftJoin('u.CsGroups g')
                             ->where('u.user_id = ?')
                             ->fetchOne(array($session_result['user_id']), Doctrine::FETCH_ARRAY);

            }
            else
            {
                session_regenerate_id(true);
            }
        }

        // check if this user is activated, else reset cookie, session and redirect
        if ( is_array($this->user) AND $this->user['activated'] == 0 )
        {
            $this->logoutUser();
            $functions->redirect( 'index.php?mod=account&action=activation_email', 'metatag|newsite', 5, _('Your account is not yet activated - please enter your email in the form that appears in 5 seconds to resend the activation email.') );
        }

        /**
         * Create $_SESSION['user'] array, containing user data
         */
        if ( is_array($this->user) )
        {
            /**
             * User infos
             */
            //var_dump($_SESSION);
            //var_dump($this->config);
            $_SESSION['user']['authed']         = 1;
            $_SESSION['user']['user_id']        = $this->user['user_id'];

            $_SESSION['user']['passwordhash']   = $this->user['passwordhash'];
            $_SESSION['user']['email']          = $this->user['email'];
            $_SESSION['user']['nick']           = $this->user['nick'];

            $_SESSION['user']['disabled']       = $this->user['disabled'];
            $_SESSION['user']['activated']      = $this->user['activated'];

            // Fallback: first take user['language'], else standard language as defined by $this->config['->language
            if ( !isset($_SESSION['user']['language_via_url']) )
            {
                $_SESSION['user']['language'] = (!empty($this->user['language']) ? $this->user['language'] : $this->config['language']['language']);
            }

            // Fallback: first take standard theme as defined by $config->theme
            if ( !isset($_REQUEST['theme']) )
            {
                $_SESSION['user']['theme'] = (!empty($this->user['theme']) ? $this->user['theme'] : $this->config['template']['theme']);
            }

            /**
             * Get Groups & Rights of user_id
             */

            # Initialize User Session Arrays
            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $groups = Doctrine_Query::create()
                         ->select('g.group_id, r.right_id, r.name')
                         ->from('CsGroups g')
                         ->leftJoin('g.CsRights r')
                         ->where('g.group_id = ?')
                         ->fetchOne(array(1), Doctrine::FETCH_ARRAY);

            #var_dump($this->user['CsGroups']);

            if ( is_array( $this->user['CsGroups'] ) )
            {
                foreach( $this->user['CsGroups'] as $key => $group )
                {
                    $_SESSION['user']['groups'][] = $group['group_id'];

                    $rights = Doctrine_Query::create()
                                 ->select('g.group_id, r.right_id, r.name')
                                 ->from('CsGroups g')
                                 ->leftJoin('g.CsRights r')
                                 ->where('g.group_id = ?')
                                 ->fetchOne(array(1), Doctrine::FETCH_ARRAY);

                    if( is_array( $rights['CsRights'] ) )
                    {
                        foreach( $rights['CsRights'] as $key => $values )
                        {
                            $_SESSION['user']['rights'][$values['name']] = 1;
                        }
                    }

                    /* OLD PDO Style
                    $stmt = $this->db->prepare( 'SELECT rg.*, ri.* FROM ' . DB_PREFIX . 'group_rights AS rg
                                                 JOIN ' . DB_PREFIX . 'rights AS ri
                                                 ON ri.right_id = rg.right_id
                                                 WHERE rg.group_id = ?' );
                    $stmt->execute( array( $group_id['group_id'] ) );
                    $rights = $stmt->fetchAll(PDO::FETCH_ASSOC);


                    if( is_array( $rights ) )
                    {
                        foreach( $rights as $key => $values )
                        {
                            $_SESSION['user']['rights'][$values['name']] = 1;
                        }
                    }
                    */
                }
            }
        }
        else // reset $_SESSION['user'] array
        {
            /**
             * Fill $_SESSION[user] with Guest-User-infos
             */

            $_SESSION['user']['authed']         = 0;
            $_SESSION['user']['user_id']        = 0;
            $_SESSION['user']['nick']           = 'Guest'; #T_('Guest');

            $_SESSION['user']['passwordhash']   = '';
            $_SESSION['user']['email']          = '';

            $_SESSION['user']['disabled']       = 0;
            $_SESSION['user']['activated']      = 0;

            // Fallback: Language for Guest Users as defined by $this->config['language']['language']
            if (empty($_SESSION['user']['language']))
            {
                $_SESSION['user']['language']   = $this->config['language']['language'];
            }

            // Fallback: Theme for Guest Users as defined by config['template']['theme']
            if (empty($_SESSION['user']['theme']))
            {
                $_SESSION['user']['theme']      = $this->config['template']['theme'];
            }

            /**
             * Groups & Rights (Guest Group id = 1)
             */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $_SESSION['user']['groups'][] = 1;


            $rights = Doctrine_Query::create()
                         ->select('g.group_id, r.right_id, r.name')
                         ->from('CsGroups g')
                         ->leftJoin('g.CsRights r')
                         ->where('g.group_id = ?')
                         ->fetchOne(array(1), Doctrine::FETCH_ARRAY);
            #var_dump($rights);

            /* OLD PDO Style
            $stmt = $this->db->prepare( 'SELECT rg.*, ri.* FROM ' . DB_PREFIX . 'group_rights AS rg
                                         JOIN ' . DB_PREFIX . 'rights AS ri
                                         ON ri.right_id = rg.right_id
                                         WHERE rg.group_id = ?' );
            $stmt->execute( array( 1 ) );
            $rights = $stmt->fetchAll(PDO::FETCH_ASSOC);
            */

            #var_dump($rights);

            if( is_array( $rights['CsRights'] ) )
            {
                foreach( $rights['CsRights'] as $key => $values )
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

    public function checkUser($login_method = 'nick', $value, $passwordhash)
    {
        $user = null;

        // check if a given nick or email exists
        if( $login_method == 'nick' )
        {
            // get user_id and passwordhash with the nick
            $user = Doctrine_Query::create()
                         ->select('user_id, passwordhash, salt')
                         ->from('CsUsers u')
                         ->where('nick = ?')
                         ->fetchOne(array($value), Doctrine::FETCH_ARRAY);
        }

        // check if a given email exists
        if( $login_method == 'email' )
        {
            // get user_id and passwordhash with the email
            $user = Doctrine_Query::create()
                         ->select('user_id, passwordhash, salt')
                         ->from('CsUsers u')
                         ->where('email = ?')
                         ->fetchOne(array($value), Doctrine::FETCH_ARRAY);
        }

        // if user was found, check if passwords match each other
        if ( $user && $this->security->check_salted_hash( $passwordhash, $user['passwordhash'], $user['salt'] ) )
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

    public function loginUser($user_id, $remember_me, $password)
    {
        /**
         * 1. Create the User Data Array and the Session via $user_id
         */
        $this->createUser($user_id);

        /**
         * 2. Remember-Me ( set Logindata via Cookie )
         */
        if ( $remember_me == 1 )
        {
            setcookie('user_id', $user_id, time() + round($this->config['login']['remember_me_time']*24*60*60));
            # @todo note by vain:
            # build_salted_hash deprecated check security.class.php
            setcookie('password', $this->security->build_salted_hash( $password ), time() + round($this->config['login']['remember_me_time']*24*60*60));
        }

        /**
         * 3. user_id is now inserted into the session without user_id
         * This transforms the so called Guest-Session to a User-Session
         */
        $this->sessionSetUserId();

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
     * Logout a user
     *
     */

    public function logoutUser()
    {
        // Destroy the session
        session_regenerate_id(true);

        // Delete cookies
        setcookie('user_id', false );
        setcookie('password', false );
    }

    /**
     * Checks if a login cookie is set
     *
     */

    public function checkLoginCookie()
    {
        /**
         * Check for login cookie
         */

        if ( !empty($_COOKIE['user_id']) && !empty($_COOKIE['password']) )
        {
            $this->user = Doctrine_Query::create()
                                ->select('user_id,passwordhash')
                                ->from('CsUsers')
                                ->where('user_id = ?')
                                ->fetchOne(array((int)$_COOKIE['user_id']), Doctrine::FETCH_ARRAY);

            /**
             * Proceed if match
             */

            if ( is_array($this->user) &&
                 $this->security->build_salted_hash( $_COOKIE['password'] ) == $this->user['password'] &&
                 $_COOKIE['user_id'] == $this->user['user_id'] )
            {
                /**
                 * Update the cookie
                 */

                setcookie('user_id', $_COOKIE['user_id'], time() + round($this->config['login']['remember_me_time']*24*60*60));
                setcookie('password',$_COOKIE['password'], time() + round($this->config['login']['remember_me_time']*24*60*60));

                /**
                 * Create $this->session['user']
                 */

                $this->createUser($this->user['user_id']);

                /**
                 * Update Session in DB
                 */

                $this->sessionSetUserId();
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
     * @todo: maybe param user_id?
     */
    public function sessionSetUserId()
    {
        $result = Doctrine_Query::create()
                         #->select('*') // automatically set when left out
                         ->from('CsSession')
                         ->where('session_id = ?')
                         ->fetchOne(array( session_id() ));

        if ( $result )
        {
            /**
             * Update Session, because we know that session_id already exists
             */
            $result->user_id = $this->user['user_id'];
            $result->save();
            return true;
        }
        return false;
    }
}
?>