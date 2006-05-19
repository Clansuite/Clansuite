<?php
require '../../shared/prepend.php';

$gbooklist = $Db->getAll("SELECT n.* FROM " . DB_PREFIX . "guestbook n ORDER BY gbook_id DESC");
foreach ($gbooklist as $k => $v) { $gbooklist[$k]['nr_comments'] = $Db->getOne("SELECT COUNT(*) FROM " . DB_PREFIX . "guestbook_comments WHERE gbook_id = ?", $v['gbook_id']); }
//foreach ($gbooklist as $k => $v) { $gbooklist[$k]['lastcomment'] = $Db->getOne($Db->limitQuery("SELECT IFNULL(u.nick, c.pseudo) FROM " . DB_PREFIX . "guestbook_comments c LEFT JOIN users u USING(user_id) WHERE c.gbook_id = ? ORDER BY c.comment_id DESC", 0, 1), $v['gbook_id']); }
foreach ($gbooklist as $k => $v) { if ($gbooklist[$k]['nr_comments'] > 0 ) {
$gbooklist[$k]['comments'] = $Db->getAll("SELECT c.*, u.nick FROM " . DB_PREFIX . "guestbook_comments c 
LEFT JOIN " . DB_PREFIX . "users u USING(user_id) WHERE c.gbook_id = ? 
ORDER BY c.added ASC", $v['gbook_id']); } }

include_header('Guestbook');
 
//MAIN
$_CONFIG['template_dir'] = ROOT .'/module/guestbook/templates';
$Page = new SmarterTemplate( "guestbook.tpl" );
$Page->assign('modullanguage', $modullanguage);
$Page->assign('corelanguage', $corelanguage);
$Page->assign('guestbook', $gbooklist);

//template ausgeben
if (DEBUG == 1) { $Page->debug(); 
echo "Debug Array from Database Query | <pre>",print_r($gbooklist),"</pre>"; 
			} else { $Page->output(); }
			
include_footer();
?>