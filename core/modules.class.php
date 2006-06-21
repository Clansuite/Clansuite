<?php
/**
* Modules Handler Class
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
* @version    SVN: $Id: modules.class.php 129 2006-06-09 12:09:03Z vain $
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
// Start modules class
//----------------------------------------------------------------	
class modules
{
   public $loaded = array();
   
   //----------------------------------------------------------------
   // Register {mod} in SMARTY Template Engine
   //----------------------------------------------------------------
   function __construct()
   {
		global $tpl;
		
		$tpl->register_function('mod', array('modules','get_instant_content'), false);
   }
   
	//----------------------------------------------------------------
	// {mod} handler function
	//----------------------------------------------------------------
   static function get_instant_content($params) 
   {
		global $modules, $cfg, $lang;

		if ( array_key_exists( $params['name'], $cfg->modules ) )
		{ 
			$file = MOD_ROOT . '/' . $cfg->modules[$params['name']]['folder_name'] . '/' . $cfg->modules[$params['name']]['file_name'];

			if ( file_exists ( $file ) )
			{
				if( !in_array( $params['name'], $modules->loaded ) )
				{
					$modules->loaded[] = $params['name'];
				}
				
				require_once ( $file );				
				$class_name = $cfg->modules[$params['name']]['class_name'];
				$module_{$params['name']} = new $class_name;	
				$func_params = split('\|', $params['params']);

				if ( method_exists( $module_{$params['name']}, $params['func'] ) )
					echo call_user_func_array(array($module_{$params['name']}, $params['func']), $func_params);
				else
					echo $lang->t('This method doesn not exist');
			}
		}
   }
   

	//----------------------------------------------------------------
	// Get normal content of a module from auto_run()
	//----------------------------------------------------------------	
	function get_content($mod='')
	{
		global $cfg, $error, $lang, $functions;
		
		$mod = $mod=='' ? $cfg->std_module : $mod ;

		if ( array_key_exists( $mod, $cfg->modules ) )
		{
		
			$file = MOD_ROOT . '/' . $cfg->modules[$mod]['folder_name'] . '/' . $cfg->modules[$mod]['file_name'];
			if ( file_exists ( $file ) )
			{
				require_once ( $file );
				$class_name = $cfg->modules[$mod]['class_name'];
				${$mod} = new $class_name;	
				$content = ${$mod}->auto_run();
				return $content;
			}
		}
		else
		{
			$error->error_log['no_module']['not_in_array'] = $lang->t('The module you tried to enter is not registered');
			
			$mod = $cfg->std_module;
				
			$content['OUTPUT'] = $lang->t('This module does not exist! You are being redirected in 5 seconds...');
			$functions->redirect( '/index.php', 'metatag', '5' );
			return $content;
		}
	}
}
?>