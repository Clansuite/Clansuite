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

require '../../shared/prepend.php';

// $newslist = newseinträge mit usernick und categorie
$newslist = $Db->getAll("SELECT n.*, u.nick, c.cat_name, c.cat_image_url FROM ".DB_PREFIX."news n 
		  				LEFT JOIN ".DB_PREFIX."users u USING(user_id) 
						LEFT JOIN ".DB_PREFIX."category c ON ( n.news_category = c.cat_id AND c.cat_modulname = 'news')
						WHERE n.news_hidden='0' ORDER BY news_id DESC");

// $newslist arrayergänzung um die anzahl der newscommentare
foreach ($newslist as $k => $v) 
		{ $newslist[$k]['nr_news_comments'] = $Db->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."news_comments WHERE news_id = ?", $v['news_id']); }

// falls es comments gibt, den usernick des letzten comments holen
foreach ($newslist as $k => $v) 
		{ if ($newslist[$k]['nr_news_comments'] > 0 ) {
		$newslist[$k]['lastcomment_by'] = $Db->getOne("SELECT IFNULL(u.nick, c.pseudo) FROM ".DB_PREFIX."news_comments c 
										  			   LEFT JOIN ".DB_PREFIX."users u USING(user_id) 
													   WHERE c.news_id = ? ORDER BY c.comment_id DESC", $v['news_id']); } }
// ModulInitialisieren : modulname , modul-pagetitle
ModulInit('news','Newsbereich');

// $newslist an Smarty übergeben und Template ausgeben
$ModulPage->assign('news', $newslist);
$ModulPage->display('news.tpl'); 
?>