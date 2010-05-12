<?php
   /**
    * Clansuite - just an eSports CMS
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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (defined('IN_CS') == false)
{
    die('Clansuite not loaded. Direct Access forbidden.');
}

/**
 * This is the Clansuite Core Class for Session Handling
 *
 * Purpose:    Clansuite Core Class for Session Handling
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Session
 */
class Clansuite_Session implements Clansuite_Session_Interface, ArrayAccess
{
    #protected $session = null; # Set common session vars

    const session_name = 'suiteSID'; # session_name contains the session name

    /**
     * @var integer
     */
    public $session_expire_time     = 30; # Session Expire time in minutes
    public $session_probability     = 30; # Probabliity of trashing the Session as percentage

    /**
     * @var object
     */
    private $config     = null;
    private $request    = null;

    /**
     * This creates the session
     *
     * Injections:
     * Clansuite_Configuration is needed for the configuration of session variables.
     * Clansuite_HttpRequest is needed to determine the current location of the user on the website.
     *
     * Overwrite php.ini settings
     * Start the session
     * @param object $injector Contains the Dependency Injector Phemto.
     */

    function __construct(Clansuite_Config $config, Clansuite_HttpRequest $request)
    {
        $this->config   = $config;
        $this->request  = $request;

        /**
         * Configure Session
         */
        ini_set('session.name', self::session_name );
        ini_set('session.save_handler', 'user');

        /**
         * Configure Garbage Collector
         * This will call the GC in 10% of the requests.
         * Calculation : gc_probability/gc_divisor = 1/10 = 0,1 = 10%
         */
        ini_set('session.gc_maxlifetime', $this->config['session']['session_expire_time']);
        ini_set('session.gc_probability', 1 );
        ini_set('session.gc_divisor', 10 );

        # use_trans_sid off -> because spiders will index with PHPSESSID
        # use_trans_sid on  -> considered evil
        ini_set('session.use_trans_sid', 0 );

        # @todo check if there is a problem with rewriting
        #ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,formfieldset=");

        # use a cookie to store the session id
        ini_set('session.use_cookies', $this->config['session']['use_cookies'] );
        ini_set('session.use_only_cookies', $this->config['session']['use_cookies_only'] );

        # Setup the custom session handler methods
        session_set_save_handler(   array($this, 'session_open'   ),
                                    array($this, 'session_close'  ),
                                    array($this, 'session_read'   ),
                                    array($this, 'session_write'  ), # this redefines session_write_close()
                                    array($this, 'session_destroy'), # this redefines session_destroy()
                                    array($this, 'session_gc'     )
                                 );

        # Create new ID, if session string-lenght corrupted OR not initiated already OR application token missing
        if (  strlen(session_id()) != 32 or !isset($_SESSION['initiated']) or ((string) $_SESSION['application'] != 'CS-'.CLANSUITE_REVISION))
        {
            # Make a new session_id and destroy old session
            # from PHP 5.1 on , if set to true, it will force the session extension to remove the old session on an id change
            session_regenerate_id(true);

            # session fixation
            $_SESSION['initiated']      = true;

            # application-marker
            $_SESSION['application']    = 'CS-'.CLANSUITE_REVISION;

            /**
             * Session Security Token
             * CSRF: http://shiflett.org/articles/cross-site-request-forgeries
             */
            # session token
            $_SESSION['token']      = md5(uniqid(rand(), true));

            # session time
            $_SESSION['token_time'] = time();
        }

        # Start Session
        $this->startSession(3600);
    }

    /**
     * Start Session and throw Error on failure
     */
    private function startSession($time = 3600)
    {
        # set cookie parameters
        session_set_cookie_params($time);
        session_set_cookie_params(0, ROOT);

        # START THE SESSION
        if( true === session_start())
        {
            # Set Cookie + adjust the expiration time upon page load
            setcookie(self::session_name, session_id() , time() + $time, '/');
        }
        else
        {
            throw new Clansuite_Exception('The session start failed!', 200);
        }
    }

    /**
     * =========================================
     *      Custom Session Handler Methods
     * =========================================
     */

    /**
     * Opens a session
     *
     * @return true
     */
    public function session_open()
    {
        return true;
    }

    /**
     * Closes a session
     *
     * @return true
     */
    public function session_close()
    {
        session_write_close();
        return true;
    }

