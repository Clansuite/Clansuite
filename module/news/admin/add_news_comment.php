<?php
require '../../shared/prepend.php';

$news_id = (int) get('news_id');
$pseudo = htmlspecialchars(post('pseudo'));
$body = htmlspecialchars(post('body'));
$preview = post('preview');

if ($User->isLoggedIn()) $pseudo = null;

$news = $Db->getRow("SELECT n.*, u.nick FROM " . DB_PREFIX . "news n LEFT JOIN " . DB_PREFIX . "users u USING(user_id) WHERE n.news_id = ?", $news_id);
if (!$news) { include ROOT.'../shared/error.php'; }

if (!$preview && $User->mayAddComment() && ($User->isLoggedIn() || $pseudo) && $body) {
    $Db->execute("INSERT INTO " . DB_PREFIX . "news_comments SET news_id = ?, news_body = ?, news_added = NOW(), user_id = ?, pseudo = ?, ip = ?, host = ?", $news_id, $body, $User->getId(), $pseudo, getRemoteAddr(), getRemoteHost());
    if ($pseudo) {
        setcookie('pseudo', $pseudo, time()+3600*24*365, WWW_ROOT.'/');
    }
    header("Location: add_news_comment.php?news_id=$news_id#addcomment");
    exit;
}

$news['nr_news_comments'] = $Db->getOne("SELECT COUNT(*) FROM " . DB_PREFIX . "news_comments WHERE news_id = ?", $news_id);
$news['lastcomment'] = $Db->getOne($Db->limitQuery("SELECT IFNULL(u.nick, c.pseudo) FROM " . DB_PREFIX . "news_comments c LEFT JOIN " . DB_PREFIX . "users u USING(user_id) WHERE c.news_id = ? ORDER BY c.comment_id DESC", 0, 1), $news_id);
$comments = $Db->getAll("SELECT c.*, u.nick FROM " . DB_PREFIX . "news_comments c LEFT JOIN " . DB_PREFIX . "users u USING(user_id) WHERE news_id = ? ORDER BY comment_id ASC", $news_id);

?>

<?php $TITLE = $news['news_title']; ?>
<?php include '../shared/header.tpl'; ?>

    <div class="news">
        <div class="title"><?php echo $news['news_title']; ?>
        <input type="button" class="button" value="Edit" onclick="location='edit.php?news_id=<?php echo $news['news_id']; ?>'">
        </div>
        <div class="submitted">
            Submitted by <a href="users/view.php?user_id=<?php echo $news['user_id']; ?>"><?php echo $news['nick']; ?></a>
            on <?php echo substr($news['news_added'], 0, -3); ?>
        </div>
        <div class="body"><?php echo $news['news_body']; ?></div>
        <div class="comments">
            <strong>&raquo;</strong> <a href="../../news_comments.php?news_id=<?php echo $news['news_id']; ?>"><?php echo $news['nr_news_comments']; ?> comments</a>
            <?php if ($news['lastcomment']): ?>
            <div>Last comment: <span><?php echo $news['lastcomment']; ?></span></div>
            <?php endif; ?>
        </div>
    </div>

    
    <h2><a name="comments">Comments</a></h2>

    <?php foreach ($comments as $n => $comment): ?>
    <div class="comment">
        <div class="header">
        <div style="text-align:right">
        <input type="button" class="button" value="Edit Comment #<?php echo $comment['comment_id']; ?>" onclick="location='edit_news_comment.php?comment_id=<?php echo $comment['comment_id']; ?>'">
        
        <input type="button" class="button" value="Delete" 
            onclick="if (confirm('Delete Comment #<?php echo $comment['comment_id'].' written by '.$comment['nick'];?>?')) 
            location = 'delete_news_comment.php?news_id=<?php echo $news['news_id'].'&comment_id='.$comment['comment_id'] ?>';">
            
            </div>
            <?php if ($comment['pseudo']) echo '<div class="ip">'.$comment['ip'].' / '.$comment['host'].'</div>'; ?>
            <div class="added">
                #<?php echo $n+1; ?> added by
                <?php if ($comment['user_id']) echo '<a href="users/view.php?user_id='.$comment['user_id'].'">'.$comment['nick'].'</a>';
                      else echo '<strong>'.$comment['pseudo'].'</strong>'; ?>
                on <?php echo substr($comment['added'], 0, -3); ?>
            </div>
        </div>
        <div class="body"><?php echo nl2br($comment['body']); ?></div>
    </div>
    <?php endforeach; ?>

    
    <?php if ($User->mayAddComment()): ?>

        <h2><a name="addcomment">Add Comment</a></h2>

        <?php if (post('preview')): ?>
        <div class="comment">
            <div class="header">PREVIEW</div>
            <div class="body"><?php echo nl2br(htmlspecialchars(post('body'))); ?></div>
        </div>
        <?php endif; ?>
        
        <form action="add_news_comment.php?news_id=<?php echo get('news_id'); ?>#addcomment" method="post">
            <p>
                Nickname:
                <?php if ($User->isLoggedIn()): ?>
                    <strong><?php echo $User->getNick(); ?></strong>
                <?php else: ?>
                    <input maxlength="25" type="text" name="pseudo" value="<?php echo post('pseudo') ? htmlspecialchars(post('pseudo')) : cookie('pseudo'); ?>">
                <?php endif; ?>
            </p>
            <textarea cols="60" rows="10" name="body"><?php echo htmlspecialchars(post('body')); ?></textarea>
            <div class="submit">
                <input type="submit" name="submit" value="Add Comment" onclick="return validateForm(this.form);">
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
<?php echo "<pre>",print_r($comments),"</pre>"; ?>