<?php
   /**
    *   Clansuite - just an E-Sport CMS
    *   Jens-Andre Koch, Florian Wolf © 2005,2006
    *   http://www.clansuite.com/
    *
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
    */

   /**  =====================================================================
    *  WARNING: DO NOT MODIFY THIS FILE, UNLESS YOU KNOW WHAT YOU ARE DOING.
    *           READ THE DOCUMENTATION FOR INSTALLATION PROCEDURE.
    *  =====================================================================
    */

  /**
    * config.class.php
    * Variable Configuration
    *
    * @author     Florian Wolf <xsign.dll@clansuite.com>
    * @author     Jens-Andre Koch <vain@clansuite.com>
    * @copyright  2006 Clansuite Group
    * @license    see COPYING.txt
    * @link       http://gna.org/projects/clansuite
    * @since      File available since Release 0.1
    *
    * @version    SVN: $Id: config.class.php 147 2006-06-11 23:15:37Z vain $
    */

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/*******************************************************************
*   Variable Configuration
*   Use them while scripting in this way:
*	Class is normally initalized: $cfg = new cfg;
*	$cfg->variable_name = 'variable_value';
*******************************************************************/

/**
* @desc Start config class
*/
class config
{
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

        // Meta Tag Information

        $this->meta['description'] = 'Clansuite is a Content Management System for handling the needs of clans';
        $this->meta['language'] = 'de';
        $this->meta['author'] = 'Florian Wolf, Jens-Andre Koch';
        $this->meta['email'] = 'system@clansuite.com';
        $this->meta['keywords'] = 'clan, cms, content management system, portal';

        // Standard Path Configuration

        $this->www_root      = BASE_URL_SEED2;
        $this->root          = BASEDIR;
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
        $this->from_name = 'Clansuite Group';

        //Standard configurations

        $this->tpl_name = 'standard';
        $this->tpl_wrapper_file = 'index.tpl';
        $this->language = 'de';
        $this->std_module = 'index';
        $this->std_module_action = 'show';
        $this->min_pass_length = 6;
        $this->encryption = 'sha1';
        $this->salt = '1-3-5-8-4-1-7-2-4-1-4-1';
        $this->std_page_title = 'clansuite.com';
        $this->std_css = 'standard.css';
        $this->std_javascript = 'standard.js';

        // Login Configuration

        $this->login_method = 'nick';
        $this->remember_me_time = 7776000; // 90 Days
        $this->max_login_attempts = 5;
        $this->login_ban_minutes = 30;

        // Session configuration

        $this->use_cookies = 1;
        $this->use_cookies = 1;
        $this->session_name = 'suiteSID';

        // Error Handling

        $this->suppress_errors = 0;
        $this->debug = 1;
        $this->debug_popup = '';

        // Developers configuration

        $this->version      = (float) 0.1;
        $this->copyright    = '&copy; 2006 by <a href="http://www.clansuite.com">clansuite.com</a>';

    }
}
?>