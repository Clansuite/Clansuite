<?php
/**
* admin Configuration
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
$info['subs'] = array(  'configs' => array( 'configs.module.php', 'module_admin_configs' ),
                        'modules' => array( 'modules.module.php', 'module_admin_modules' ),
                        'users' => array( 'users.module.php', 'module_admin_users' ),
                        'categories' => array( 'categories.module.php', 'module_admin_categories' ),
                        'groups' => array( 'groups.module.php', 'module_admin_groups' ),
                        'permissions' => array( 'perms.module.php', 'module_admin_permissions' ),
                        'menueditor' => array( 'menueditor.module.php', 'module_admin_menueditor' ),
                        'static' => array( 'static.module.php', 'module_admin_static' ),
 );




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
$info['timestamp']      = 1155715034;
$info['name']           = 'admin';
$info['title']          = 'Admin Interface';
$info['description']    = 'This is the Admin Control Panel';
$info['class_name']     = 'module_admin';
$info['file_name']      = 'admin.module.php';
$info['folder_name']    = 'admin';
$info['image_name']     = 'module_admin.jpg';
$info['version']        = (float) 0.1;
$info['cs_version']     = (float) 0.1;
$info['core']           = 1;

/**
* @desc Admin Menus
*/
 
$info['admin_menu'] = 'a:0:{}';

?>
