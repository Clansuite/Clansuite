<?php
   /**
    * Clansuite - just an E-Sport CMS
    * Jens-Andre Koch, Florian Wolf © 2005-2007
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
    * @copyright  Jens-Andre Koch (2005-$LastChangedDate$), Florian Wolf (2006-2007)
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
 * Defines the Security Handler
 */
if (!defined('IN_CS')) { die('You are not allowed to view this page.'); }

/**
 * This is the Config class of Clansuite. It contains all settings.
 *
 * Variable Configuration
 * Use them while scripting in this way:
 * Class is normally initalized: $cfg = new cfg;
 * $cfg->variable_name = 'variable_value';
 *
 * @package clansuite
 * @subpackage config
 * @todo maybe change this class to a ini file
 */
class config
{

    /**
     * CONSTRUCTOR
     * sets up all variables
     */
    function __construct()
    {

    	// Database related configurations

        $this->db_type      = 'mysql';
        $this->db_username  = 'clansuite';
        $this->db_password  = 'toop';
        $this->db_name      = 'clansuite';
        $this->db_host      = 'localhost';
        $this->db_prefix    = 'cs_';
        $this->db_abs_layer = 'pdo';

        // Standard Path Configuration

        $this->core_folder   = 'core';
        $this->lang_folder   = 'languages';
        $this->tpl_folder    = 'templates';
        $this->mod_folder    = 'modules';
        $this->upload_folder = 'uploads';

        // SwiftMail configuration

        // methods: smtp, sendmail, exim, mail
        $this->mailmethod = 'mail';
        $this->mailerhost = 'localhost';
        // if no port is given: ports 25 & 465 are used
        $this->mailerport = 21;
        $this->smtp_username = 'clansuite';
        $this->smtp_password = 'toop';
        // encryption types: SWIFT_OPEN (no) / SWIFT_SSL (SSL) / SWIFT_TLS (TLS/SSL)
        $this->mailencryption = 'SWIFT_OPEN';
        $this->from = 'system@clansuite.com';
        $this->from_name = 'ClanSuite Group';

        // Template Configurations

        $this->theme = 'accessible';
        $this->tpl_wrapper_file = 'index.tpl';
        
        // Modules: Default Module and Default Action
        
        $this->std_module = 'index';
        $this->std_module_action = 'show';
        
        // Default Page Title + CSS + Javascript 
        
        $this->std_page_title = 'clansuite.com';
        $this->std_css = 'standard.css';
        $this->std_javascript = 'standard.js';

        // Default Language / Locale Setting
        
        $this->language = 'de';
        
        // Meta Tag Informations

        $this->meta['description'] = 'Clansuite - just an e-sport content management system.';
        $this->meta['language'] = $this->language;
        $this->meta['author']  = 'Jens-Andre Koch, Florian Wolf';
        $this->meta['email'] = 'system@clansuite.com';
        $this->meta['keywords'] = 'cms, content management system, portal, e-sport';

        // Login Configuration & Password Encryption

        $this->login_method = 'nick'; # email or nick
        $this->remember_me_time = 90; # days
        $this->session_expire_time = 30; # minutes
        $this->max_login_attempts = 5;
        $this->login_ban_minutes = 30; # minutes

        $this->min_pass_length = 6;
        $this->encryption = 'sha1';
        $this->salt = '1-3-5-8-4-1-7-2-4-1-4-1';

        // Session configuration

        $this->use_cookies = 1;
        $this->use_cookies_only = 0;
        $this->session_name = 'suiteSID';

        // Error Handling

        $this->suppress_errors = 0;
        $this->debug = 1;
        $this->debug_popup = 0;

        // Developers configuration

        $this->help_edit_mode = 1;
        $this->version  = (float) 0.1;

        // Cache

        $this->caching = 0;
        $this->cache_lifetime = '-1';

        // Maintenance Mode

        $this->maintenance = 0;
        $this->maintenance_reason = 'SITE is currently undergoing scheduled maintenance.
                                     <br />Please try back in 60 minutes. Sorry for the inconvenience.';
    }
}
?>