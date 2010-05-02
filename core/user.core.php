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

# Security Handler
if (defined('IN_CS') == false){ die('Clansuite not loaded. Direct Access forbidden.'); }

/**
 * This Clansuite Core Class for Users Handling
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 *
 * @category    Clansuite
 * @package     Modules
 * @subpackage  User
 */
class Clansuite_User
{
    /**
     * @var object User Object
     */
    private $user       = null;

    /**
     * @var object Clansuite_Configuration
     */
    private $config     = null;

    private $input      = null;

    /**
     * Constructor
     *
     * Set up references
     */
    function __construct(Clansuite_Config $config)
    {
        $this->config       = $config;
    }

    /**
     * getUser
     *
     * @param integer $user_id The ID of the User. Default is user_id from session.
     * @return array $userdata (Dataset of CsUsers + CsProfile)
     */
    public function getUser( $user_id = null)
    {
        if($user_id == null)
        {
            $user_id = $_SESSION['user']['user_id'];
        }
        else
        {
            $user_id = (int) $user_id;
        }

        $userdata = Doctrine_Query::create()
                ->select($fields)
                ->from('CsUsers')
                ->leftJoin('CsProfile')
                ->where('CsUsers.user_id = ?')
                ->fetchOne(array($user_id), Doctrine::HYDRATE_ARRAY);

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
     * @param $user_id The ID of the User.
     * @param $email The email of the User.
     * @param $nick The nick of the User.
     */
    public function createUserSession($user_id = '', $email = '', $nick = '')
    {
        # Initialize the User Object
        $this->user = null;

        /**
         * Get User via DB Queries
         *
         * 1) user_id
         * 2) email
         * 3) nick
         */

        if( empty($user_id) == false )
        {
            # Get the user from the user_id
            $this->user = Doctrine_Query::create()
                    #->select('u.*,g.*,o.*')
                    ->from('CsUsers u')
                    ->leftJoin('u.CsOption o')
                    ->leftJoin('u.CsGroup g')
                    ->leftJoin('g.CsRight r')
                    ->where('u.user_id = ?')
                    ->fetchOne(array($user_id), Doctrine::HYDRATE_ARRAY);
        }
        elseif( empty($email) == false )
        {
            # Get the user from the email
            $this->user = Doctrine_Query::create()
                    #->select('u.*,g.*,o.*')
                    ->from('CsUsers u')
                    ->leftJoin('u.CsOption o')
                    ->leftJoin('u.CsGroup g')
                    ->leftJoin('g.CsRight r')
                    ->where('u.email = ?')
                    ->fetchOne(array($email), Doctrine::HYDRATE_ARRAY);
        }
        elseif( empty($nick) == false )
        {
            # Get the user from the nick
            $this->user = Doctrine_Query::create()
                    #->select('u.*,g.*,o.*')
                    ->from('CsUsers u')
                    ->leftJoin('u.CsOption o')
                    ->leftJoin('u.CsGroup g')
                    ->leftJoin('g.CsRight r')
                    ->where('u.nick = ?')
                    ->fetchOne(array($nick), Doctrine::HYDRATE_ARRAY);

        }

        # check if this user is activated, else reset cookie, session and redirect
        if ( is_array($this->user) and $this->user['activated'] == 0 )
        {
            $this->logoutUser();

            # redirect
            Clansuite_CMS::getInjector()
                    ->instantiate('Clansuite_HttpResponse')
                    ->redirect( 'index.php?mod=account&action=activation_email', 5, 403, _('Your account is not yet activated.'));
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

            /**
             * Language
             *
             * first take user['language'], else standard language as defined by $this->config['->language
             */
            if ( isset($_SESSION['user']['language_via_url']) == false )
            {
                $_SESSION['user']['language'] = (!empty($this->user['language']) ? $this->user['language'] : $this->config['language']['language']);
            }

            /**
             * Frontend-Theme
             *
             * first take standard theme as defined by $config->theme
             */
            if ( isset($_REQUEST['theme']) == false )
            {
                $_SESSION['user']['theme'] = (!empty($this->user['theme']) ? $this->user['theme'] : $this->config['template']['theme']);
            }

            /**
             * Backend-Theme
             */
            if(empty($this->user['backendtheme']) == false)
            {
                $_SESSION['user']['backendtheme'] = $this->user['backendtheme'];
            }
            else
            {
                $_SESSION['user']['backendtheme'] = $this->config['template']['backend_theme'];
            }

            /**
             * Permissions
             *
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
        else
        {
            # this resets the $_SESSION['user'] array
            Clansuite_GuestUser::getInstance();
        }
    }

    /**
     * Check the user
     *
     * Validates the existance of the user via nick or email and the passwordhash
     * This is done in two steps:
     * 1. check if given nick or email exists
     * and if thats the case
     * 2. compare password from login form with database
     *
     * @param string $login_method contains the login_method ('nick' or 'email')
     * @param string $value contains nick or email string to look for
     * @param string $password contains password string
     *
     * @return int ID of User. If the user is found, the $user_id - otherwise false.
     */
    public function checkUser($login_method = 'nick', $value, $passwordhash)
    {
        $user = null;

        # check if a given nick or email exists
        if( $login_method == 'nick' )
        {
            # get user_id and passwordhash with the nick
            $user = Doctrine_Query::create()
                    ->select('user_id, passwordhash, salt')
                    ->from('CsUsers u')
                    ->where('nick = ?')
                    ->fetchOne(array($value), Doctrine::HYDRATE_ARRAY);
        }

        # check if a given email exists
        if( $login_method == 'email' )
        {
            # get user_id and passwordhash with the email
            $user = Doctrine_Query::create()
                    ->select('user_id, passwordhash, salt')
                    ->from('CsUsers u')
                    ->where('email = ?')
                    ->fetchOne(array($value), Doctrine::HYDRATE_ARRAY);
        }

        # Fetch Clansuite_Security Helpers
        $security = Clansuite_CMS::getInjector()->instantiate('Clansuite_Security');

        # if user was found, check if passwords match each other
        if( is_null($user) == false and $security->check_salted_hash( $passwordhash, $user['passwordhash'], $user['salt'] ))
        {
            # ok, the user with nick or email exists and the passwords matched, then return the user_id
            return $user['user_id'];
        }
        else
        {
            # no user was found with this combination of either nick and password or email and password
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
        $this->createUserSession($user_id);

        /**
         * 2. Remember-Me ( set Logindata via Cookie )
         */
        if ( $remember_me == 1 )
        {
            $this->setRememberMeCookie($user_id, $passwordhash);
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
     * set the remember me cookie
     * if this cookie is found, the user is re-logged in automatically
     *
     * @param integer $user_id contains user_id
     * @param string $password contains password string
     */
    private function setRememberMeCookie($user_id, $passwordhash)
    {
        # calculate cookie lifetime
        $cookie_lifetime = time() + round($this->config['login']['remember_me_time']*24*60*60);

        setcookie('cs_cookie_user_id', $user_id, $cookie_lifetime);
        setcookie('cs_cookie_password', $passwordhash, $cookie_lifetimee);

        unset($cookie_lifetime);
    }

    /**
     * Logout
     */
    public function logoutUser()
    {
        # Destroy the session
        session_regenerate_id(true);

        # Delete cookies
        setcookie('cs_cookie_user_id', false );
        setcookie('cs_cookie_password', false );
    }

    /**
     * Checks if a login cookie is set
     */
    public function checkLoginCookie()
    {
        # Check for login cookie
        if ( isset($_COOKIE['cs_cookie_user_id']) and isset($_COOKIE['cs_cookie_password']) )
        {
            $this->user = Doctrine_Query::create()
                    ->select('user_id,passwordhash,salt')
                    ->from('CsUsers')
                    ->where('user_id = ?')
                    ->fetchOne(array((int) $_COOKIE['cs_cookie_user_id']), Doctrine::HYDRATE_ARRAY);

            /**
             * Proceed if match
             */

            if ( is_array($this->user) and
                    $this->security->check_salted_hash( $_COOKIE['cs_cookie_password'], $this->user['passwordhash'], $this->user['salt'] ) &&
                    $_COOKIE['cs_cookie_user_id'] == $this->user['user_id'] )
            {
                # Update the cookie
                $this->setRememberMeCookie($_COOKIE['cs_cookie_user_id'], $_COOKIE['cs_cookie_password']);

                # Create the user session array ($this->session['user'] etc.) by using this user_id
                $this->createUserSession($this->user['user_id']);

                # Update Session in DB
                $this->sessionSetUserId();
            }
            else # Delete cookies, if no match

            {
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
        Doctrine_Query::create()
                ->delete('CsUsers')
                ->from('CsUsers')
                ->where('activated = ? AND joined < ?')
                ->execute( array( 0, time() - 259200 ) );
    }

    /**
     * Check whether a user is logged in
     *
     * @return Returns true if user is authed, false otherwise.
     */
    public function isUserAuthed()
    {
        $boolResult = false;
        if( isset($_SESSION['user']['authed']) and ($_SESSION['user']['authed'] === 1) )
        {
            $boolResult = true;
        }

        return $boolResult;
    }

    /**
     * Gives the UserID
     *
     * @return int UserID
     */
    public function getUserIdFromSession()
    {
        return $_SESSION['user']['user_id'];
    }
}

/**
 * Clansuite_GuestUser represents the initial values of the user object.
 * This is the guest visitor.
 */
class Clansuite_GuestUser
{
    /**
     * @var object Clansuite_GuestUser is a singleton.
     */
    static private $instance = null;

    /**
     * @var object Clansuite_Configuration
     */
    private $config = null;

    /**
     * @return This object is a singleton.
     */
    static public function getInstance()
    {
        if (null === self::$instance)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function __construct()
    {
        $this->config = Clansuite_CMS::getInjector()->instantiate('Clansuite_Config');

        /**
         * Fill $_SESSION[user] with Guest-User-infos
         */

        $_SESSION['user']['authed']         = 0;
        $_SESSION['user']['user_id']        = 0;            # guest have user_id 0
        $_SESSION['user']['nick']           = _('Guest');

        $_SESSION['user']['passwordhash']   = '';
        $_SESSION['user']['email']          = '';

        $_SESSION['user']['disabled']       = 0;
        $_SESSION['user']['activated']      = 0;

        /**
         * Language for Guests
         *
         * Sets the Default Language for all Guest Visitors as defined by configuration value,
         * if not already set via a GET request, like "index.php?lang=fr".
         */
        if ( isset($_SESSION['user']['language_via_url']) == false )
        {
            $_SESSION['user']['language']   = $this->config['language']['language'];
        }

        /**
         * Theme for Guests
         *
         * Sets the Default Theme for all Guest Visitors, if not already set via a GET request.
         * Theme for Guest Users as defined by config['template']['theme']
         */
        if (empty($_SESSION['user']['theme']))
        {
            $_SESSION['user']['theme']      = $this->config['template']['theme'];
        }

        /**
         * Permissions for Guests
         */

        # Reset Groups
        $_SESSION['user']['groups'] = array();
        $_SESSION['user']['groups'][] = 1;

        # Reset Rights
        $_SESSION['user']['rights'] = array();
        $_SESSION['user']['rights'][] = 1;

        # Database Lookup
        /*$rights = Doctrine_Query::create()
                     ->select('g.group_id, r.right_id, r.name')
                     ->from('CsGroups g')
                     ->leftJoin('g.CsRights r')
                     ->where('g.group_id = ?')
                     ->fetchOne(array(1), Doctrine::HYDRATE_ARRAY);*/

        #Clansuite_XDebug::firebug($rights);

        /*
        if( is_array( $rights['CsRight'] ) )
        {
            foreach( $rights['CsRight'] as $key => $values )
            {
                $_SESSION['user']['rights'][$values['name']] = 1;
            }
        }
        */

        #Clansuite_XDebug::firebug($_SESSION);
    }
}
?>