<?php
require '../../shared/prepend.php';

$gbook_id = (int) get('gbook_id');
$pseudo = htmlspecialchars(post('pseudo'));
$body = htmlspecialchars(post('body'));
$preview = post('preview');

if ($User->isLoggedIn()) $pseudo = null;

$gbooks = $Db->getRow("SELECT n.* FROM " . DB_PREFIX . "guestbook n WHERE n.gbook_id = ?", $gbook_id);
if (!$gbooks) { include ROOT.'/shared/error.php'; }

if (!$preview && $User->mayAddComment() && ($User->isLoggedIn() || $pseudo) && $body) {
    $Db->execute("INSERT INTO " . DB_PREFIX . "guestbook_comments SET gbook_id = ?, body = ?, added = NOW(), user_id = ?, pseudo = ?, ip = ?, host = ?", $gbook_id, $body, $User->getId(), $pseudo, getRemoteAddr(), getRemoteHost());
    if ($pseudo) {
        setcookie('pseudo', $pseudo, time()+3600*24*365, WWW_ROOT.'/');
    }
    header("Location: gbook_comments.php?gbook_id=$gbook_id#addcomment");
    exit;
}

$gbooks['guest_comments'] = $Db->getOne("SELECT COUNT(*) FROM " . DB_PREFIX . "guestbook_comments WHERE gbook_id = ?", $gbook_id);
$gbooks['lastcomment'] = $Db->getOne($Db->limitQuery("SELECT IFNULL(u.nick, c.pseudo) FROM " . DB_PREFIX . "guestbook_comments c LEFT JOIN " . DB_PREFIX . "users u USING(user_id) WHERE c.gbook_id = ? ORDER BY c.comment_id DESC", 0, 1), $gbook_id);
foreach ($gbooklist as $k => $v) { $gbooklist[$k]['comment'] = 
$Db->getAll("SELECT c.*, u.nick FROM " . DB_PREFIX . "guestbook_comments c 
LEFT JOIN " . DB_PREFIX . "users u USING(user_id) WHERE c.gbook_id = ? 
ORDER BY c.comment_id ASC", $v['gbook_id']); }

?>

<?php $TITLE = 'Guestbook'; include '../shared/header.tpl'; ?>

<h2>Comments</h2>

    <?php foreach ($comments as $n => $comment): ?>
    <div class="comment">
        <div class="header">
            <?php if ($comment['pseudo']) echo '<div class="ip">'.$comment['ip'].' / '.$comment['host'].'</div>'; ?>
            <div class="added">Comment #<?php echo $n+1; ?> added by
                <?php if ($comment['user_id']) echo '<a href="users/view.php?user_id='.$comment['user_id'].'">'.$gbook[comment]['nick'].'</a>';
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
        
        <form action="gbook_comments.php?gbook_id=<?php echo get('gbook_id'); ?>#addcomment" method="post">
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

        <script type="text/javascript" src="shared/form.js"></script>
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