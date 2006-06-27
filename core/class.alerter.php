<?php
/**
* Connect to CenterICQ Bot
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
* @version    SVN: $Id$
* @link       http://gna.org/projects/clansuite
* @since      File available since Release 0.1
*/

class alerter
{
	function alert($text, $uin)
	{
		$text = addslashes($text);
		$process = popen("/bin/bash", "w");
		fputs($process, "su teamn1com -c 'echo -e \"".$text."\" | /usr/local/bin/centericq -s msg -p icq -t ".$uin." | exit' 2>/tmp/error.txt\nzYTcvhOs\n");
		fclose($process);
	}
}

$alerter = new alerter;

$uids = array( 	1 => "163164530",
						2 => "163164530",
						3 => "163164530",
						4 => "163164530",
						5 => "163164530",
						6 => "163164530", );

foreach( $uids as $key => $value )
{
	$alerter->alert($key.time(), $value);
	echo $value . ' alerted with: ' . $key.time();
}
?>