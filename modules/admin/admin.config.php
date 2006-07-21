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

//----------------------------------------------------------------
// Security Handler
//----------------------------------------------------------------
if (!defined('IN_CS'))
{
    die('You are not allowed to view this page statically.' );
}


//----------------------------------------------------------------
// Subfiles of the module
// $sub_files = array( 'sub_module_name' => array( 'file_name', 'class_name' ) );
//----------------------------------------------------------------
$sub_files = array( 'admin_configs'    => array('admin_configs.class.php'    , 'module_admin_configs' ),
                    'admin_modules'    => array('admin_modules.class.php'    , 'module_admin_modules' ),
                    'admin_users'      => array('admin_users.class.php'      , 'module_admin_users'   ),
                    'admin_menueditor' => array('admin_menueditor.class.php' , 'module_admin_menueditor') );