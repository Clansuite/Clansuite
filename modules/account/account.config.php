<?php
/**
* account Configuration
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
* @author     Jens-André Koch, Florian Wolf
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
// {mod name="account" func="show" sub="mysubmodule" params="myparams"}
// 
// $sub_files = array( 'sub_module_name' => array( 'file_name', 'class_name' ) );
//----------------------------------------------------------------
$info['subs'] = array();




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
$info['timestamp']      = 1159206193;
$info['name']           = 'account';
$info['title']          = 'Account Administration';
$info['description']    = 'This module handles all necessary account stuff - like login/logout etc.';
$info['class_name']     = 'module_account';
$info['file_name']      = 'account.module.php';
$info['folder_name']    = 'account';
$info['image_name']     = 'module_account.jpg';
$info['version']        = (float) 0.1;
$info['cs_version']     = (float) 0.1;
$info['core']           = 1;

/**
* @desc Admin Menus
*/
 
$info['admin_menu'] = 'a:3:{i:2;a:9:{s:2:"id";s:1:"2";s:6:"parent";s:1:"0";s:4:"type";s:6:"folder";s:4:"text";s:7:"Modules";s:4:"href";s:0:"";s:5:"title";s:7:"Modules";s:6:"target";s:5:"_self";s:5:"order";s:1:"1";s:4:"icon";s:0:"";}i:3;a:9:{s:2:"id";s:1:"3";s:6:"parent";s:1:"2";s:4:"type";s:6:"folder";s:4:"text";s:4:"News";s:4:"href";s:0:"";s:5:"title";s:4:"News";s:6:"target";s:5:"_self";s:5:"order";s:1:"0";s:4:"icon";s:13:"page_edit.png";}i:4;a:9:{s:2:"id";s:1:"4";s:6:"parent";s:1:"2";s:4:"type";s:6:"folder";s:4:"text";s:8:"Articles";s:4:"href";s:0:"";s:5:"title";s:8:"Articles";s:6:"target";s:5:"_self";s:5:"order";s:1:"1";s:4:"icon";s:10:"report.png";}}';

?>