<?php
/**
    * Clansuite - just an E-Sport CMS
    * Jens-André Koch © 2005 - onwards
    * http://www.clansuite.com/
    *
    * This file is part of "Clansuite - just an eSports CMS".
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
    * @license    GNU/GPL v2 or (at your option) any later version, see "/doc/LICENSE".
    *
    * @author     Jens-André Koch   <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

// Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * This Clansuite Core Class for Users Handling
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  User
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

    function __construct(Clansuite_Config $config, Clansuite_Security $security, Clansuite_Inputfilter $input )
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
        if($user_id == null)
        {
           $user_id = $_SESSION['user']['user_id'];
        }
        else
        {
            $user_id = (int) $user_id;
        }
         /*
        $userdata = Doctrine_Query::create()
                        ->select($fields)
                        ->from('CsUser')
                        ->leftJoin('CsProfile')
                        ->where('CsUser.user_id = ?')
                        ->fetchOne(array($user_id), Doctrine::HYDRATE_ARRAY);
                           */
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
                         #->select('u.*,g.*,o.*')
                         ->from('CsUser u')
                         ->leftJoin('u.CsOption o')
                         ->leftJoin('u.CsGroup g')
                         ->leftJoin('g.CsRight r')
                         ->where('u.user_id = ?')
                         ->fetchOne(array($user_id), Doctrine::HYDRATE_ARRAY);
        }
        else if ( !empty($email) )
        {
            // Get the user from the email
            $this->user = Doctrine_Query::create()
                         #->select('u.*,g.*,o.*')
                         ->from('CsUser u')
                         ->leftJoin('u.CsOption o')
                         ->leftJoin('u.CsGroup g')
                         ->leftJoin('g.CsRight r')
                         ->where('u.email = ?')
                         ->fetchOne(array($email), Doctrine::HYDRATE_ARRAY);
        }
        else if ( !empty($nick) )
        {
            // Get the user from the nick
            $this->user = Doctrine_Query::create()
                         #->select('u.*,g.*,o.*')
                         ->from('CsUser u')
                         ->leftJoin('u.CsOption o')
                         ->leftJoin('u.CsGroup g')
                         ->leftJoin('g.CsRight r')
                         ->where('u.nick = ?')
                         ->fetchOne(array($nick), Doctrine::HYDRATE_ARRAY);

        }
        /*else
        {
            // Get the user from the session_id
            $session_result = Doctrine_Query::create()
                                ->select('user_id')
                                ->from('CsSession')
                                ->where('session_id = ?')
                                ->fetchOne(array(session_id()), Doctrine::HYDRATE_ARRAY);
        } */

        // check if session-table[user_id] is a valid user-table[user_id]
        /*if (!empty($_SESSION['user']['user_id']))
        {
            if ( isset($session_result) and $session_result['user_id'] == $_SESSION['user']['user_id'] )
            {
                $this->user = Doctrine_Query::create()
                             ->from('CsUser u')
                             ->leftJoin('u.CsOption o')
                             ->leftJoin('u.CsGroup g')
                             ->leftJoin('g.CsRight r')
                             ->where('u.user_id = ?')
                             ->fetchOne(array($session_result['user_id']), Doctrine::HYDRATE_ARRAY);

            }
            else
            {
                session_regenerate_id(true);
            }
        }  */

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
            
            $_SESSION['user']['backendtheme'] = (!empty($this->user['backendtheme']) ? $this->user['backendtheme'] : $this->config['template']['backend_theme']);

            /**
             * Get Groups & Rights of user_id
             */

            # Initialize User Session Arrays
            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            if ( isset($this->user['CsGroup']) && is_array( $this->user['CsGroup'] ) )
            {
                foreach( $this->user['CsGroup'] as $key => $group )
                {
                    $_SESSION['user']['groups'][] = $group['group_id'];

                    if( isset($group['CsRight']) && is_array( $group['CsRight'] ) )
                    {
                        foreach( $group['CsRight'] as $key => $values )
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
            if ( !isset($_SESSION['user']['language_via_url']) )
            {
                $_SESSION['user']['language']   = $this->config['language']['language'];
            }

            // Fallback: Theme for Guest Users as defined by config['template']['theme']
            if (empty($_SESSION['user']['theme']))
            {
                $_SESSION['user']['theme']      = $this->config['template']['theme'];
            }

            # @todo this is a workaround / backendthemes is enabled for guests .. it's nonsense
            # this rule is active, unless the login is activated
            $_SESSION['user']['backendtheme'] = (!empty($this->user['backendtheme']) ? $this->user['backendtheme'] : $this->config['template']['backend_theme']);

            /**
             * Groups & Rights (Guest Group id = 1)
             */

            $_SESSION['user']['groups'] = array();
            $_SESSION['user']['rights'] = array();

            $_SESSION['user']['groups'][] = 1;
            $_SESSION['user']['rights'][] = 1;

            /*
            $rights = Doctrine_Query::create()
                         ->select('g.group_id, r.right_id, r.name')
                         ->from('CsGroup g')
                         ->leftJoin('g.CsRight r')
                         ->where('g.group_id = ?')
                         ->fetchOne(array(1), Doctrine::HYDRATE_ARRAY);*/
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
            /*
            if( is_array( $rights['CsRight'] ) )
            {
                foreach( $rights['CsRight'] as $key => $values )
                {
                    $_SESSION['user']['rights'][$values['name']] = 1;
                }
            }
            */
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
                         ->from('CsUser u')
                         ->where('nick = ?')
                         ->fetchOne(array($value), Doctrine::HYDRATE_ARRAY);
        }

        // check if a given email exists
        if( $login_method == 'email' )
        {
            // get user_id and passwordhash with the email
            $user = Doctrine_Query::create()
                         ->select('user_id, passwordhash, salt')
                         ->from('CsUser u')
                         ->where('email = ?')
                         ->fetchOne(array($value), Doctrine::HYDRATE_ARRAY);
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
     */

    public function loginUser($user_id, $remember_me, $passwordhash)
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
            setcookie('cs_cookie_user_id', $user_id, time() + round($this->config['login']['remember_me_time']*24*60*60));
            # @todo note by vain:
            # build_salted_hash deprecated check security.class.php
            setcookie('cs_cookie_password', $passwordhash, time() + round($this->config['login']['remember_me_time']*24*60*60));
        }

        /**
         * 3. user_id is now inserted into the session without user_id
         * This transforms the so called Guest-Session to a User-Session
         */
        $this->sessionSetUserId($user_id);

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
        setcookie('cs_cookie_user_id', false );
        setcookie('cs_cookie_password', false );
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

        if ( !empty($_COOKIE['cs_cookie_user_id']) && !empty($_COOKIE['cs_cookie_password']) )
        {
            $this->user = Doctrine_Query::create()
                                ->select('user_id,passwordhash,salt')
                                ->from('CsUser')
                                ->where('user_id = ?')
                                ->fetchOne(array((int)$_COOKIE['cs_cookie_user_id']), Doctrine::HYDRATE_ARRAY);

            /**
             * Proceed if match
             */

            if ( is_array($this->user) &&
                 $this->security->check_salted_hash( $_COOKIE['cs_cookie_password'], $this->user['passwordhash'], $this->user['salt'] ) &&
                 $_COOKIE['cs_cookie_user_id'] == $this->user['user_id'] )
            {
                /**
                 * Update the cookie
                 */

                setcookie('cs_cookie_user_id', $_COOKIE['cs_cookie_user_id'], time() + round($this->config['login']['remember_me_time']*24*60*60));
                setcookie('cs_cookie_password',$_COOKIE['cs_cookie_password'], time() + round($this->config['login']['remember_me_time']*24*60*60));

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

                setcookie('cs_cookie_user_id', false );
                setcookie('cs_cookie_password', false );
            }
        }
    }


    /**
     * Sets user_id to a session
     */
    public function sessionSetUserId($user_id)
    {
        /*$result = Doctrine_Query::create()
                         #->select('*') // automatically set when left out
                         ->from('CsSession')
                         ->where('session_id = ?')
                         ->fetchOne(array( session_id() ));

        if ( $result )
        {
            /**
             * Update Session, because we know that session_id already exists
             */   /*
            $result->user_id = $user_id;
            $result->save();
            return true;
        }
        return false; */
    }

    /**
     * Checks a permission
     */
    public static function hasAccess( $permission = '' )
    {
        if( $permission == '' )
            return false;

        if ( isset($_SESSION['user']['rights'][$permission]) && $_SESSION['user']['rights'][$permission] == 1 )
        {
            return true;
        }
        return false;
    }

    /**
     * DELETE : USERS which have joined but are not activated after 3 days.
     *
     * 259200 = (60s * 60m * 24h * 3d)
     */
     public function deleteJoinedButNotActivitatedUsers()
     {
        $query = Doctrine_Query::create()->delete('CsUser')
                                         ->from('CsUser')
                                         ->where('activated = ? AND joined < ?')
                                         ->execute( array( 0, time() - 259200 ) );
     }
}
?>