    /**
     * Reads a session
     *
     * @param integer $id contains the session_id
     * @return string string of the session data
     */
    public function session_read( $id )
    {
        # Debug Display
        #echo 'Session ID is: '. $id .' AND Session NAME is:'. self::session_name;

        try
        {
            $result = Doctrine_Query::create()
                             ->select('session_data, session_starttime')
                             ->from('CsSession')
                             ->where('session_name = ? AND session_id = ?')
                             ->fetchOne(array(self::session_name, $id ), Doctrine::HYDRATE_ARRAY);

            if( $result )
            {
                return (string) $result['session_data'];  # unserialize($result['session_data']);
            }
        }
        catch(Exception $e)
        {
            echo get_class($e).' thrown within the exception handler. Message: '.$e->getMessage().' on line '.$e->getLine();
            exit;
        }

        return '';
    }

    /**
     * Write a session
     *
     * This redefines php's session_write_close()
     *
     * @param integer $id contains session_id
     * @param array $data contains session_data
     *
     * @return bool
     */
    public function session_write($id, $data)
    {
        /**
         * Determine the current location of a user by checking request['mod']
         */
        if(isset($request['mod']) == false and empty($this->request['mod']))
        {
            $userlocation = 'sessionstart';
        }
        else
        {
            $userlocation = $this->request['mod'];
        }
        #echo 'location '.$userlocation;

        /**
         * Try to INSERT Session Data or REPLACE Session Data in case session_id already exists
         */
        $session_query = new CsSession();
        $session_query->session_id         = $id;
        $session_query->session_name       = self::session_name;
        $session_query->session_starttime  = time();
        $session_query->session_data       = $data;
        $session_query->session_visibility = 1;
        $session_query->session_where      = $userlocation;
        $session_query->user_id            = 0;
        $session_query->replace();

        return true;
    }

   /**
     * Destroy the current session.
     *
     * This redefines php's session_destroy()
     *
     * @param  string $session_id
     */
    public function session_destroy( $session_id )
    {
        // Unset all of the session variables.
        $_SESSION = array();

        //  Unset Cookie Vars
        if (isset($_COOKIE[self::session_name]))
        {
            setcookie(self::session_name, false );
        }

        /**
         * Delete session from DB
         */
        $rows = Doctrine_Query::create()
                                 ->delete('CsSession')
                                 ->from('CsSession')
                                 ->where('session_name = ? AND session_id = ?')
                                 ->execute(array( self::session_name, $session_id ));
    }

     /**
     * Session Garbage Collector
     *
     * @param int session life time (mins)
     * Removes the current session, if:
     * a) gc probability is reached (ini_set)
     * b) time() is reached (DB has timestamp stored, that is time() + expiration )
     * @see session.gc_divisor      100
     * @see session.gc_maxlifetime  1800 = 30*60
     * @see session.gc_probability    1
     * @usage execution rate 1/100 (session.gc_probability/session.gc_divisor)
     * @return boolean
     */
    public function session_gc($maxlifetime)
    {
        if($maxlifetime == 0 )
        {
            return;
        }

        /**
         * Determine Expiretime of Session
         *
         * $maxlifetime is a minute time value, we get this from config settings ['session']('session_expire_time']
         * $sessionLifetime is seconds
         */
        $sessionlifetime = $maxlifetime * 60;
        $expire_time = time() + $sessionlifetime;

        Doctrine_Query::create()
                 ->delete('CsSession')
                 ->from('CsSession')
                 ->where('session_name = ? AND session_starttime < ?')
                 ->execute(array( self::session_name, $expire_time  ));

        return true;
    }

    /**
     * =======================
     *       Get and Set
     * =======================
     */

    /**
     * Sets Data into the Session.
     *
     * @param string key
     * @param mixed  value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets Data from the Session.
     *
     * @param string key
     *
     * @return mixed value/boolean false
     */
    public function get($key)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        else
        {
            return false;
        }
    }

    /**
     * =====================================
     *   Implementation of SPL ArrayAccess
     * =====================================
     */

    public function offsetExists($offset)
    {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    // @todo note by vain: check if this works on single array of session?
    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);
        return true;
    }
}

/**
 * Interface for Clansuite_Session
 *
 * @category    Clansuite
 * @package     Core
 * @subpackage  Session
 */
interface Clansuite_Session_Interface
{
    public function session_open();
    public function session_close();
    public function session_read($id);
    public function session_write($id, $data);
    public function session_destroy($id);
    public function session_gc($maxlifetime);
}
?>