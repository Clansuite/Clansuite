<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf � 2005-2007
    * http://www.clansuite.com/
    *
    * File:         session.class.php
    * Requires:     PHP 5.1.4+
    *
    * Purpose:      Clansuite Core Class for Session Handling
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
 *  Database Table Structure for cs_session
 *
    CREATE TABLE `cs_session` (
    `user_id` int(11) NOT NULL default '0',
    `session_id` varchar(32) NOT NULL default '',
    `session_data` text NOT NULL,
    `session_name` text  NOT NULL,
    `session_expire` int(11) NOT NULL default '0',
    `session_visibility` tinyint(4) NOT NULL default '0',
    `session_where` text NOT NULL,
    PRIMARY KEY  (`session_id`),
    UNIQUE KEY `session_id` (`session_id`),
    KEY `user_id` (`user_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 *
 */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}


/**
 * This is the Clansuite Core Class for Session Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @category    core
 * @subpackage  session
 */

class session implements ISessionHandler, ArrayAccess
{
    /**
     * Set common session vars
     */
    #protected $session = null;

    /**
     * @access public
     * @var string $session_name contains the session name
     */

    const session_name = 'suiteSID';

    /**#@+
     * @access public
     * @var integer
     */
    /**
     * Session Expire time in minutes
     */

    public $session_expire_time     = 30;

    /**
     * Probabliity of trashing the Session as percentage
     */

    public $session_probability     = 30;
    public $session_cookies         = 1;
    public $session_cookies_only    = 0;

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
     * @var object $db is the Reference to PDO
     */

    private $config     = null;
    private $db         = null;
    private $request    = null;
    private $lang       = null;
    private $error      = null;
    private $functions  = null;
    private $input      = null;

    /**
     * This creates the session
     *
     * Overwrite php.ini settings
     * Start the session
     * @global $this->config, $this->lang, $this->error, $this->functions, $input
     * @param object
     */

    function __construct($injector)
    {
        /**
         * Setup References
         */

        $this->config       = $injector->instantiate('configuration');
        $this->db           = $injector->instantiate('db');
        $this->request      = $injector->instantiate('httprequest');
        $this->lang         = $injector->instantiate('language');
        $this->error        = $injector->instantiate('errorhandler');
        $this->functions    = $injector->instantiate('functions');
        $this->input        = $injector->instantiate('input');

        /**
         * Set the session configuration Parameters accordingly to Config Class Values
         */
        #$this->session_name                 = $this->config['session_name'];
        $this->session_cookies              = $this->config['use_cookies'];
        $this->session_cookies_only         = $this->config['use_cookies_only'];
        ini_set('session.save_handler'      , 'user' );
        ini_set('session.gc_maxlifetime'    , $this->config['session_expire_time']);
        ini_set('session.gc_probability'    , $this->session_probability );
        ini_set('session.name'              , self::session_name );
        # use_trans_sid off -> because spiders will index with PHPSESSID = thing
        ini_set('session.use_trans_sid'     , 0 );
        ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,formfieldset=");
        ini_set('session.use_cookies'       , $this->config['use_cookies'] );
        ini_set('session.use_only_cookies'  , $this->config['use_cookies_only'] );

        /**
         * Setup the custom session handler
         */

        session_set_save_handler(   array($this, "_session_open"   ),
                                    array($this, "_session_close"  ),
                                    array($this, "_session_read"   ),
                                    array($this, "_session_write"  ),
                                    array($this, "_session_destroy"),
                                    array($this, "_session_gc"     ));

        /**
         * Start Session and throw Error on failure
         */
        try
        {
            session_start();
        }
        catch (Exception $e) {
            throw new clansuite_exception('The session start failed!', 500);
        }

        /**
         * Create new ID if session is not in DB or string-lenght corrupted
         */

        #if ($this->_session_read(session_id() ) === false OR strlen(session_id() ) != 32)
        #{
        #    session_regenerate_id();
        #}

        /**
        * Perform Security Check
        *
        * If Session doesn't pass this, redirect to login.
        */

        if (!$this->_session_check_security() )
        {
            die($this->functions->redirect('index.php?mod=login') );
        }

        /**
         * Control the session
         */

        $this->session_control();

        /**
         *  Register shutdown
         */

        register_shutdown_function('session_write_close');
    }

    /**
     * Opens a session
     *
     * @return true
     */

    function _session_open()
    {
        return true;
    }

    /**
     * Closes a session
     *
     * @return true
     */

    function _session_close()
    {
        session::_session_gc(0);
        session_write_close();
        return true;
    }

    /**
     * Reads a session
     *
     * @param integer $id contains the session_id
     */

    function _session_read( $id )
    {
        #echo 'session id =>'. $id;
        $stmt = $this->db->prepare('SELECT session_data FROM ' . DB_PREFIX .'session WHERE session_name = ? AND session_id = ?' );
        $stmt->execute( array(self::session_name, $id ) );


        if ($result = $stmt->fetch())
        {
            return $result['session_data'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Write a session
     *
     * @param integer $id contains session_id
     * @param array $data contains session_data
     */

    function _session_write( $id, $data )
    {
        /**
         * Determine Expiretime of Session
         *
         * $this->session_expire_time is a minute time value, we get this from config settings
         * $sessionLifetime is seconds
         */
        $sessionlifetime = $this->session_expire_time * 60;
        $expires = time() + $sessionlifetime;

        /**
         * Determine the current location of a user by checking request['mod']
         *
         #var_dump($_REQUEST);
         #var_dump($this->request);
         *
         */
        $userlocation = (!isset($request['mod']) && empty($this->request['mod'])) ? 'sessionstart' : $this->request['mod'];
        #echo 'location '.$userlocation;

        /**
         * Check if session_id exists in DB
         */

        $stmt = $this->db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
        $stmt->execute( array( $id ) );
        $session_result = $stmt->fetch();
        $stmt->closeCursor();
        $stmt = NULL;


        if ( is_array($session_result) )
        {
            /**
             * Update Session, because we know that session_id already exists
             */

            $stmt = $this->db->prepare('UPDATE ' . DB_PREFIX . 'session SET session_expire = ? , session_data = ?, session_where = ? WHERE session_id = ?' );
            $stmt->execute(array($expires, $data, $userlocation, $id ) );
            $stmt->closeCursor();
        }
        else
        {
            /**
             * Insert Session, because we got no session_result for that session_id
             */

            $stmt = $this->db->prepare('INSERT INTO ' . DB_PREFIX . 'session (session_id, session_name, session_expire, session_data, session_visibility, user_id, session_where) VALUES(?,?,?,?,?,?,?)' );
            $stmt->execute(array($id, self::session_name, $expires, $data, 1, 0, $userlocation ) );
            $stmt->closeCursor();
        }
        return true;
    }

    /**
     * Destroy a session
     */

    function _session_destroy( $id )
    {
        /**
         * Unset Session
         */

        unset($_SESSION);

        /**
         *  Unset Cookie Vars
         */

        if (isset($_COOKIE[self::session_name]))
        {
            setcookie(self::session_name, false );
        }

        /**
         * Delete session from DB
         */

        $stmt = $this->db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_id = ?' );
        $stmt->execute(array(self::session_name, $id ) );

        /**
         *  Optimize DB
         */

        if ($stmt->rowCount() > 0)
        {
            $this->_session_optimize();
        }

    }

    /**
     * Session garbage collector
     *
     * Removes the current session, if the session max_lifetime expired
     *
     * @param integer $max_lifetime contains the session-liftetime value
     * @todo by vain: wtf? $max_lifetime as parameter does nothing? we compare to the time ?
     */

    function _session_gc($max_lifetime)
    {
        $stmt = $this->db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_expire < ?' );
        $stmt->execute(array(self::session_name, time() ) );

        if ($stmt->rowCount() > 0)
        {
            $this->_session_optimize();
        }
    }

    /**
     * Optimize the session table
     *
     * @todo function is deactivated - Check how session table can be optimized while using pdo
     */

    function _session_optimize()
    {
        /* NON WORKING WITH PDO
        $stmt = $this->exec->query('OPTIMIZE TABLE ' . DB_PREFIX . 'session');
        $stmt->closeCursor();
        */

    }

    /**
     * Ensure the session integrity
     *
     * 1. Check for IP
     * 2. Check for Browser
     * 3. Check for Host Address
     *
     * @return boolean
     */

    function _session_check_security()
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
                session::_session_destroy(session_id());
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
                session::_session_destroy(session_id());
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
                session::_session_destroy(session_id());
                return false;
            }
        }

        /**
         *  Return true if everything is ok
         */

        return true;
    }

    /** */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

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
     * Sets a new SESSION_ID into SESSION['NAME']
     */
    public static function regenerate_session_id()
    {
        $_SESSION[self::session_name] = session_id();
    }

    /**
     * Session control
     * 1. Prune no activated users
     * 2. Prune out-timed Sessions
     * 3.
     *
     * @global object $function
     * @global array  $lang
     */

    function session_control()
    {
        /**
         *  Delete not activated users after 3 days
         */

        $stmt = $this->db->prepare( 'DELETE FROM ' . DB_PREFIX . 'users WHERE activated = ? AND joined < ?');
        $stmt->execute( array( 0, ( time() - (60*60*24*3) ) ) );

        /**
         *  Delete all out-timed Sessions
         */

        $stmt = $this->db->prepare( 'DELETE FROM ' . DB_PREFIX . 'session WHERE session_expire < ?' );
        $stmt->execute( array( time() ) );

        /**
         *  Check if session expired
         */

        if ( ( !isset($_COOKIE['user_id']) OR !isset($_COOKIE['password']) ) )
        #AND $_SESSION['user']['user_id'] != 0 )
        {
            $stmt = $this->db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
            $stmt->execute( array( $this->request[self::session_name] ) );
            $res = $stmt->fetch();
            if ( !is_array($res) )
            {
                #$this->functions->redirect( 'index.php?mod=account&action=login', 'metatag|newsite', 3, $this->lang->t('Your session has expired. Please login again.') );
            }
        }

        /**
         *  Assign Session Time Values for Session Countdown
         */
        $expire_seconds = $this->session_expire_time * 60;
        $time = time();
        $expiretime = $time + $expire_seconds;

        //@note by vain: assigns for Session Countdown
        #$tpl->assign('SessionCurrentTime', $time);
        #$tpl->assign('SessionExpireTime', $expiretime);
    }


    public function __set($offset,$data) {
		#echo("Name: {$offset}<br/>\nData: {$value}<br/>\n");
		$this->session[$offset] = $value;
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
        $this->__set($offset, $value);
    }

    // @todo
    // @note by vain: check if this works on single arrays of session?
    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);
        return true;
    }

}

interface ISessionHandler
{
    public function _session_open();
    public function _session_close();
    public function _session_read($id);
    public function _session_write($id, $data);
    public function _session_destroy($id);
    public function _session_gc($max_lifetime);
}

?>
