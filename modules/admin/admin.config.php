<?php
/**
* Admin Module Configuration
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
* @license    ???
* @version    SVN: $Id$
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
* @desc Subfiles of the module
* @desc $sub_files = array( 'sub_module_name' => array( 'file_name', 'class_name' ) );
*/

$sub_files = array( 'configs'    => array('configs.module.php'    , 'module_admin_configs'      ),
                    'modules'    => array('modules.module.php'    , 'module_admin_modules'      ),
                    'users'      => array('users.module.php'      , 'module_admin_users'        ),
                    'usercenter' => array('usercenter.module.php' , 'module_admin_usercenter'   ),
                    'groups'     => array('groups.module.php'     , 'module_admin_groups'       ),
                    'permissions'=> array('perms.module.php'      , 'module_admin_permissions'  ),
                    'menueditor' => array('menueditor.module.php' , 'module_admin_menueditor'   ), 
                    'static'     => array('static.module.php'     , 'module_admin_static'       ), );
