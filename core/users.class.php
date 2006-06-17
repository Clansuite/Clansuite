<?php
/**
* Users Handler Class
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
* @version    SVN: $Id: users.class.php 129 2006-06-09 12:09:03Z vain $
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
// Start of users class
//----------------------------------------------------------------
class users
{
	
	//----------------------------------------------------------------
	// Get a user from any kind of given type
	//----------------------------------------------------------------
	function get( $value='', $type='' )
	{
		switch( $type )
		{
			case 'id':
			
				break;
				
			case 'name':
			
				break;
				
			case 'nick':
			
				break;
				
			case 'clan_id':
			
				break;
				
			case 'squad_id':
			
				break;
			
			default:
				// per id! => get('2')
				break;
		}
	}
}

?>