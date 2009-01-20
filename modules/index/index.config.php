<?php
/**
* index Configuration
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
* @author     Jens-André Koch <vain@clansuite.com>
* @copyright  2006 Clansuite Group
* @link       http://gna.org/projects/clansuite
*
* @author     Jens-Andé Koch, Florian Wolf
* @copyright  Clansuite Group
* @license    GPL v2
* @version    SVN: $Id$
* @link       http://www.clansuite.com
*/

/**
* @desc Security Handler
*/
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


//----------------------------------------------------------------
// Subfiles of the module
// -----------------------
// Subfiles of the module are used to extend the modules use.
// For example:
// You have a module, that becomes beyond 5000 lines and you want
// to split that. Then you create a sub-module, that can be called
// by the following type of URL:
//
// http://www.myclan.com/index.php?mod=mymodule&sub=mysubmodule
//
// Or inside a template:
// {mod="mymodule" sub="mysubmodule" func="myfunc" params="myparams"}
// 
// $sub_files = array( 'sub_module_name' => array( 'file_name', 'class_name' ) );
//----------------------------------------------------------------
$info['subs'] = 's:0:"";';




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

$info['author']         = 'Jens-André Koch, Florian Wolf';
$info['homepage']       = 'http://www.clansuite.com';
$info['license']        = 'GPL v2';
$info['copyright']      = 'Clansuite Group';
$info['timestamp']      = 1155715255;
$info['name']           = 'index';
$info['title']          = 'Index Module';
$info['description']    = 'This is the main site.';
$info['class_name']     = 'module_index';
$info['file_name']      = 'index.module.php';
$info['folder_name']    = 'index';
$info['image_name']     = 'module_index.jpg';
$info['module_version']        = (float) 0.1;
$info['clansuite_version']     = (float) 0.1;
$info['core']           = 1;

/**
* @desc Admin Menus
*/
 
$info['admin_menu'] = 'a:0:{}';

?>
