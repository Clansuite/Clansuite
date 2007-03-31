<?php
/**
* board Configuration
*
* PHP >= version 5.1.4
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
* @link       http://gna.org/projects/clansuite
*
* @author     JAK  FW
* @copyright  2007 JAK  FW
* @license    LGPL
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

// Security Handler
if (!defined('IN_CS')) { die('You are not allowed to view this page.' ); }

//----------------------------------------------------------------
// Subfiles of the module
// -----------------------
// Subfiles are used to extend the usage of a module,
// by shuffling functionality into a new file.
//
// For example:
// If you have a module, that becomes beyond 3000 lines and you want to split that,
// you can create a sub-module and shuffle off some of the functionality into the new file.
// So you've got the option to call the new submodule directly by its URL
//
// http://URL/index.php?mod=mymodule&sub=mysubmodule
//
// or from inside a template by using the {mod} - block
// {mod name="board" func="show" sub="mysubmodule" params="myparams"}
//
// $sub_files = array( 'sub_module_name' => array( 'file_name', 'class_name' ) );
//----------------------------------------------------------------

$info['subs'] = array( 'admin' => array( 'board.admin.php', 'module_board_admin') );




//----------------------------------------------------------------
// Infos
// -----
// These infos are BACKUP Infos! They do not alter the shown
// infos in any way. Just in case somebody installed a module by
// copy and paste into the module folder, they are used as a
// reference.
// If you want to change the real values, so lookup the
// module in the admin interface.
//----------------------------------------------------------------

$info['author']         = 'JAK  FW';
$info['homepage']       = 'http://www.clansuite.com';
$info['license']        = 'LGPL';
$info['copyright']      = '2007 JAK  FW';
$info['timestamp']      = 1175298593;
$info['name']           = 'board';
$info['title']          = 'board';
$info['description']    = 'The Clansuite Board';
$info['class_name']     = 'module_board';
$info['file_name']      = 'board.module.php';
$info['folder_name']    = 'board';
$info['image_name']     = 'module_board.jpg';
$info['module_version']        = (float) 0.1;
$info['clansuite_version']     = (float) 0.1;
$info['core']           = 0;

/**
* Admin Menu Entries
*/

$info['admin_menu'] = '';

?>