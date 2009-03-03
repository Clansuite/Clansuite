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
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}

/**
 * This is the Clansuite Core Class for Session Handling
 *
 * Purpose:    Clansuite Core Class for Session Handling
 *
 * @author     Jens-André Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-André Koch (2005 - onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @subpackage  session
 * @category    core
 */
class Clansuite_Session implements Clansuite_Session_Interface, ArrayAccess
{
    #protected $session = null; # Set common session vars

    const session_name = 'suiteSID'; # session_name contains the session name

    /**#@+
     * @access public
     * @var integer
     */
    public $session_expire_time     = 30; # Session Expire time in minutes
    public $session_probability     = 30; # Probabliity of trashing the Session as percentage


    /**#@-*/

    /**
     * Array with Settings for the Session Security Check
     * possible options are: 'check_ip', 'check_browser', 'check_host'
     *
     * @access public
     */
    public $session_security    = array('check_ip', 'check_browser', 'check_host');

    /**
     * @access public
     * @var object
     */
    private  $config     = null;
    private  $request    = null;
    private  $response   = null;

    /**
     * Clansuite_Session is a Singleton
     *
     * @param object $injector DependencyInjector
     *
     * @return instance of session class
     */
    public static function getInstance($injector)
    {
    	static $instance;

        if ( ! isset($instance))
        {
            $instance = new Clansuite_Session($injector);
        }

        return $instance;
    }

    /**
     * This creates the session
     *
     * Overwrite php.ini settings
     * Start the session
     * @param object $injector Contains the Dependency Injector Phemto.
     */

    function __construct(Phemto $injector)
    {
        # Setup References

        $this->config       = $injector->instantiate('Clansuite_Config');
        $this->request      = $injector->instantiate('Clansuite_HttpRequest');
        $this->response     = $injector->instantiate('Clansuite_HttpResponse');

        /**
         * Configure Session
         */
        ini_set('session.name'              , self::session_name );
        ini_set('session.save_handler'      , 'user');

        /**
         * Configure Garbage Collector
         * This will call the GC in 10% of the requests.
         * Calculation : gc_probability/gc_divisor = 1/10 = 0,1 = 10%
         */
        ini_set('session.gc_maxlifetime'    , $this->config['session']['session_expire_time']);
        ini_set('session.gc_probability'    , 1 );
        ini_set('session.gc_divisor'        , 10 );

        # use_trans_sid off -> because spiders will index with PHPSESSID
        # use_trans_sid on  -> considered evil
        ini_set('session.use_trans_sid'     , 0 );
        ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,formfieldset=");
        ini_set('session.use_cookies'       , $this->config['session']['use_cookies'] );
        ini_set('session.use_only_cookies'  , $this->config['session']['use_cookies_only'] );

        # Setup the custom session handler
        session_set_save_handler(   array($this, "session_open"   ),
                                    array($this, "session_close"  ),
                                    array($this, "session_read"   ),
                                    array($this, "session_write"  ),
                                    array($this, "session_destroy"),
                                    array($this, "session_gc"     ));

        # Create new ID, if session is not in DB OR string-lenght corrupted OR not initiated already
        # $this->session_read(session_id()) == '' OR
        if ( strlen(session_id()) != 32 OR !isset($_SESSION['initiated']) OR ((string)$_SESSION['application'] != 'CS-'.CLANSUITE_REVISION))
        {
            session_regenerate_id(true);    # Make a new session_id and destroy old session
            $_SESSION['initiated']      = true;  # session fixation
            $_SESSION['application']    = 'CS-'.CLANSUITE_REVISION; # application-marker

            /**
             * Session Security Token
             * CSRF: http://shiflett.org/articles/cross-site-request-forgeries
             */
            $_SESSION['token']      = md5(uniqid(rand(), true)); # session token
            $_SESSION['token_time'] = time();
        }

        # Start Session
        $this->startSession();

        # Perform a Security Check on the Session, and if it doesn't pass this, redirect to login.

        if (!$this->_session_check_security() )
        {
            $this->response->redirect('index.php?mod=login');
        }


        # Control the session ( Clean up: 3day registrations, old sessions)

        #$this->session_control();
    }

    /**
     * Start Session and throw Error on failure
     */
    private function startSession($time = 3600, $ses = 'MYSES')
    {
        try
        {
            # set cookie parameters
            #session_set_cookie_params($time);
            #session_set_cookie_params(0, ROOT);

            # name the session
            #session_name(self::session_name);

            # START THE SESSION
            session_start();

            # Reset the expiration time upon page load
            if (isset($_COOKIE[$ses]))
            {
                setcookie($ses, $_COOKIE[$ses], time() + $time, "/");
            }

        }
        catch (Exception $exception)
        {
            throw new clansuite_exception( $exception, 'The session start failed!', 200);
            exit;
        }
    }

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

