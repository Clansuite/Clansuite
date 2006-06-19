<?php
/**
* Debug Handler Class
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
* @version    SVN: $Id: debug.class.php 144 2006-06-11 22:59:35Z vain $
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

//----------------------------------------------------------------
// Start debug class
//----------------------------------------------------------------	
class debug
{	
	//----------------------------------------------------------------
	// Print debug console
	//----------------------------------------------------------------
	function show_console()
	{
		global $db, $tpl, $cfg, $error, $lang, $modules;

		$tpl->assign('request'		, $_REQUEST );
		$tpl->assign('session'		, $_SESSION );
		$tpl->assign('cookies'		, $_COOKIE );
		$tpl->assign('queries'		, $db->queries );
		$tpl->assign('prepares'		, $db->prepare_statements );
		$tpl->assign('config'		, $cfg );
		$tpl->assign('error_log'	, $error->error_log );
		$tpl->assign('lang_loaded'	, $lang->loaded );
		$tpl->assign('debug_popup'	, $cfg->debug_popup );
		$tpl->assign('mods_loaded'	, $modules->loaded );
		$tpl->display( 'debug.tpl' );
	}
}
?>