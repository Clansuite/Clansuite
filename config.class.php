<?php
/**
* Set config variables
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
* @author     Jens-André Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @license    see COPYING.txt
* @version    SVN: $Id: config.class.php 147 2006-06-11 23:15:37Z vain $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

/**
* @desc Start config class
*/
class config
{
    function __construct()
    {

        /**
        * @desc Database related configurations
        */

        $this->db_type      = 'mysql';
        $this->db_username  = 'clansuite';
        $this->db_password  = 'toop';
        $this->db_name      = 'clansuite';
        $this->db_host      = 'localhost';
        $this->db_prefix    = 'cs_';
        $this->db_abs_layer = 'pdo';

        /**
        * @desc Standard Path configurations
        */

        $this->www_root      = BASE_URL_SEED2;
        $this->root          = BASEDIR;
        $this->core_folder   = 'core';
        $this->lang_folder   = 'languages';
        $this->tpl_folder    = 'templates';
        $this->mod_folder    = 'modules';
        $this->upload_folder = 'uploads';

        /**
        * @desc Meta Tag Informations
        */

        $this->meta['description']  = 'Clansuite is a Content Management System for handling the needs of clans';
        $this->meta['language']     = 'de';
        $this->meta['author']       = 'Florian Wolf, Jens-André Koch';
        $this->meta['email']        = 'system@clansuite.com';
        $this->meta['keywords']     = 'clan, cms, content management system, portal';

        /**
        * @desc Mail configuration
        */

        // methods: smtp, sendmail, exim, 
        $this->mailmethod       = 'mail';
        $this->mailerhost       = $_SERVER['SERVER_NAME'];
        // if no port is given: ports 25 & 465 are used
        $this->mailerport       = '';        
        $this->smtp_username    = 'clansuite';
        $this->smtp_password    = 'toop';
        // encryption types: SWIFT_OPEN (no) / SWIFT_SSL (SSL) / SWIFT_TLS (TLS/SSL)
        $this->mailencryption   = 'SWIFT_OPEN';
        $this->from             = 'system@clansuite.com';
        $this->from_name        = 'Clansuite Mailer';

        /**
        * @desc Standard configurations
        */

        $this->tpl_name             = 'standard';
        $this->tpl_wrapper_file     = 'index.tpl';
        $this->language             = 'de';
        $this->std_module           = 'index';
        $this->std_module_action    = 'show';
        $this->min_pass_length      = '6';
        $this->encryption           = 'sha1';
        $this->salt                 = '1-3-5-8-4-1-7-2-4-1-4-1';
        $this->std_page_title       = 'clansuite.com';
        $this->std_css              = 'standard.css';
        $this->std_javascript       = 'standard.js';
        
        /**
        * @desc User configurations
        */

        $this->login_method         = 'nick';
        $this->remember_me_time     = 7776000; // 90 Days
        $this->max_login_attempts   = 5;
        $this->login_ban_minutes    = 30;
        
        /**
        * @desc Session configurations
        */

        $this->use_cookies      = 1;
        $this->use_cookies_only = 0;
        $this->session_name     = 'suiteSID';

        /**
        * @desc Error Handling
        */

        $this->suppress_errors  = 0;
        $this->debug            = 1;
        $this->debug_popup      = 0;

        /**
        * @desc Developers configurations
        */

        $this->version      = (float) 0.1;
        $this->copyright    = '&copy; 2006 by <a href="http://www.clansuite.com">clansuite.com</a>';
        
    }
}
?>