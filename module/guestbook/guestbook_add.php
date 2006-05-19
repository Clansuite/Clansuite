<?php require '../../shared/prepend.php';

$gbook_nick = htmlspecialchars(post('gbook_nick'));
$gbook_email = post('gbook_email');
$gbook_icq = post('gbook_icq');
$gbook_website = post('gbook_website');
$gbook_town = post('gbook_town');
$gbook_text = post('gbook_text');
$gbook_ip = post('gbook_ip');

if ($gbook_nick && $gbook_text) {
    $Db->execute("INSERT INTO " . DB_PREFIX . "guestbook 
    					SET users_id = ?, gbook_time_added = NOW(), gbook_nick = ?, 
    					gbook_email = ?, gbook_icq = ?, gbook_website = ?, 
    					gbook_town = ?, gbook_text = ?, gbook_ip = ?",
   $User->getId(), $gbook_nick, $gbook_email, $gbook_icq, $gbook_website, $gbook_town, $gbook_text, $User->getId());
  						
    header('Location: index.php');
    exit;
}

include_header('Add Guestbook');

//MAIN
$_CONFIG['template_dir'] = ROOT .'/module/guestbook/templates';
$Page = new SmarterTemplate( "guestbook_add.tpl" );
$Page->assign('modullanguage', $modullanguage);
$Page->assign('corelanguage', $corelanguage);
//$Page->assign('news', $newslist);
//template ausgeben
if (DEBUG == 1) { $Page->debug(); 
echo "Debug Array from Database Query | <pre>",print_r($newslist),"</pre>"; 
			} else { $Page->output(); }

include_footer(); ?>