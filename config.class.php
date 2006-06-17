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
	die( 'You are not allowed to view this page statically.' );	
}


class config
{
	function __construct()
	{

		/**
		* @desc Database related configurations
		* --------------------------------------
		* @desc $this->db_type		The servers DB type, e.g. mysql,mssql,sqlite
		* @desc $this->db_username 	Username to access the database
		* @desc $this->db_password 	Password that goes with the username to access the database
		* @desc $this->db_name 		The name of the Database
		* @desc $this->db_host 		IP or Domain - usually just 'localhost'
		* @desc $this->db_prefix 	The prefix WITHOUT '_' ! The underline is added automatically e.g. cs_settings
		* @desc $this->db_abs_layer	The abstraction layer clansuite should use. PDO (PHP 5.1) or native PHP functions e.g. 'mysql_query'
		*/
		$this->db_type 		= 'mysql';
		$this->db_username	= 'clansuite';
		$this->db_password	= 'toop';
		$this->db_name		= 'clansuite';
		$this->db_host		= 'localhost';
		$this->db_prefix	= 'cs_';
		$this->db_abs_layer	= 'pdo';


		/**
		* @desc Standard Path configurations
		* ----------------------------------
		* @desc $this->www_root 		A proper domainname WITHOUT trailing slash '/'
		* @desc $this->root 			Absolute path to the index.php WITHOUT trailing Slash '/', usually /home/domain.com/public_html (Unix) or D:/Homepage/Domain.com (Windows)
		* @desc $this->core_root 		Absolute path to the core functions WITHOUT trailing Slash '/'
		* @desc $this->lang_root 		Absolute path to the languages WITHOUT trailing Slash '/'
		* @desc $this->tpl_root 		Absolute path to the templates WITHOUT trailing Slash '/'
		* @desc $this->mod_root 		Absolute path to the modules WITHOUT trailing Slash '/'
		*/
		$this->www_root		= BASE_URL_SEED2;
		$this->root			= BASEDIR;
		$this->core_folder	= 'core';
		$this->lang_folder	= 'languages';
		$this->tpl_folder	= 'templates';
		$this->mod_folder	= 'modules';

		/**
		* Mail configuration
		* -------------------
		* todo
		*/
		$this->mailmethod 		= "mail";
		$this->host 			= "clansuite.localhost.de";
		$this->smtp_username 	= "clansuite";
		$this->smtp_password 	= "toop";
		$this->path_to_sendmail = "/usr/sbin/sendmail";
		$this->from				= "system@clansuite.com";
		$this->from_name		= "Clansuite Mailer";
				
		/**
		* @desc Standard configurations
		* -----------------------------
		* @desc $this->tpl_name				Set the template name
		* @desc $this->tpl_wrapper_file     Set the Wrapper file - usually just 'index.tpl'
		* @desc $this->lanugage				Set the acronym for the language e.g. 'de','en'
		* @desc $this->std_module			Set the first module to load when a user enters the page
		* @desc $this->user_cookies			Should the system use cookies or only $_GET Sessions (index.php?sid=b34534kljsdagd)
		*/
		$this->tpl_name 			= 'andreas01';
		$this->tpl_wrapper_file 	= 'index.tpl';
		$this->language 			= 'de';
		$this->std_module			= 'index';
		$this->std_module_action	= 'index_show';
		$this->min_pass_length		= '6';
		$this->encryption			= 'sha1';
		$this->salt					= '1-3-5-8-4-1';
		$this->std_page_title		= 'clansuite.com';
		$this->std_css				= 'standard.css';
		$this->std_javascript		= 'standard.js';
		$this->use_cookies			= 0;
		
		/**
		* @desc Init modules (white-list)
		* @desc $this->prepare_modules = array( 'module_name' => array( 'file_name.php', 'folder_name in /modules/', 'class_name' )
		* @desc Later: $this->modules (See end of file)
		*/
		$prepare_modules = array( 	'index' => array( 'index.class.php', 'index', 'module_index' ),
									'admin' => array( 'admin.class.php', 'admin', 'module_admin' ),	);
		

		
		
		/**
		*       NO EDIT NECESSARY BELOW, ONLY FOR DEVELOPERS
		*       NO EDIT NECESSARY BELOW, ONLY FOR DEVELOPERS
		* 		NO EDIT NECESSARY BELOW, ONLY FOR DEVELOPERS
		*       NO EDIT NECESSARY BELOW, ONLY FOR DEVELOPERS
		*/
		
		
		
		/**
		* @desc Developers configurations
		* @desc $this->debug		0: Hide all background information
		*							1: Give extra information such as SQL Queries
		* @desc $this->debug_popup  0: Show debugger in the website
		* 							1: Show debugger as a popup
		*/
		$this->debug		= 1;
		$this->debug_popup	= 0;
		$this->version		= (float) 0.1;
		$this->copyright	= 'clansuite.com | (c) 2006 under ??? License';

		/**
		* @desc Create a nice and proper $this->modules white-list
		*/
		foreach ( $prepare_modules as $key => $value )
		{
			$this->modules[$key]['file_name'] 	= $value[0];
			$this->modules[$key]['folder_name'] = $value[1];
			$this->modules[$key]['class_name'] 	= $value[2];
		}
	}
}
?>