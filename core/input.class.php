<?php
/**
* Input Handler Class
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
* @version    SVN: $Id: input.class.php 132 2006-06-09 22:47:30Z xsign $
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
// Start input class
//----------------------------------------------------------------
class input
{
	//----------------------------------------------------------------
	// Essential clean-up of $_REQUEST
	// Handle intruders/hackers
	//----------------------------------------------------------------
	function essential_cleanup()
	{
		global $cfg, $security;
		
		if ( ( isset($_REQUEST['id']) AND $this->check( $_REQUEST['id'] , 'is_violent' ) )
			OR ( isset($_REQUEST['action']) AND $this->check( $_REQUEST['action'] , 'is_violent' ) )
			OR ( isset($_REQUEST['mod']) AND $this->check( $_REQUEST['mod'] , 'is_violent' ) )
			OR ( isset($_REQUEST[$cfg->session_name]) AND $this->check( $_REQUEST[$cfg->session_name] , 'is_violent' ) ) )
		{
			$security->intruder_alert();	
		}
		
		$_REQUEST['id'] 	= isset ($_REQUEST['id']) 		? (int) $_REQUEST['id'] : null;
		$_REQUEST['mod'] 	= isset ($_REQUEST['mod']) 		? $this->check($_REQUEST['mod'], 'is_int|is_abc') ? $_REQUEST['mod'] : $cfg->std_module : $cfg->std_module;
		$_REQUEST['action']	= isset ($_REQUEST['action']) 	? $this->check($_REQUEST['action'], 'is_int|is_abc|is_custom', '_') ? $_REQUEST['action'] : $cfg->std_module_action : $cfg->std_module_action;
	}
	
	//----------------------------------------------------------------
	// Modify a given String
	//----------------------------------------------------------------
	function modify( $string='', $modificators='' )
	{
		$mods 	= array();
		$mods 	= split('[|]' ,$modificators);
		
		foreach($mods as $key => $value)
		{
			switch($value)
			{
				case 'add_slashes':
					$string = addslashes($string); 	
					break;
					
				case 'strip_slashes':
					$string = stripslashes($string); 	
					break;					

				case 'strip_tags':
					$string = striptags($string); 	
					break;

				case 'urlencode':
					$string = urlencode($string); 	
					break;

				case 'urldecode':
					$string = urldecode($string);	
					break;
				
				// Replacement: ? instead of &#233
				case 'html_replace:numeric_entities':
					 $string = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
					 break;
				
				// Replacement: zB &#8364 instead of &euro
				case 'html_replace:normal_to_numerical_entities':
					 $string = $this->modify(html_entity_decode($string),'html_numeric_entities');
					 break;
					 
				default:
					break;
			}
		}
		
		return $string;
				
	}
	
	//----------------------------------------------------------------
	// Check a string
	//----------------------------------------------------------------
	//
	// -----------------------------------------------------------------------------
	// | Possible single checks:
	// | $input->check('thisisastring...asdf', 'is_int', 'optional reg.exp pattern')
	// -----------------------------------------------------------------------------
	// 
	// is_int: 0-9
	// is_abc: a-z,A-Z
	// is_pattern: Given pattern... $input->check('abasdfdf_', 'is_pattern', '/^[a-zA-Z_]$/'); Will give true
	// is_pass_length: $cfg->min_pass_length
	// is_icq: 123-123-123 or 123123123
	// is_sessionid: a-z, A-Z, 0-9
	// is_hostname: http://www.hostname.de
	// is_url: http://www.thisurlwithpath.de/folder1/folder2
	// is_steamid: 00:0:1231451
	// is_email: test@test.de
	// is_ip: 122.122.123.123
	// is_violent: "SELECT%20", "%00", "chr(", "eval(" is declared as violent
	// 
	// Examples:
	// ---------
	// $input->check( '2344vsladkf', 'is_int' ); Will return bool(false)
	// $input->check( '2344vsladkf', 'is_pattern', '/^[a-z0-9]+$/' ); Will return bool(true)
	//
	// -----------------------------------------------------------------------------
	// | Possible multiple checks:
	// | $input->check('thisisastring...asdf', 'is_int|is_abc|is_custom', 'optional custom chars')
	// -----------------------------------------------------------------------------
	// is_int: 0-9
	// is_abc: a-z,A-Z	
	// is_custom: all avaible chars
	//
	// Examples:
	// ---------
	// $input->check( '2344avsdffs', 'is_int|is_abc' ); Will return bool(true)
	// $input->check( '235432asdlfkj_;', 'is_int|is_abc|is_custom', '_;' ); Will return bool(true)
	//
	//----------------------------------------------------------------
	function check( $string = '', $types = '', $pattern = '' )
	{
		global $error, $lang, $cfg;
		
		$r_bool = false;
		$bools 	= array();
		$a_types = array();
		$a_types = split('[|]' ,$types);
		
		if( count($a_types) > 1 )
		{
			$reg_exp = '/^[';
			
			foreach ($a_types as $key => $type)
			{
				switch ($type)
				{   
					// SPECIAL : set reg_exp to specific searchpattern
					// give-trough
					// @input : $pattern
					case 'is_custom':
						$reg_exp .= $pattern; 	
						break;
				
				    // Normal RegExp Cases
					
					// Is integer?
					case 'is_int':
						$reg_exp .= '0-9';
						break;

					// Is alphabetic?
					case 'is_abc':
						$reg_exp .= 'a-zA-Z';
						break;
		        }

			}
			
			$reg_exp .= ']+$/';
			$r_bool = preg_match($reg_exp, $string) ? true : false;
		}
		else
		{
			switch($a_types[0])
			{   
				// Different Checkconditions: Watch out! 
				// Does the password fits the minimum length?
				case 'is_pass_length':
		            $reg_exp = '/^.{'. $cfg->min_pass_length .',}$/';
					break;
			
				// SPECIAL : set reg_exp to specific searchpattern
				// give-trough
				// @input : $pattern
				case 'is_pattern':
					$reg_exp = $pattern; 	
					break;
			
				// Normal RegExp Cases
				
				// Is integer?
				case 'is_int':
					$reg_exp = '/^[0-9]+$/';
					break;

				// Is alphabetic?
				case 'is_abc':
					$reg_exp = '/^[a-zA-Z]+$/';
					break;
				
				// Is ICQ ?
				case 'is_icq':
					$reg_exp = '/^[\d-]*$/i';
					break;
				
				// Is Sessionid ? 	
				case 'is_sessionid':
					$reg_exp 	= '/^[A-Za-z0-9]+$/';
					break;
				
				// Is hostname?
				case 'is_hostname':
					$reg_exp = '/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/)*([a-z]{1,}[\w-.]{0,}).([a-z]{2,6})$/i';
					break;
				
				// Is url?
				case 'is_url':
					$reg_exp  = "/^(http:\/\/|https:\/\/|ftp:\/\/|ftps:\/\/)([a-z]{1,}[\w-.]{0,}).([a-z]{2,6})(\/{1}[\w_]{1}[\/\w-&?=_%]{0,}(.{1}[\/\w-&?=_%]{0,})*)*$/i";
					break;

				// Is Steam ID ?
				case 'is_steam_id':
					$reg_exp 	= '/^[0-9]+:[0-9]+:[0-9]+$/';
					break;
				
				// Check if mail is valid ?	
				case 'is_email':
					$reg_exp = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
					break;
				
		        // Check if valid ip ?	
				case 'is_ip':
		            $num = "(25[0-5]|2[0-4]\d|[01]?\d\d|\d)";
					$reg_exp = "/^$num\\.$num\\.$num\\.$num$/";
					break;
					
				// Check for violent code
				case 'is_violent':
					$reg_exp = '/SELECT\s|\x|chr\(|eval\(|password\s|\0|phpinfo\(/i';
					break;
		    }

			$r_bool = preg_match($reg_exp, $string) ? true : false;
		}
		
		if ($r_bool == false AND $a_types[0] != 'is_violent')
        {
            $error->error_log['security']['checked_false'] = $lang->t('A variable is checked as "false":').'Type: ' . $a_types[0];
        }
		return $r_bool;
	}		

	function fix_magic_quotes( $var = NULL, $sybase = NULL )
	{
		// if sybase style quoting isn't specified, use ini setting
		if ( !isset ($sybase) )
		{
			$sybase = ini_get ('magic_quotes_sybase');
		}

		// if no var is specified, fix all affected superglobals
		if ( !isset ($var) )
		{
			// if magic quotes is enabled
			if ( get_magic_quotes_gpc () )
			{
				// workaround because magic_quotes does not change $_SERVER['argv']
				$argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : NULL; 

				// fix all affected arrays
				foreach ( array ('_ENV', '_REQUEST', '_GET', '_POST', '_COOKIE', '_SERVER') as $var )
				{
					$GLOBALS[$var] = fix_magic_quotes ($GLOBALS[$var], $sybase);
				}

				$_SERVER['argv'] = $argv;

				// turn off magic quotes, this is so scripts which
				// are sensitive to the setting will work correctly
				ini_set ('magic_quotes_gpc', 0);
			}

			// disable magic_quotes_sybase
			if ( $sybase )
			{
				ini_set ('magic_quotes_sybase', 0);
			}

			// disable magic_quotes_runtime
			set_magic_quotes_runtime (0);
			return TRUE;
		}

		// if var is an array, fix each element
		if ( is_array ($var) )
		{
			foreach ( $var as $key => $val )
			{
				$var[$key] = fix_magic_quotes ($val, $sybase);
			}

			return $var;
		}

		// if var is a string, strip slashes
		if ( is_string ($var) )
		{
			return $sybase ? str_replace ('\'\'', '\'', $var) : stripslashes ($var);
		}

		// otherwise ignore
		return $var;
	}
}	
?>