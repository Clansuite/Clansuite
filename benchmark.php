<?php
/**
* Benchmark: define vs. array as language modul
* @desc ARRAY:	0.0004 
* @desc DEFINE: 0.0002
* @desc XML:	0.0003 
*/

function start_timer($event) {
printf("timer: %s<br>\n", $event);
list($low, $high) = explode(" ", microtime());
$t = $high + $low;
flush();

return $t;
}

function next_timer($start, $event) {
list($low, $high) = explode(" ", microtime());
$t    = $high + $low;
$used = $t - $start;
printf("timer: %s (%8.4f)<br>\n", $event, $used);
flush();

return $t;
}

$t = start_timer("start Befehl 1");

/**
* @desc array
*/

$lang = array(
'not_in_modules_array'	=> 'The module you tried to enter isn\'t registered in $cfg->modules',
'wrong_get_input'		=> 'Only allowed as §_GET input: a-z, A-Z, 0-9, -, _',
'wrong_post_input'		=> 'Allowed as $_POST[mod] or $_POST[do] input: a-z, A-Z, 0-9, -, _',
'no_direct_input'		=> 'Do not use $_GET,$_POST or $_REQUEST ! The variable $input gives you a secure and filtered input.',
);

echo $lang['not_in_modules_array'].'<br>';
echo $lang['wrong_get_input'].'<br>';
echo $lang['wrong_post_input'].'<br>';


$t = next_timer($t, "start Befehl 2");

/**
* @desc define
*/
define('not_in_modules_array'	, 'The module you tried to enter isn\'t registered in $cfg->modules');
define('wrong_get_input'		, 'Only allowed as §_GET input: a-z, A-Z, 0-9, -, _');
define('wrong_post_input'		, 'Allowed as $_POST[mod] or $_POST[do] input: a-z, A-Z, 0-9, -, _');
define('no_direct_input'		, 'Do not use $_GET,$_POST or $_REQUEST ! The variable $input gives you a secure and filtered input.');

echo not_in_modules_array.'<br>';
echo wrong_get_input.'<br>';
echo wrong_post_input.'<br>';

$t = next_timer($t, "start Befehl 3");
/**
* @desc XML
*/
$xml_string = <<<XML
<?xml version='1.0' standalone='yes'?>
<!--
* @desc index module malnguage file
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
-->

<xml>
	<error>
		<not_in_modules_array>The module you tried to enter isn\'t registered in $cfg->modules</not_in_modules_array>
		<wrong_get_input>Only allowed as $_GET input: a-z, A-Z, 0-9, -, _</wrong_get_input>
		<wrong_post_input>Allowed as $_POST[mod] or $_POST[do] input: a-z, A-Z, 0-9, -, _</wrong_post_input>
		<no_direct_input>Do not use $_GET,$_POST or $_REQUEST ! The variable $input gives you a secure and filtered input.</no_direct_input>
	</error>
</xml>
XML;
$xml_object = simplexml_load_string($xml_string);
echo $xml_object->error->not_in_modules_array.'<br>';
echo $xml_object->error->wrong_get_input.'<br>';
echo $xml_object->error->no_direct_input.'<br>';


$t = next_timer($t, "finish");

?>