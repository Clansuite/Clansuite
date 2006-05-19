<?php
require '../../shared/prepend.php';

$newslist = $Db->getAll("SELECT n.*, u.nick, c.cat_name, c.cat_image_url FROM ".DB_PREFIX."news n 
LEFT JOIN ".DB_PREFIX."users u USING(user_id) 
LEFT JOIN ".DB_PREFIX."category c ON ( n.news_category = c.cat_id AND c.cat_modulname = 'news')
WHERE n.news_hidden='0' ORDER BY news_id DESC");
foreach ($newslist as $k => $v) { $newslist[$k]['nr_news_comments'] = $Db->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."news_comments WHERE news_id = ?", $v['news_id']); }
foreach ($newslist as $k => $v) { if ($newslist[$k]['nr_news_comments'] > 0 ) {
				  $newslist[$k]['lastcomment_by'] = $Db->getOne("SELECT IFNULL(u.nick, c.pseudo) FROM ".DB_PREFIX."news_comments c LEFT JOIN ".DB_PREFIX."users u USING(user_id) WHERE c.news_id = ? ORDER BY c.comment_id DESC", $v['news_id']); } }

$modulname = 'news';				  
Smarty_Modul($modulname);

$ModulLanguageFile = ROOT.'/module/'.$modulname.'/language/'.$modulname.'.language.xml';
$ModulLangInit = new TMXResourceBundle($ModulLanguageFile, "DE"); // english
$modullanguage = $ModulLangInit->getResource(); // language array for english
$ModulPage->assign('modullanguage', $modullanguage);

$ModulPage->assign('news', $newslist);
$ModulPage->display('news.tpl'); 
?>