<?php
require '../../shared/prepend.php';

$news_id = (int) get('news_id');

if (!$Db->getRow("SELECT * FROM " . DB_PREFIX . "news WHERE news_id = ?", $news_id)) {
    include ROOT.'/shared/error.php';
}
$Db->execute("DELETE FROM " . DB_PREFIX . "news WHERE news_id = ?", $news_id);


$nr_news_comments = $Db->getOne("SELECT COUNT(*) FROM " . DB_PREFIX . "news_comments WHERE news_id = ?", $news_id);

// in case the news had comments delete them
if ( $nr_news_comments != 0 ) {
if (!$Db->getRow("SELECT * FROM " . DB_PREFIX . "news_comments WHERE news_id = ?", $news_id)) {
    include ROOT.'/shared/error.php';}
$Db->execute("DELETE FROM " . DB_PREFIX . "news_comments WHERE news_id = ?", $news_id);
}


header('Location: index.php');

?>