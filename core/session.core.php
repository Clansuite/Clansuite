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
    * @author     Jens-André Koch <vain@clansuite.com>
    * @copyright  Jens-André Koch (2005 - onwards)
    * @link       http://www.clansuite.com
    *
    * @version    SVN: $Id$
    */

//Security Handler
if (defined('IN_CS') === false)
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
    # stop applications to influcence each other by applying a session_name
    const session_name = 'CsuiteSID';

    /**
     * Session Expire time in seconds.
     * 1800 seconds / 60 = 30 Minutes
     *
     * @var integer
     */
    public $session_expire_time = 1800;

    /**
     * Probabliity of trashing the Session as percentage.
     * (This implies that gc_divisor is fixed to 100.)
     *
     * @var integer
     */
    public $session_probability = 10;

    /**
     * @var array
     */
    private $config = array();

    /**
     * This creates the session.
     *
     * Injections:
     * Clansuite_Config is needed for the configuration of session variables.
     * Clansuite_HttpRequest is needed to determine the current location of the user on the website.
     *
     * @todo reading and writing the session are transactions! implement
     *
     * Overwrite php.ini settings
     * Start the session
     * @param object Clansuite_Config
     * @param object Clansuite_HttpRequest
     */

    function __construct(Clansuite_Config $config)
    {
        $this->config   = $config;

        # session auto_start must be disabled
        if (ini_get('session.auto_start') != 0)
        {
            throw new Clansuite_Exception('PHP Setting session.auto_start must be disabled.');
        }

        /**
         * Set the Session Expire Time.
         * The value comming from the clansuite config and is a minute value.
         */
        if(isset($this->config['session']['session_expire_time'])
           and $this->config['session']['session_expire_time'] <= 60)
        {
            $this->session_expire_time = $this->config['session']['session_expire_time'] * 60;
        }

        # configuration not needed any longer, free some memory
        unset($this->config);

        /**
         * Configure Session
         */
        ini_set('session.name', self::session_name );
        ini_set('session.save_handler', 'user');

        /**
         * Configure Garbage Collector
         * This will call the GC in 10% of the requests.
         * Calculation : gc_probability/gc_divisor = 10/100 = 0,1 = 10%
         */
        ini_set('session.gc_maxlifetime', $this->session_expire_time);
        ini_set('session.gc_probability', $this->session_probability);
        ini_set('session.gc_divisor', 100 );

        # use_trans_sid off -> because spiders will index with PHPSESSID
        # use_trans_sid on  -> considered evil
        ini_set('session.use_trans_sid', 0 );

        # @todo check if there is a problem with rewriting
        #ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,formfieldset=");

        # use a cookie to store the session id (no session_id's in URL)
        # session cookies are forced!
        ini_set('session.use_cookies', 1 );
        ini_set('session.use_only_cookies', 1 );

        # stop javascript accessing the cookie (XSS)
        ini_set('session.cookie_httponly', 1);

        /**
         * Setup the custom session handler methods
         * Userspace Session Storage
         */
        session_set_save_handler(   array($this, 'session_open'   ),
                                    array($this, 'session_close'  ),
                                    array($this, 'session_read'   ),
                                    array($this, 'session_write'  ), # this redefines session_write_close()
                                    array($this, 'session_destroy'), # this redefines session_destroy()
                                    array($this, 'session_gc'     )
                                 );


        # Start Session
        self::startSession($this->session_expire_time);

        # Apply and Check Session Security
        self::validateAndSecureSession();
    }

    /**
     * Start Session and throw Error on failure
     */
    private static function startSession($time = 1800)
    {
        # set cookie parameters
        session_set_cookie_params($time);

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
     * Change from a permissive to a strict session system by applying
     * several security flags.
     *
     * A new session ID is created when
     * a) if session string-lenght corrupted
     * b) OR not initiated already
     * c) OR application token missing
     */
    private static function validateAndSecureSession()
    {
        if(mb_strlen(session_id()) != 32 or false === isset($_SESSION['application']['initiated']))
        {
            # Make a new session_id and destroy old session
            # from PHP 5.1 on , if set to true, it will force the session extension to remove the old session on an id change
            session_regenerate_id(true);

            # session fixation
            $_SESSION['application']['initiated'] = true;

            /**
             * Session Security Token
             * CSRF: http://shiflett.org/articles/cross-site-request-forgeries
             */
            # session token
            $_SESSION['application']['token'] = md5(uniqid(rand(), true));

            # session time
            $_SESSION['application']['token_time'] = time();
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
            $em = Clansuite_CMS::getEntityManager();
            $query = $em->createQuery('SELECT s.session_data, s.session_starttime
                                       FROM \Entities\Session s
                                       WHERE s.session_name = :name
                                       AND s.session_id = :id');
            $query->setParameters(array('name' => self::session_name, 'id' => $id));
            $result = $query->getResult();

            if( $result )
            {
                return (string) $result[0]['session_data'];  # unserialize($result['session_data']);
            }
        }
        catch(Exception $e)
        {
            # @todo can we throw a clansuite_exception here?
            echo get_class($e).' thrown within the session handler. <br /> Message: '.$e->getMessage().' on line '.$e->getLine();
            $uri = sprintf('http://%s%s', $_SERVER['SERVER_NAME'], dirname($_SERVER['PHP_SELF']) . '/installation/index.php');
            exit('<p><b><font color="#FF0000">[Clansuite Error] The database table for sessions is missing.</font></b> <br />
                 Please use <a href="' . $uri . '">Clansuite Installation</a> to perform a proper installation.</p>');
        }
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
         * @todo wrong place. move either to statistics or whoisonline
         * determine the current location of a user by checking request['mod']
         */
        #if(isset($this->request['mod']) == false and empty($this->request['mod']))
        #{
        #    $userlocation = 'sessionstart';
        #}
        #else
        #{
        #    $userlocation = $this->request['mod'];
        #}

        /**
         * Try to INSERT Session Data or REPLACE Session Data in case session_id already exists
         */
        $em = Clansuite_CMS::getEntityManager();
        $query = $em->createQuery('UPDATE \Entities\Session s
             SET s.session_id = :id,
             s.session_name = :name,
             s.session_starttime = :time,
             s.session_data = :data,
             s.session_visibility = :visibility,
             s.session_where = :where,
             s.user_id = :user_id
             WHERE s.session_id = :id');
        $query->setParameters(
                array( 'id' => $id,
                       'name' => self::session_name,
                       'time' => time(),
                       'data' => $data, # @todo serialize($data)
                       'visibility' => '1', # @todo ghost mode
                       'where' => 'session_start',
                       'user_id' => '0'
                    ));
        $query->execute();

        return true;
    }

    /**
     * Destroy the current session.
     *
     * This redefines php's session_destroy()
     *
     * @param  string $session_id
     */
    public function session_destroy($session_id)
    {
        // Unset all of the session variables.
        $_SESSION = array();

        //  Unset Cookie Vars
        if(isset($_COOKIE[self::session_name]))
        {
            setcookie(self::session_name, false);
        }

        /**
         * Delete session from DB
         */
        $em = Clansuite_CMS::getEntityManager();
        $query = $em->createQuery('DELETE \Entities\Session s
                                   WHERE s.session_name = :name
                                     AND s.session_id = :id');
        $query->setParameters(array('name' => self::session_name,
                                    'id' => $session_id));
        $query->execute();
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
    public function session_gc($maxlifetime = 30)
    {
        if($maxlifetime == 0 )
        {
            return;
        }

        /**
         * Determine Expiretime of Session
         *
         * $maxlifetime is a minute time value, we get this from $config['session']['session_expire_time']
         * $sessionLifetime is seconds
         */
        $sessionlifetime = $maxlifetime * 60;
        $expire_time = time() + $sessionlifetime;

        $em = Clansuite_CMS::getEntityManager();
        $query = $em->createQuery('DELETE \Entities\Session s
                                   WHERE s.session_name = :name
                                     AND s.session_starttime < :time');
        $query->setParameters(array('name' => self::session_name,
                                    'time' => $expire_time));
        $query->execute();

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