        $result = Doctrine_Query::create()
                         ->select('session_data, session_starttime')
                         ->from('CsSession')
                         ->where('session_name = ? AND session_id = ?')
                         ->fetchOne(array(self::session_name, $id ), Doctrine::HYDRATE_ARRAY);
        if( $result )
        {
            return (string) $result['session_data'];  # unserialize($result['session_data']);
        }
        return '';
    }

    /**
     * Write a session
     *
     * @param integer $id contains session_id
     * @param array $data contains session_data
     * @return bool
     */
    public function session_write( $id, $data )
    {
        /**
         * Determine the current location of a user by checking request['mod']
         */
        $userlocation = (!isset($request['mod']) && empty($this->request['mod'])) ? 'sessionstart' : $this->request['mod'];
        #echo 'location '.$userlocation;

        /**
         * Try to Update / Replace Session Data in case session_id already exists
         * $result is 1, when update successful, zero otherwise
         */
        $result = Doctrine_Query::create()
                         ->update('CsSession s')
                         ->set('s.session_starttime','?', time())
                         ->set('s.session_data','?', $data)
                         ->set('s.session_where','?', $userlocation)
                         ->where('s.session_id = ?', $id)
                         ->execute();

        /**
         * Insert Session using the Doctrine CsSession Object
         * because we got no result for that session_id during update
         */
        if ( $result == 0)
        {
            $newSession = new CsSession();
            $newSession->session_id         = $id;
            $newSession->session_name       = self::session_name;
            $newSession->session_starttime  = time();
            $newSession->session_data       = $data;
            $newSession->session_visibility = 1;
            $newSession->session_where      = $userlocation;
            $newSession->user_id            = 0;
            $newSession->save();
        }

        return true;
    }

   /**
	 * Destroy the current session.
	 *
	 * @param  string $session_id
	 *
	 * @return void
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
     * Ensure the session integrity
     *
     * 1. Check for IP
     * 2. Check for Browser
     * 3. Check for Host Address
     * 4. Check for Number of Password Tries
     *
     * @return boolean
     */

    public function _session_check_security()
    {
        /**
         * 1. Check for IP
         */

        if (in_array("check_ip", $this->session_security))
        {
            if ( !isset($_SESSION['client_ip']) )
            {
                $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'];
            }
            else if ($_SERVER['REMOTE_ADDR'] != $_SESSION['client_ip'])
            {
                $this->session_destroy(session_id());
                return false;
            }
        }

        /**
         * 2. Check for Browser
         */

        if ( in_array("check_browser", $this->session_security) )
        {
            if ( !isset($_SESSION['client_browser']) )
            {
                $_SESSION['client_browser'] = $_SERVER["HTTP_USER_AGENT"];
            }
            else if ( $_SERVER["HTTP_USER_AGENT"] != $_SESSION['client_browser'] )
            {
                $this->session_destroy(session_id());
                return false;
            }
        }

        /**
         * 3. Check for Host Address
         */

        if(in_array("check_host", $this->session_security))
        {
            if( !isset( $_SESSION['client_host'] ) )
            {
                $_SESSION['client_host'] = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
            }
            else if ( gethostbyaddr($_SERVER["REMOTE_ADDR"]) != $_SESSION['client_host'] )
            {
                $this->session_destroy(session_id());
                return false;
            }
        }

        /**
         * 4. Check for Number of Password Tries
         **/
        /*
        if(in_array("bad_password_tries", $this->session_security))
        {
            if($_SESSION['password_tries'] < $this->config['anti-hijack']['maximal_password_tries'])
            {
                if(true == $this->request->issetParameter('POST','password'))
                {
                    if(!isset($_SESSION['password_tries']))
                    {
                        $_SESSION['password_tries'] = 1;
                    }
                    elseif($_SESSION['password_tries'] <= $this->config['anti-hijack']['maximal_password_tries'])
                    {
                        $_SESSION['password_tries']++;
                    }

                    # perform password check here
                }
                else # reset
                {
                    unset($_SESSION['password_tries']);
                }
            }
            else
            {
                $this->session_destroy(session_id());
                return false;
            }
        }
        */

        /**
         *  Return true if everything is ok
         */

        return true;
    }

    /**
     * Session control
     * 1. Prune not activated users after 3 days
     * 2. Prune timed out Sessions (just call gc)
     * 3.
     *
     * @TODO: Move user prune to users class
     */
    public function session_control()
    {
        /**
         * DELETE : USERS which are not activated after 3 days.
         *
         * 259200 = (60s * 60m * 24h * 3d)
         * @todo: move to users class!
         */
        /*$query = Doctrine_Query::create()->delete('CsUser')
                                         ->from('CsUser')
                                         ->where('activated = ? AND joined < ?')
                                         ->execute( array( 0, time() - 259200 ) );*/

        /**
         *  CHECK whether session is EXPIRED
         */
        /*
        if ( ( !isset($_COOKIE['cs_cookie_user_id']) OR !isset($_COOKIE['cs_cookie_password']) ) )
        #AND $_SESSION['user']['user_id'] != 0 )
        {
            $result = Doctrine_Query::create()
                                ->select('user_id, session_starttime')
                                ->from('CsSession')
                                ->where('session_id = ?')
                                ->fetchOne(array( $this->request[self::session_name] ), Doctrine::HYDRATE_ARRAY);

            if ( $result && $result['session_expire'] < time() )
            {
                //session_regenerate_id(true);
                //var_dump($result);

                // deprecated redirect with message
                #$this->response->redirect('index.php?mod=account&action=login', 'metatag|newsite', 3, _('Your session has expired. Please login again.') );

                $this->response->redirect('index.php?mod=account&action=login', 0, '302' );

            }

        } */

        /**
         *  Assign Session Time Values for Session Countdown
         */
        /*$expire_seconds = $this->session_expire_time * 60;
        $time = time();
        $expiretime = $time + $expire_seconds;
         */
        //@todo: session countdown / ajax
        #$tpl->assign('SessionCurrentTime', $time);
        #$tpl->assign('SessionExpireTime', $expiretime);
    }


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
     * Implementation of SPL ArrayAccess
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

    // @todo: note by vain: check if this works on single array of session?
    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);
        return true;
    }
}

/**
 * Interface for Clansuite_Session
 *
 * @package clansuite
 * @subpackage session
 * @category interfaces
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