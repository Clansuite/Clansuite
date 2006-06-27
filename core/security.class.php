<?php
/**
* Security Handler Class
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
* @version    SVN: $Id: security.class.php 130 2006-06-09 22:41:05Z xsign $
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
// Start of security class	
//----------------------------------------------------------------
class security
{
	//----------------------------------------------------------------
	// Salt creation e.g. "4-5-6-7-8-9"
	//----------------------------------------------------------------
	function generate_salt()
	{
		$salt = rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9).'-'.rnd(0,9);
		return $salt;
	}
	
	//----------------------------------------------------------------
	// MD5 Hash creation
	//----------------------------------------------------------------
	function generate_md5( $string = '' )
	{
		return md5( md5( $string ) );	
	}
	
	//----------------------------------------------------------------
	// SHA1 Hash creation
	//----------------------------------------------------------------
	function generate_sha1( $string = '' )
	{
		return sha1( sha1( $string ) );
	}
	
	//----------------------------------------------------------------
	// Build salted Hash string
	//----------------------------------------------------------------
	function build_salted_hash( $string = '' )
	{
		global $cfg;
		
		$salt = split('-', $cfg->salt);
		
		switch ( $cfg->encryption )
		{
			case 'sha1':
				$hash = $this->generate_sha1( $string );
				break;
				
			case 'md5':
				$hash = $this->generate_md5( $string );
				break;
		}
		
		
		for($x=0;$x<3;$x++)
		{
			$hash = str_replace( $salt[$x], $salt[$x+3], $hash );
		}
		
		return $hash;
	}
	
	//----------------------------------------------------------------
	// Check for {$copyright} tag in $cfg->tpl_wrapper_file
	//----------------------------------------------------------------
	function check_copyright($file)
	{
		global $lang;
		
		if( file_exists($file) )
		{
			$string = file_get_contents($file);
			preg_match( '/\{\$copyright\}/' , $string ) ? '' : die( $lang->t('You removed the copyright tag - that is not allowed and a violation of our rules!') );
			preg_match( '/\<\!\-\-[^>](.*)\{\$copyright\}(.*)\-\-\>/', $string ) ? die( $lang->t('Do not try to fool the system by hiding the copyright tag!') ) : '';
		}
		else
		{
			die( $lang->t('The template File with the copyright Tag {$copyright} does not exist!') );
		}
	}
	
	//----------------------------------------------------------------
	// Handle Intruders
	//----------------------------------------------------------------
	function intruder_alert()
	{
		global $tpl, $lang;
		$tpl->assign('hacking_attempt', $lang->t('There seems to be an hacking attempt in progress - Every step is logged and the webmaster is informed about the problem.'));
		$tpl->assign('user_ip'		, $_SERVER['REMOTE_ADDR']);
		$tpl->assign('hostname'		, isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : '*** not supported by your apache ***' );
		$tpl->assign('user_agent'	, $_SERVER['HTTP_USER_AGENT']);
		$tpl->display( 'security_breach.tpl' );
		die();
	}
}

?>