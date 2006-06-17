<?php
/**
* Index Modul Handler Class
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
	die( 'You are not allowed to view this page statically.' );	
}

	
class module_index
{
	public $output 			= '';
	public $mod_page_title 	= '';
	public $additional_head = '';
	
	/**
	* @desc First function to run - switches between $_REQUEST['action'] Vars to the functions
	* @desc Loads necessary language files
	*/
	function auto_run()
	{
		global $lang;
		
		$this->mod_page_title = $lang->t( 'Entrance' );
		
		switch ($_REQUEST['action'])
		{
			case 'show':
				$this->index_show();
				break;
			default:
				$this->index_show();
				break;	
		}
		
		return array( 	'OUTPUT' 			=> $this->output,
						'MOD_PAGE_TITLE'	=> $this->mod_page_title,
						'ADDITIONAL_HEAD'	=> $this->additional_head );
	}
	
	/**
	* @desc Show the entracne - welcome message etc.
	*/
	function index_show()
	{
		global $tpl, $error, $lang;
		
		$this->output .= $tpl->fetch('index/show.tpl');
		$this->output .= $error->show( $lang->t('Module Failure'), $lang->t('The Module has a failure (testing $error->show... Level 2 Failure...)'), 2);
	}
	
	/**
	* @desc Give the time
	* @desc type: unix,german,english	
	*/
	function index_time( $type='unix', $seperator='.' )
	{
		switch($type)
		{
			case 'unix':
				$time = time();	
				break;
			
			case 'german':
				$time = date( 'd'.$seperator.'m'.$seperator.'Y', time() );
				break;
				
			case 'english':
				$time = date( 'Y'.$seperator.'m'.$seperator.'d', time() );
				break;
		}
		return $time;	
	}
}
?>