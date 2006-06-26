<?php
/**
* ACP - INDEX
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
* @version    SVN: $Id: $
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

//--------------------------------------------------------
// Load public config
// Create Object $cfg 
//--------------------------------------------------------
require ('admin.config.php');

//--------------------------------------------------------
// SETUP EVERYTHING
//--------------------------------------------------------
include '../core/prepend.php';

$tpl->template_dir = array( ROOT . '/admin/templates/admin_standard/', 
			    ROOT . '/templates/core/' ) ;
$tpl->debug_tpl		  = ROOT . '/templates/core/debug.tpl';


// logic
// if user-right^group whatever !== admin
// { beam user ! redirect }
// else { 

// include menu with tpl assign menu
include 'menu/menu.php';

// output all
$tpl->display($cfg->tpl_wrapper_file);

//----------------------------------------------------------------
// Show Debug Console
//----------------------------------------------------------------
DEBUG ? $debug->show_console() : '';

//}
?>