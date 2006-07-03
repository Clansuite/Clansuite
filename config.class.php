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

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}

//----------------------------------------------------------------
// Start config class
//----------------------------------------------------------------
class config
{
    function __construct()
    {

        //----------------------------------------------------------------
        // Database related configurations
        //----------------------------------------------------------------
        $this->db_type      = 'mysql';
        $this->db_username  = 'clansuite';
        $this->db_password  = 'toop';
        $this->db_name      = 'clansuite';
        $this->db_host      = 'localhost';
        $this->db_prefix    = 'cs_';
        $this->db_abs_layer = 'pdo';

        //----------------------------------------------------------------
        // Standard Path configurations
        //----------------------------------------------------------------
        $this->www_root     = BASE_URL_SEED2;
        $this->root         = BASEDIR;
        $this->core_folder  = 'core';
        $this->lang_folder  = 'languages';
        $this->tpl_folder   = 'templates';
        $this->mod_folder   = 'modules';

        //----------------------------------------------------------------
        // Mail configuration
        //----------------------------------------------------------------
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

        //----------------------------------------------------------------
        // Standard configurations
        //----------------------------------------------------------------
        $this->tpl_name             = 'andreas01';
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
        
        //----------------------------------------------------------------
        // User configurations
        //----------------------------------------------------------------
        $this->login_method     = 'nick';
        $this->remember_me_time = 7776000; // 90 Days
        
        //----------------------------------------------------------------
        // Session configurations
        //----------------------------------------------------------------
        $this->use_cookies      = 1;
        $this->use_cookies_only = 0;
        $this->session_name     = 'suiteSID';

        //----------------------------------------------------------------
        // Error Handling
        //----------------------------------------------------------------
        $this->suppress_errors  = 0;
        $this->debug            = 1;
        $this->debug_popup      = 0;

        //----------------------------------------------------------------
        // Init modules (white-list)
        // $this->prepare_modules = array( 'module_name' => array( 'file_name.php', 'folder_name in /modules/', 'class_name' ) );
        // Later: $this->modules (See end of file)
        //----------------------------------------------------------------
        $prepare_modules = array(   'index'     => array('index.class.php'    , 'index'    , 'module_index'     ),
                                    'account'   => array('account.class.php'  , 'account'  , 'module_account'   ),
                                    'admin'     => array('admin.class.php'    , 'admin'    , 'module_admin'     ), );





        //----------------------------------------------------------------
        //
        //
        //    DO NOT EDIT BELOW IF YOU DO NOT KNOW WHAT YOU ARE DOING
        //
        //
        //----------------------------------------------------------------




        //----------------------------------------------------------------
        // Developers configurations
        //----------------------------------------------------------------
        $this->version      = (float) 0.1;
        $this->copyright    = 'clansuite.com | (c) 2006 under GPL v2 License (see COPYING.txt)';
        
        //----------------------------------------------------------------
        // Create a nice and proper $this->modules white-list
        //----------------------------------------------------------------
        foreach ($prepare_modules as $mod => $values )
        {
            $this->modules[$mod]['file_name']     = $values[0];
            $this->modules[$mod]['folder_name']   = $values[1];
            $this->modules[$mod]['class_name']    = $values[2];
        }
    }
}
?>