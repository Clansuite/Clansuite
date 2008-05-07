<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005 - onwards
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
    * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 */

//Security Handler
if (!defined('IN_CS')){ die('Clansuite not loaded. Direct Access forbidden.' );}


/**
 * This is the Clansuite Core Class for Session Handling
 *
 * @author     Jens-Andre Koch   <vain@clansuite.com>
 * @author     Florian Wolf      <xsign.dll@clansuite.com>
 * @copyright  Jens-Andre Koch (2005 - onwards), Florian Wolf (2006-2007)
 * @since      Class available since Release 0.1
 *
 * @package     clansuite
 * @subpackage  session
 * @category    core
 */

class Clansuite_Session implements Clansuite_Session_Interface, ArrayAccess
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
    private $response   = null;

    /**
     * Clansuite_Session is a Singleton
     */
    public static function getInstance($injector)
    {
    	static $instance;

        if ( ! isset($instance)) {
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

        $this->config       = $injector->instantiate('configuration');
        $this->db           = $injector->instantiate('db');
        $this->request      = $injector->instantiate('httprequest');
        $this->response     = $injector->instantiate('httpresponse');

        # Set the session configuration Parameters accordingly to Config Class Values
        
        $this->session_cookies              = $this->config['use_cookies'];
        $this->session_cookies_only         = $this->config['use_cookies_only'];
        
        /**
         * Configure Session
         *
         * @todo: http://bugs.php.net/bug.php?id=32330 
         * PHPBUG -> session_set_save_handler() session_destroy())
         */
        session_module_name("files");
        ini_set('session.save_handler', 'files');                   # workaround for save_handler user is causing a strange bug
        #ini_set('session.save_handler'      , 'user' );
        ini_set("session.save_path", "C:/xampplite/temp");          # Session Temp Path outside the Clansuite Directory 
        #ini_set("session.save_path", ROOT . 'tmp');                                        # Session Temp Path inside the Clansuite Directory
        // Garbage Collector not needed, because it's gettin called everytime in session_control()
        #ini_set('session.gc_maxlifetime'    , $this->config['session_expire_time']);
        #ini_set('session.gc_probability'    , 10 ); // 10% of the requests will call the gc
        #ini_set('session.gc_divisor'        , 100 );
        ini_set('session.name'              , self::session_name );
        # use_trans_sid off -> because spiders will index with PHPSESSID
        ini_set('session.use_trans_sid'     , 0 );
        ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,formfieldset=");
        ini_set('session.use_cookies'       , $this->config['use_cookies'] );
        ini_set('session.use_only_cookies'  , $this->config['use_cookies_only'] );

        # Setup the custom session handler

        session_set_save_handler(   array($this, "session_open"   ),
                                    array($this, "session_close"  ),
                                    array($this, "session_read"   ),
                                    array($this, "session_write"  ),
                                    array($this, "session_destroy"),
                                    array($this, "session_gc"     ));

        
        # Start Session and throw Error on failure
        
        try
        {
            session_start();
           
        }
        catch (Exception $exception) 
        {
            throw new clansuite_exception( $exception, 'The session start failed!', 200);
            exit;
        }

        # Create new ID if session is not in DB or string-lenght corrupted

        if ($this->session_read(session_id()) === false OR strlen(session_id()) != 32 OR !isset($_SESSION['initiated']))
        {
            session_regenerate_id(true);    # Make a new session_id and destroy old session
            $this->session_close();
            session_start();
            $_SESSION['initiated'] = true;  # session fixation
            
            // The token prevents PHP from generating a fresh session_id correctly
            # var_dump(strlen(session_id()));
            # $token = md5(uniqid(rand(), true)); # session token
            # $_SESSION['token'] = $token;
        }
        
        # Perform a Security Check on the Session, and if it doesn't pass this, redirect to login.
        
        if (!$this->_session_check_security() )
        {
            die($this->response->redirect('index.php?mod=login') );
        }

        # Control the session ( Clean up: 3day registrations, old sessions)
        
        //$this->session_control();

        # Register shutdown
        
        register_shutdown_function(array($this,'session_close'));
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
     * @return mixed (boolean false or data)
     */
    public function session_read( $id )
    {
        #echo 'session id =>'. $id;
        #echo self::session_name;
        /* OLD PDO Style
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
        */
        $result = Doctrine_Query::create()
                         ->select('session_data, session_expire')
                         ->from('CsSession')
                         ->where('session_name = ? AND session_id = ?')
                         ->fetchOne(array(self::session_name, $id ), Doctrine::FETCH_ARRAY);
        if( $result )
        {
            if( $result['session_expire'] < time() )
            {
                return false;
            }
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
     * @return bool
     */
    public function session_write( $id, $data )
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

        $result = Doctrine_Query::create()
                         #->select('*') // automatically set when left out
                         ->from('CsSession')
                         ->where('session_name = ? AND session_id = ?')
                         ->fetchOne(array(self::session_name, $id ));
        
        /* OLD PDO Style
        $stmt = $this->db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
        $stmt->execute( array( $id ) );
        $session_result = $stmt->fetch();
        $stmt->closeCursor();
        $stmt = NULL;
        */


        if ( $result )
        {
            /**
             * Update Session, because we know that session_id already exists
             */
            $result->session_expire = $expires;
            $result->session_data = $data;
            $result->session_where = $userlocation;
            $result->save();

            #var_dump($result);
            /* OLD PDO Style
            $stmt = $this->db->prepare('UPDATE ' . DB_PREFIX . 'session SET session_expire = ? , session_data = ?, session_where = ? WHERE session_id = ?' );
            $stmt->execute(array($expires, $data, $userlocation, $id ) );
            $stmt->closeCursor();

            $rows = Doctrine_Query::create()
                             ->update('CsSession')
                             ->set('session_expire', $expires)
                             ->set('session_data', $data)
                             ->set('session_where', $userlocation)
                             ->where('session_name = ? AND session_id = ?')
                             ->execute(array(self::session_name, $id ));
            var_dump($rows);
            */
            
        }
        else
        {
            /**
             * Insert Session, because we got no session_result for that session_id
             */
            $newSession = new CsSession();
            $newSession->session_id = $id;
            $newSession->session_name = self::session_name;
            $newSession->session_expire = $expires;
            $newSession->session_data = $data;
            $newSession->session_visibility = 1;
            $newSession->session_where = $userlocation;
            $newSession->user_id = 0;
            $newSession->save();
            
            #var_dump($newSession);
            /* OLD PDO Style
            $stmt = $this->db->prepare('INSERT INTO ' . DB_PREFIX . 'session (session_id, session_name, session_expire, session_data, session_visibility, user_id, session_where) VALUES(?,?,?,?,?,?,?)' );
            $stmt->execute(array($id, self::session_name, $expires, $data, 1, 0, $userlocation ) );
            $stmt->closeCursor();
            */
        }
        return true;
    }

   /**
	 * Destroy the current session.
	 *
	 * @param  string $session_id
	 * @return void
	 */
    public function session_destroy( $session_id )
    {
        // Unset all of the session variables.
        $_SESSION = array();

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
        $rows = Doctrine_Query::create()
                                 ->delete('CsSession')
                                 ->from('CsSession')
                                 ->where('session_name = ? AND session_id = ?')
                                 ->execute(array( self::session_name, $session_id ));
        #var_dump($rows);
        /*
        $stmt = $this->db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_id = ?' );
        $stmt->execute(array(self::session_name, $session_id ) );*/

        /**
         *  Optimize DB
         */
        if ($rows > 0)
        {
            $this->_session_optimize();
        }
    }

    /**
     * Session garbage collector
     *
     * Removes the current session, if:
     * a) gc probability is reached (ini_set)
     * b) time() is reached (DB has timestamp stored, that is time() + expiration )
     */

    public function session_gc()
    {
        # echo xdebug_call_function();
        $rows = Doctrine_Query::create()
                                 ->delete('CsSession')
                                 ->from('CsSession')
                                 ->where('session_name = ? AND session_expire < ?')
                                 ->execute(array( self::session_name, time() ));
        /* OLD PDO Style
        $stmt = $this->db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_expire < ?' );
        $stmt->execute(array(self::session_name, time() ) );
        */
        #var_dump($rows);
        
        if ($rows > 0)
        {
            $this->_session_optimize();
        }
    }

    /**
     * Optimize the session table
     *
     * @todo function is deactivated - Check how session table can be optimized while using pdo
     */

    public function _session_optimize()
    {
        /* NOT WORKING WITH PDO
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
         *  Return true if everything is ok
         */

        return true;
    }

   

    
    /**
     * Sets a new SESSION_ID into SESSION['NAME']
     */
    /*
    * NOT NEEDED ANYMORE - replaced by built-in php function session_regenerate_id(true) with destroy ability
    
    public static function regenerate_session_id()
    {
        echo xdebug_call_function();
        var_dump('WTF');
        $_SESSION[self::session_name] = session_id();
    }
    
    */

    /**
     * Session control
     * 1. [ Prune no activated users ]
     * 2. Prune timed out Sessions (just call gc)
     * 3. 
     *
     * @TODO: Move user prune to users class
     */

    public function session_control()
    {
        # NOT NEEDED - gc_ probabilites are set by php itself ( see ini_set in __construct() )
        # Perform Garbage Control
        # $this->session_gc(1);
                
        /**
         *  Delete not activated users after 3 days
         * @TODO: move to users class!
         */
        $rows = Doctrine_Query::create()
                                 ->delete('CsUsers')
                                 ->from('CsUsers')
                                 ->where('activated = ? AND joined < ?')
                                 ->execute( array( 0, ( time() - (60*60*24*3) ) ) );
        
        /* OLD PDO Style
        $stmt = $this->db->prepare( 'DELETE FROM ' . DB_PREFIX . 'users WHERE activated = ? AND joined < ?');
        $stmt->execute( array( 0, ( time() - (60*60*24*3) ) ) );
        */

        /**
         *  Delete all timed out Sessions
         */

        $this->session_gc();
        
        /* OLD PDO Style
        $stmt = $this->db->prepare( 'DELETE FROM ' . DB_PREFIX . 'session WHERE session_expire < ?' );
        $stmt->execute( array( time() ) );
        */

        /**
         *  Check whether session is expired
         */

        if ( ( !isset($_COOKIE['user_id']) OR !isset($_COOKIE['password']) ) )
        #AND $_SESSION['user']['user_id'] != 0 )
        {
            $result = Doctrine_Query::create()
                                ->select('user_id, session_expire')
                                ->from('CsSession')
                                ->where('session_id = ?')
                                ->fetchOne(array( $this->request[self::session_name] ), Doctrine::FETCH_ARRAY);
            /*
            $stmt = $this->db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
            $stmt->execute( array( $this->request[self::session_name] ) );
            $res = $stmt->fetch();
            */
            if ( $result && $result['session_expire'] < time() )
            {
                //session_regenerate_id(true);
                //var_dump($result);
                #$this->response->redirect( 'index.php?mod=account&action=login', 'metatag|newsite', 3, _('Your session has expired. Please login again.') );
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

      
    /**
     * set()
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * get()
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

    // @todo
    // @note by vain: check if this works on single arrays of session?
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
    public function session_gc();
}
?>