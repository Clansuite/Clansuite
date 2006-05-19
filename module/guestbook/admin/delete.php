<?php
require '../../shared/prepend.php';

$gbook_id = (int) get('gbook_id');

if (!$Db->getRow("SELECT * FROM " . DB_PREFIX . "guestbook WHERE gbook_id = ?", $gbook_id)) {
    include ROOT.'/shared/error.php';
}
$Db->execute("DELETE FROM " . DB_PREFIX . "guestbook WHERE gbook_id = ?", $gbook_id);


$nr_guestbook_comments = $Db->getOne("SELECT COUNT(*) FROM " . DB_PREFIX . "guestbook_comments WHERE gbook_id = ?", $gbook_id);

if ( $nr_guestbook_comments != 0 ) {
if (!$Db->getRow("SELECT * FROM " . DB_PREFIX . "guestbook_comments WHERE gbook_id = ?", $gbook_id)) {
    include ROOT.'/shared/error.php';}
$Db->execute("DELETE FROM " . DB_PREFIX . "guestbook_comments WHERE gbook_id = ?", $gbook_id);
}


header('Location: index.php');

?>