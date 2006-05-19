<?php
/*****************************************************************************/
/* Clansuite - just another E-Sport CMS                                      */
/* Copyright (C) 1999 - 2006 Jens-André Koch (jakoch@web.de)                 */
/*                                                                           */
/* Clansuite is free software; you can redistribute it and/or modify         */
/* it under the terms of the GNU General Public License as published by      */
/* the Free Software Foundation; either version 2 of the License, or         */
/* (at your option) any later version.                                       */
/*                                                                           */
/* Clansuite is distributed in the hope that it will be useful,              */
/* but WITHOUT ANY WARRANTY; without even the implied warranty of            */
/* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             */
/* GNU General Public License for more details.                              */
/*                                                                           */
/* You should have received a copy of the GNU General Public License         */
/* along with this program; if not, write to the Free Software               */
/* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA  */
/*****************************************************************************/

require 'shared/prepend.php';

include_header('Online Stats');
$modulname = 'stats';

$_CONFIG['template_dir'] = ROOT.'/module/'.$modulname.'/templates';
$Page = new SmarterTemplate( "whoisonline.tpl" );

// MAIN
#include_language
$ModulLanguageFile = ROOT.'/module/'.$modulname.'/language/'.$modulname.'.language.xml';
$ModulLangInit = new TMXResourceBundle($ModulLanguageFile, "DE"); // english
$modullanguage = $ModulLangInit->getResource(); // language array for english

// Gäste
$guestonline = $Db->getAll("SELECT s.user_id, s.SessionWhere
		  			   FROM ".DB_PREFIX."session s 
					   WHERE s.SessionVisibility = 1 AND s.user_id = 0");

// Member
$memberonline = $Db->getAll("SELECT s.user_id, s.SessionWhere
		  			   FROM ".DB_PREFIX."session s 
					   WHERE s.SessionVisibility = 1 AND s.user_id = 1");

// if useradmin -> show hidden ... s.SessionVisibilty = 0
// mark user with ( a red ! or ghost-symbol)

// who were online ? ( timestamp < now - 24h ) 
$wereonline = $Db->getAll("SELECT u.user_id, u.nick, u.first_name, u.last_name, u.timestamp 
		  			   FROM ".DB_PREFIX."users u 
					   WHERE u.timestamp > NOW() - INTERVAL 1 DAY");


$Page->assign('wereonline', $wereonline);
$Page->assign('memberonline', $memberonline);
$Page->assign('guestonline', $guestonline);


//template ausgeben
if (DEBUG == 1) { $Page->debug(); 
	echo "Debug Array from Database Query | <pre>",print_r($wereonline),"</pre>"; 
			} else { $Page->output(); }

include_footer(); ?>