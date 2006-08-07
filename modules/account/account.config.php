<?php
/**
* Account Module Configuration
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

$sub_files = array();

/**
* @desc Infos
*/
 

$info['author']         = 'Florian Wolf, Jens-Andr� Koch';
$info['timestamp']      = 1234567;
$info['name']           = 'account';
$info['title']          = 'Account Administration';
$info['description']    = 'This module handles all necessary account stuff - like login/logout etc.';
$info['class_name']     = 'module_account';
$info['file_name']      = 'account';
$info['folder_name']    = 'account.module.php';
$info['image_name']     = 'module_account.jpg';
$info['version']        = (float) 0.1;
$info['cs_version']     = (float) 0.1;
$info['core']           = 1;