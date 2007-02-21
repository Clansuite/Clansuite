<?php
/**
* Session Handler Class
*
* PHP versions 5.1.4
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
*    You should have received a copy of the GNU General Public License
*    along with this program; if not, write to the Free Software
*    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* @author     Florian Wolf <xsign.dll@clansuite.com>
* @author     Jens-Andre Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: session.class.php 155 2006-06-13 20:55:04Z xsign $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/



/**
* @desc Table structure for cs_session
*/
/*
CREATE TABLE `cs_session` (
`user_id` int(11) NOT NULL default '0',
`session_id` varchar(255) NOT NULL default '',
`session_data` text NOT NULL,
`session_name` text  NOT NULL,
`session_expire` int(11) NOT NULL default '0',
`session_visibility` tinyint(4) NOT NULL default '0',
`session_where` text NOT NULL,
PRIMARY KEY  (`session_id`),
UNIQUE KEY `session_id` (`session_id`),
KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

*/


/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Class session start
*/
class session
{
    /**
    * @desc Init class session | set common session vars
    */

    public $session_name            = 'suiteSID';
    public $session_expire_time     = 30; // minutes
    public $session_probability     = 30; // precenatge
    public $session_cookies         = 1;
    public $session_cookies_only    = 0;
    public $session_security        = array('check_ip', 'check_browser', 'check_host');
    public $db;
    
    /**
    * @desc Overwrite php.ini settings
    * @desc Start the session
    */

    function create_session($db)
    {
        global $cfg, $lang, $error, $functions, $input;
        
        /**
        * @desc Reference PDO
        */

        $this->db = $db;
        
        /**
        * @desc Set the ini Vars and look for configs
        */

        $this->session_name                 = $cfg->session_name;
        $this->session_cookies              = $cfg->use_cookies;
        $this->session_cookies_only         = $cfg->use_cookies_only;
        ini_set('session.save_handler'      , 'user' );
        ini_set('session.gc_maxlifetime'    , $cfg->session_expire_time );
        ini_set('session.gc_probability'    , $this->session_probability );
        ini_set('session.name'              , $this->session_name );
        ini_set('session.use_trans_sid'     , 1 );
        ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,formfieldset=");
        ini_set('session.use_cookies'       , $cfg->use_cookies );
        ini_set('session.use_only_cookies'  , $cfg->use_cookies_only );
        
        /**
        * @desc Set the handlers
        */

        session_set_save_handler(   array($this, "_session_open"   ),
                                    array($this, "_session_close"  ),
                                    array($this, "_session_read"   ),
                                    array($this, "_session_write"  ),
                                    array($this, "_session_destroy"),
                                    array($this, "_session_gc"     ));
        
        
        /**
        * @desc Start Session and throw Error on failure
        */

        if (!session_start())
        {
            $error->show($lang->t('Session Error' ), $lang->t('The session start failed!' ), 3 );
        }
        
        /**
        * @desc Create new ID if session is not in DB or corrupted
        */

        if ($this->_session_read(session_id() ) === false OR strlen(session_id() ) != 32)
        {
            session_regenerate_id();
        }
        
        /**
        * @desc Security Check
        */

        if (!$this->_session_check_security() )
        {
            die($functions->redirect('index.php?mod=login') );
        }
        
        /**
        * @desc Control the session
        */

        $this->session_control();

        /**
        * @desc Rergister shutdown
        */

        register_shutdown_function('session_write_close');
    }
    
    /**
    * @desc Open a session
    */

    function _session_open()
    {
        return true;
    }
    
    /**
    * @desc Close a session
    */

    function _session_close()
    {
        session::_session_gc(0);
        return true;
    }
    
    /**
    * @desc Read a session
    */

    function _session_read( $id )
    {      
        $stmt = $this->db->prepare('SELECT session_data FROM ' . DB_PREFIX .'session WHERE session_name = ? AND session_id = ?' );
        $stmt->execute(array($this->session_name, $id ) );
        
        if ($result = $stmt->fetch() )
        {
            $data = $result['session_data'];
            return $data;
        }
        else
        {
            return false;
        }
    }
    
    /**
    * @desc Write a session
    */

