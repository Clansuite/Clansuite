<?php
require '../../shared/prepend.php';

$comment_id = (int) get('comment_id');
$news_id = (int) get('news_id');

if (!$Db->getRow("SELECT * FROM " . DB_PREFIX . "news_comments WHERE comment_id = ?", $comment_id)) {
    include ROOT.'/shared/error.php';
}
$Db->execute("DELETE FROM " . DB_PREFIX . "news_comments WHERE comment_id = ?", $comment_id);

header("Location: add_news_comment.php?news_id=$news_id#comments");

?>