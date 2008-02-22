<?php
   /**
    * Clansuite - just an eSports CMS
    * Jens-Andre Koch © 2005-2008
    * http://www.clansuite.com/
    *
    * File:         config.class.php
    * Requires:     PHP5+
    *
    * Purpose:      Variable Configuration and Settings Class
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
    * @copyright  Jens-Andre Koch (2005-$Date$), Florian Wolf (2006-2007)
    *
    * @link       http://www.clansuite.com
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id$
    */

   /**  =====================================================================
    *  WARNING: DO NOT MODIFY THIS FILE, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *           READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

/**
 * Security Handler
 */
if (!defined('IN_CS')){ die('Clansuite Framework not loaded. Direct Access forbidden.' );}

/**
 * This is the Config class of Clansuite. And it's build around the $config array,
 * which is a storage container for settings.
 *
 * We use some php magic in here:
 * The array access implementation makes it seem that $registry is an array,
 * even though it's an object! Why we do that? Because less to type!
 * The __set, __get, __isset, __unset are overloading functions to work with that array.
 *
 * Usage :
 * get data : $cfg->['name'] = 'john';
 * get data, using get() : echo $cfg->get ('name');
 * get data, using array access: echo $cfg['name'];
 *
 * @author Jens-Andre Koch
 * @copyright Jens-Andre Koch (2005-$Date$)
 *
 * @package clansuite
 * @subpackage config
 * @todo COMMENT by vain: maybe change this class to a ini or yaml file? but that would add overhead when loading!
 * @todo  by vain: add set/get via database if not found in mainarray! save changes on destruct?
 **/
class configuration implements ArrayAccess
{
     /**
     * Configuration Array
     * protected-> only visible to childs
     *
     * @var array
     * @access protected
     */
    protected $config = array();

    /**
     * CONSTRUCTOR
     * sets up all variables
     *
     * @todo by vain: automatic read of ini-files / or from db -> to set up cf arrray
     */
    public function __construct()
    {
        // Database related configurations

        $this->config['db_type']      = 'mysql';
        $this->config['db_username']  = 'clansuite';
        $this->config['db_password']  = 'toop';
        $this->config['db_name']      = 'clansuite';
        $this->config['db_host']      = 'localhost';
        $this->config['db_prefix']    = 'cs_';

        // Standard Path Configuration

        $this->config['core_folder']        = 'core';
        $this->config['libraries_folder']   = 'libraries';
        $this->config['language_folder']    = 'languages';
        $this->config['themes_folder']      = 'themes';
        $this->config['mod_folder']         = 'modules';
        $this->config['upload_folder']      = 'uploads';

        // SwiftMail configuration

        // methods: smtp, sendmail, exim, mail
        $this->config['mailmethod'] = 'mail';
        $this->config['mailerhost'] = 'localhost';
        // if no port is given: ports 25 & 465 are used
        $this->config['mailerport'] = 21;
        $this->config['smtp_username'] = 'clansuite';
        $this->config['smtp_password'] = 'toop';
        // encryption types: SWIFT_OPEN (no) / SWIFT_SSL (SSL) / SWIFT_TLS (TLS/SSL)
        $this->config['mailencryption'] = 'SWIFT_OPEN';
        $this->config['from'] = 'system@clansuite.com';
        $this->config['from_name'] = 'ClanSuite Group';

        // Global Template Configurations
        $this->config['theme'] = 'standard';
        #$this->config['theme'] = 'accessible';
        $this->config['tpl_wrapper_file'] = 'index.tpl';

    	// Activate Prefilterplugin for Themeswitching via GET Parameter ?theme=
    	$this->config['themeswitch_via_url'] = 1;

    	// Activate Prefilterplugin for Languageswitching via GET Parameter ?lang=
        $this->config['languageswitch_via_url'] = 1;

    	// Controller Resolver : Default Module and Default Action
    	$this->config['default_module'] = 'index';
        $this->config['default_action'] = 'show';

        // Default Page Title + CSS + Javascript

        $this->config['std_page_title'] = 'clansuite.com';
        $this->config['std_css'] = 'standard.css';
        $this->config['std_javascript'] = 'standard.js';

        // Default Language / Locale Setting

        $this->config['language'] = 'de';
        $this->config['outputcharset'] = 'UTF-8';

        // Time Zone
        // more timezones in Appendix H of PHP Manual -> http://us2.php.net/manual/en/timezones.php
        $this->config['timezone'] = 'Europe/Berlin';

        // Meta Tag Informations

        $this->config['meta']['description'] = 'Clansuite - just an e-sport content management system.';
        $this->config['meta']['language'] = 'de';
        $this->config['meta']['author'] = 'Jens-Andre Koch &amp; Clansuite Development Team';
        $this->config['meta']['email'] = 'system@clansuite.com';
        $this->config['meta']['keywords'] = 'Clansuite, open-source, eSport, cms, clan,content management system, portal, online gaming';

        // Login Configuration & Password Encryption

        $this->config['login_method'] = 'nick'; # email or nick
        $this->config['remember_me_time'] = 90; # days
        $this->config['session_expire_time'] = 30; # minutes
        $this->config['max_login_attempts'] = 5;
        $this->config['login_ban_minutes'] = 30; # minutes

        $this->config['min_pass_length'] = 6;
        $this->config['encryption'] = 'sha1';
        $this->config['salt'] = '1-3-5-8-4-1-7-2-4-1-4-1';

        // OpenID
        $this->config['openid_trustroot'] = 'http://www.clansuite.com/openid/';
        $this->config['openid_showcommentsbox'] = 1;
        $this->config['openid_showloginbox'] = 1;

        // File/Upload configuration
        $this->config['max_upload_filesize'] = 1048576;

        // Session configuration

        $this->config['use_cookies'] = 1;
        $this->config['use_cookies_only'] = 0;

        // Error Handling

        $this->config['suppress_errors'] = 0;
        $this->config['debug'] = 1;
        $this->config['debug_popup'] = 0;

        // Developers configuration

        $this->config['help_edit_mode'] = 0;
        include 'core/clansuite.version.php';
        $this->config['clansuite_version']  = $clansuite_version;
        $this->config['clansuite_version_state']  = $clansuite_version_state;
        $this->config['clansuite_version_name']  = $clansuite_version_name;

        // Cache

        $this->config['caching'] = 0;
        $this->config['cache_lifetime'] = '90';

        // Maintenance Mode

        $this->config['maintenance'] = 0;
        $this->config['maintenance_reason'] = 'SITE is currently undergoing scheduled maintenance.
                                     <br />Please try back in 60 minutes. Sorry for the inconvenience.';

    }

    /**
     * Gets a config file item based on keyname
     *
     * @access    public
     * @param    string    the config item key
     * @return    void
     */
    public function __get($configkey)
    {
        return isset($this->config[$configkey]) ? $this->config[$configkey] : null;
    }

    /**
     * Set a config file item based on key:value
     *
     * @access    public
     * @param    string    the config item key
     * @param    string    the config item value
     * @return    void
     *
     */
    public function __set($configkey, $configvalue)
    {
        #if (isset($this->config[$configkey]) == true) {
        #       throw new Exception('Variable ' . $configkey . ' already set.');
        #}

        $this->config[$configkey] = $configvalue;
        return true;
    }

    // method that will allow 'isset' to work on these variables
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    // method to allow 'unset' calls to work on these variables
    public function __unset($key)
    {
        unset($this->config[$key]);
        #echo 'Variable was unset:'. $key;
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    // hmm? why should configuration be unset?
    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
        return true;
    }
}
?>