    function _session_write( $id, $data )
    {       
        /**
        * @desc Time Settings
        */

        $seconds = $this->session_expire_time * 60;
        $expires = time() + $seconds;
        
        /**
        * @desc Check if session is in DB
        */

        $stmt = $this->db->prepare( 'SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
        $stmt->execute( array( $id ) );
        $res = $stmt->fetch();
        $stmt->closeCursor();
        $stmt = NULL;
        if ( is_array($res) )
        {
            /**
            * @desc Update Session in DB
            */

            $stmt = $this->db->prepare('UPDATE ' . DB_PREFIX . 'session SET session_expire = ? , session_data = ?, session_where = ? WHERE session_id = ?' );
            $stmt->execute(array($expires, $data, $_REQUEST['mod'], $id ) );
            $stmt->closeCursor();
        }
        else
        {
            /**
            * @desc Create Session @ DB & Cookies OR $_GET
            */

            $stmt = $this->db->prepare('INSERT INTO ' . DB_PREFIX . 'session (session_id, session_name, session_expire, session_data, session_visibility, user_id, session_where) VALUES(?,?,?,?,?,?,?)' );
            $stmt->execute(array($id, $this->session_name, $expires, $data, 1, 0, $_REQUEST['mod'] ) );
            $stmt->closeCursor();
        }
        return true;
    }
    
    /**
    * @desc Destroy a session
    */

    function _session_destroy( $id )
    {
        /**
        * @desc Unset Session
        */

        unset($_SESSION);
                
        /**
        * @desc Unset Cookie Vars
        */

        if (isset($_COOKIE[$this->session_name]))
        {
            setcookie($this->session_name, false );
        }

        /**
        * @desc Delete session from DB
        */

        $stmt = $this->db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_id = ?' );
        $stmt->execute(array($this->session_name, $id ) );

        /**
        * @desc Optimize DB
        */

        if ($stmt->rowCount() > 0)
        {
            $this->_session_optimize();
        }
        
    }

    /**
    * @desc Session garbage collector
    */

    function _session_gc($max_lifetime )
    {
        /**
        * @desc Prune
        */

        $stmt = $this->db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_expire < ?' );
        $stmt->execute(array($this->session_name, time() ) );
        
        if ($stmt->rowCount() > 0)
        {
            $this->_session_optimize();
        }
    }
    
    /**
    * @desc Optimize the session table
    */

    function _session_optimize()
    {
        /* NON WROKING WITH PDO
        $stmt = $this->exec->query('OPTIMIZE TABLE ' . DB_PREFIX . 'session');
        $stmt->closeCursor();
        */
    }
    
    /**
    * @desc Check for a secure session
    */

    function _session_check_security()
    {
        /**
        * @desc Check for IP
        */

        if (in_array("check_ip", $this->session_security))
        {
            if ( !isset($_SESSION['client_ip']) )
            {
                $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'];
            }
            else
            if ($_SERVER['REMOTE_ADDR'] != $_SESSION['client_ip'])
            {
                session::_session_destroy(session_id());
                return false;
            }
        }
        
        /**
        * @desc Check for Browser
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
        * @desc Check for Host Address
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
        * @desc Return true if everything is ok
        */

        return true;
    }

    /**
    * @desc Session control
    * @desc - prune timeouts 
    */

    function session_control()
    {
        global $functions, $lang;

        /**
        * @desc Prune not activated users
        */

        $stmt = $this->db->prepare( 'DELETE FROM ' . DB_PREFIX . 'users WHERE activated = 0 AND joined < ' . ( time() - 60*60*24*3 ) );
        $stmt->execute();
        
        /**
        * @desc Prune Sessions
        */

        $stmt = $this->db->prepare( 'DELETE FROM ' . DB_PREFIX . 'session WHERE session_expire < ' . time() );
        $stmt->execute();
        
        /**
        * @desc Check if session expired
        */

        if ( ( !isset($_COOKIE['user_id']) OR !isset($_COOKIE['password']) ) AND $_SESSION['user']['user_id'] != 0 )
        {
            $stmt = $this->db->prepare( 'SELECT user_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
            $stmt->execute( array( $_REQUEST[$this->session_name] ) );
            $res = $stmt->fetch();
            if ( !is_array($res) )
            {
                $functions->redirect( 'index.php?mod=account&action=login', 'metatag|newsite', 3, $lang->t('Your session has expired. Please login again.') );   
            }
        }
    }
}
?>