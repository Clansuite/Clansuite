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


/*
//----------------------------------------------------------------
// Table structure for cs_session
//----------------------------------------------------------------

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


//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Class session start
//----------------------------------------------------------------
class session
{
    //----------------------------------------------------------------
    // Init class session | set common session vars
    //----------------------------------------------------------------
    public $session_name            = 'suiteSID';
    public $session_expire_time     = 30; // minutes
    public $session_probability     = 30; // precenatge
    public $session_cookies         = 1;
    public $session_cookies_only    = 0;
    public $session_security        = array('check_ip'      => true,
                                            'check_browser' => true);
    
    //----------------------------------------------------------------
    // Overwrite php.ini settings
    // Start the session
    //----------------------------------------------------------------
    function create_session()
    {
        global $cfg, $lang, $error, $functions, $input;
        
        //----------------------------------------------------------------
        // Set the ini Vars and look for configs
        //----------------------------------------------------------------
        $this->session_name                 = $cfg->session_name;
        $this->session_cookies              = $cfg->use_cookies;
        $this->session_cookies_only         = $cfg->use_cookies_only;
        ini_set('session.save_handler'      , 'user' );
        ini_set('session.gc_maxlifetime'    , $this->session_expire_time );
        ini_set('session.gc_probability'    , $this->session_probability );
        ini_set('session.name'              , $this->session_name );
        ini_set('session.use_trans_sid'     , 1 );
        ini_set('url_rewriter.tags'         , "a=href,area=href,frame=src,form=,fieldset=,meta=content=\"5; URL");
        ini_set('session.use_cookies'       , $cfg->use_cookies );
        ini_set('session.use_only_cookies'  , $cfg->use_cookies_only );
        
        //----------------------------------------------------------------
        // Set the handlers
        //----------------------------------------------------------------
        session_set_save_handler(   array(&$this, "_session_open"   ),
                                    array(&$this, "_session_close"  ),
                                    array(&$this, "_session_read"   ),
                                    array(&$this, "_session_write"  ),
                                    array(&$this, "_session_destroy"),
                                    array(&$this, "_session_gc"     ));
        
        
        //----------------------------------------------------------------
        // Start Session with Error on failure
        //----------------------------------------------------------------
        if (!session_start())
        {
            $error->show($lang->t('Session Error' ), $lang->t('The session start failed!' ), 3 );
        }
        
        //----------------------------------------------------------------
        // Create new ID if session is not in DB or corrupted
        //----------------------------------------------------------------
        if ($this->_session_read(session_id() ) === false OR strlen(session_id() ) != 32)
        {
            session_regenerate_id();
        }
        
        //----------------------------------------------------------------
        // Security Check
        //----------------------------------------------------------------
        if (!$this->_session_check_security() )
        {
            die($functions->redirect('index.php?mod=login') );
        }
    }
    
    //----------------------------------------------------------------
    // Open a session
    //----------------------------------------------------------------
    function _session_open()
    {
        return true;
    }
    
    //----------------------------------------------------------------
    // Close a session
    //----------------------------------------------------------------
    function _session_close()
    {
        session::_session_gc(0);
        return true;
    }
    
    //----------------------------------------------------------------
    // Read a session
    //----------------------------------------------------------------
    function _session_read($id )
    {
        global $db;
        
        $stmt = $db->prepare('SELECT session_data FROM ' . DB_PREFIX .'session WHERE session_name = ? AND session_id = ?' );
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
    
    //----------------------------------------------------------------
    // Write a session
    //----------------------------------------------------------------
    function _session_write($id, $data )
    {
        global $db;
        
        //----------------------------------------------------------------
        // Time Settings
        //----------------------------------------------------------------
        $seconds = $this->session_expire_time * 60;
        $expires = time() + $seconds;
        
        //----------------------------------------------------------------
        // Check if session is in DB
        //----------------------------------------------------------------
        $stmt = $db->prepare('SELECT session_id FROM ' . DB_PREFIX . 'session WHERE session_id = ?' );
        $stmt->execute(array($id ) );
        
        if ($stmt->fetchAll() )
        {
            //----------------------------------------------------------------
            // Update Session in DB
            //----------------------------------------------------------------
            $stmt = $db->prepare('UPDATE ' . DB_PREFIX . 'session SET session_expire = ? , session_data = ? WHERE session_id = ?' );
            $stmt->execute(array($expires, $data, $id ) );
            $this->session_control();
            
        }
        else
        {
            //----------------------------------------------------------------
            // Create Session @ DB & Cookies OR $_GET
            //----------------------------------------------------------------
            $stmt = $db->prepare('INSERT INTO ' . DB_PREFIX . 'session (session_id, session_name, session_expire, session_data, session_visibility, user_id) VALUES(?,?,?,?,?,?)' );
            $stmt->execute(array($id, $this->session_name, $expires, $data, 1, 0 ) );
        }
        return true;
    }
    
    //----------------------------------------------------------------
    // Destroy a session
    //----------------------------------------------------------------
    function _session_destroy($id )
    {
        global $db;
        
        //----------------------------------------------------------------
        // Unset Session
        //----------------------------------------------------------------
        unset($_SESSION);
        
        //----------------------------------------------------------------
        // Unset Cookie Vars
        //----------------------------------------------------------------
        if (isset($_COOKIE[$this->session_name]))
        {
            unset($_COOKIE[$this->session_name]);
            setcookie($this->session_name, false );
        }
        
        
        //----------------------------------------------------------------
        // Optimize tables
        //----------------------------------------------------------------
        $stmt = $db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_id = ?' );
        $stmt->execute(array($this->session_name, $id ) );
        
        if ($stmt->rowCount() > 0)
        {
            $this->_session_optimize();
        }
        
    }
    
    //----------------------------------------------------------------
    // Session garbage collector
    //----------------------------------------------------------------
    function _session_gc($max_lifetime )
    {
        global $db;
        
        //----------------------------------------------------------------
        // Prune
        //----------------------------------------------------------------
        $stmt = $db->prepare('DELETE FROM ' . DB_PREFIX . 'session WHERE session_name = ? AND session_expire < ?' );
        $stmt->execute(array($this->session_name, time() ) );
        
        if ($stmt->rowCount() > 0)
        {
            $this->_session_optimize();
        }
    }
    
    //----------------------------------------------------------------
    // Optimize the session table
    //----------------------------------------------------------------
    function _session_optimize()
    {
        global $db;
        
        $db->simple_query('OPTIMIZE TABLE ' . DB_PREFIX . 'session');
    }
    
    //----------------------------------------------------------------
    // Check for a secure session
    //----------------------------------------------------------------
    function _session_check_security()
    {
        //----------------------------------------------------------------
        // Check for IP
        //----------------------------------------------------------------
        if (in_array("check_ip", $this->session_security))
        {
            if ($_SESSION['client_ip'] === null)
            {
                $_SESSION['client_ip'] = $_SERVER['REMOTE_ADDR'];
            }
            else
            if ($_SERVER['REMOTE_ADDR'] != $_SESSION['client_ip'])
            {
                session::_session_destroy();
                return false;
            }
        }
        
        //----------------------------------------------------------------
        // Check for Browser
        //----------------------------------------------------------------
        if(in_array("check_browser", $this->session_security))
        {
            if($_SESSION['client_browser'] === null)
            {
                $_SESSION['client_browser'] = $_SERVER["HTTP_USER_AGENT"];
            }
            else if($_SERVER["HTTP_USER_AGENT"] != $_SESSION['client_browser'])
            {
                session::_session_destroy();
                return false;
            }
        }

        //----------------------------------------------------------------
        // Return true if everything is ok
        //----------------------------------------------------------------
        return true;
    }

    //----------------------------------------------------------------
    // Session control
    // - delete old users
    // - prune timeouts 
    //----------------------------------------------------------------
    function session_control()
    {
        global $db, $functions;
        // 2 Tage alte registrierte, aber nicht aktivierte User löschen!
        /*
        $table = "DELETE FROM " . DB_PREFIX . "users ";
        $where = "disabled = 1 AND (joined + INTERVAL 2 DAY)<Now() AND (timestamp + INTERVAL 2 DAY)<Now()";
        $db->exec($table.$where);


        // Zeitstempel in users-table setzen
        $table 	= 'UPDATE ' . DB_PREFIX . 'users ';
        $set	= "SET timestamp = NOW() WHERE user_id = '$_SESSION[User][user_id]'";
        $db->exec($table.$set);
        */
        if(isset($_SESSION['authed']))
        {
            if(!isset($_SESSION['lastmove']) || (time() - $_SESSION['lastmove'] > 350))
            {
                $_SESSION['lastmove'] = time();
            }
            else
            {
                die ( $functions->redirect('index.php?mod=login') );
            }
        }
    }
}