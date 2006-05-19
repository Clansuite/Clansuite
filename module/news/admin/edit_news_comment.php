<?php
require '../../shared/prepend.php';

$comment_id = (int) get('comment_id');
$pseudo = htmlspecialchars(post('pseudo'));
$body = htmlspecialchars(post('body'));
$preview = post('preview');

if ($User->isLoggedIn()) $pseudo = null;

$comment = $Db->getRow("SELECT * FROM " . DB_PREFIX . "news_comments WHERE comment_id = ?", $comment_id);
if (!$comment) { include ROOT.'/shared/error.php'; }

if (!$preview && $User->mayEditComment() && ($User->isLoggedIn() || $pseudo) && $body) {
    $Db->execute("UPDATE " . DB_PREFIX . "news_comments SET body = ? WHERE comment_id = ?", $body, $comment_id);
    if ($pseudo) {
        setcookie('pseudo', $pseudo, time()+3600*24*365, WWW_ROOT.'/');
    }
    $body = htmlspecialchars(post('body'));
    header("Location: edit_news_comment.php?comment_id=$comment_id#editcomment");
    exit;
} else
{ 
if (!$body) { $body = $comment['body']; } else { $body = htmlspecialchars(post('body'));}
}


?>

<?php $TITLE = 'Edit Comment '.$comment['comment_id'] ?>
<?php include '../shared/header.tpl'; ?>

   <?php if ($User->mayAddComment()): ?>

        <h2><a name="editcomment"><?php echo $TITLE ?></a></h2>

        <?php if (post('preview')): ?>
        <div class="comment">
            <div class="header">OLD COMMENT</div>
            <div class="body"><?php echo nl2br(htmlspecialchars($comment['body'])); ?></div>
        </div>
             
        <div class="comment">
            <div class="header">PREVIEW</div>
            <div class="body"><?php echo nl2br(htmlspecialchars(post('body'))); ?></div>
        </div>
        <?php endif; ?>
        
        <form action="edit_news_comment.php?comment_id=<?php echo $comment_id ?>#editcomment" method="post">
            <p>
                original Comment written by:
                <?php if ($User->isLoggedIn()): ?> 
                    <strong><?php echo $User->getNick(); ?></strong>
                <?php else: ?>
                    <input maxlength="25" type="text" name="pseudo" value="<?php echo post('pseudo') ? htmlspecialchars(post('pseudo')) : cookie('pseudo'); ?>">
                <?php endif; ?>
            </p>
            <textarea cols="60" rows="10" name="body"><?php echo htmlspecialchars($body); ?></textarea>
            <div class="submit">
                <input type="submit" name="submit" value="<?php echo $TITLE ?>" onclick="return validateForm(this.form);">
                <input type="checkbox" name="preview" value="1" checked="checked"> preview comment
            </div>
        </form>

        <script type="text/javascript" src="../shared/form.js"></script>
        <script type="text/javascript">
        function validateForm(form) {
            <?php if (!$User->isLoggedIn()): ?>
            var pseudo = form.elements["pseudo"];
            pseudo.value = pseudo.value.trim();
            if (!checkLength(pseudo.value, 3, 25)) { alert("Nickname must have at least 3 chars"); return false; }
            <?php endif; ?>
            var body = form.elements["body"];
            body.value = body.value.trim();
            if (!body.value) { alert("Comment cannot be empty"); return false; }
            return true;
        }
        </script>

    <?php endif; ?>

<?php include '../shared/footer.tpl'; ?>
<?php echo "<pre>",print_r($comment),"</pre>"; ?>