<?php
require '../../shared/prepend.php';

$news_id = (int) get('news_id');
$pseudo = htmlspecialchars(post('pseudo'));
$body = htmlspecialchars(post('body'));
$preview = post('preview');

if (User::isLoggedIn()) $pseudo = null;

$newslist = $Db->getRow("SELECT n.*, u.nick FROM ".DB_PREFIX."news n LEFT JOIN ".DB_PREFIX."users u USING(user_id) WHERE n.news_id = ?", $news_id);
$newslist['comments'] = $Db->getAll("SELECT c.*, u.nick FROM ".DB_PREFIX."news_comments c LEFT JOIN ".DB_PREFIX."users u USING(user_id) WHERE c.news_id = ? ORDER BY c.added ASC", $news_id);
if (!$newslist) { include ROOT.'/shared/error.php'; }

if (!$preview && User::mayAddComment() && (User::isLoggedIn() || $pseudo) && $body) {
    $Db->execute("INSERT INTO ".DB_PREFIX."news_comments SET news_id = ?, body = ?, added = NOW(), user_id = ?, pseudo = ?, ip = ?, host = ?", $news_id, $body, User::getId(), $pseudo, getRemoteAddr(), getRemoteHost());
    if ($pseudo) {
        setcookie('pseudo', $pseudo, time()+3600*24*365, WWW_ROOT.'/');
    }
    header("Location: news_comments.php?news_id=$news_id#addcomment");
    exit;
}

/* anzahl der news comments auslesen */
$newslist['nr_news_comments'] = $Db->getOne("SELECT COUNT(*) FROM ".DB_PREFIX."news_comments WHERE news_id = ?", $news_id);

/* author des letzten comments auslesen */
$newslist['lastcomment_by'] = $Db->getOne($Db->limitQuery("SELECT IFNULL(u.nick, c.pseudo) FROM ".DB_PREFIX."news_comments c LEFT JOIN ".DB_PREFIX."users u USING(user_id) WHERE c.news_id = ? ORDER BY c.comment_id DESC", 0, 1), $news_id);

/* Header mit Überschrift einbinden */
include_header('News Comments');

/* Main: news_comments.tpl einlesen */
/* Variablen einfügen */
$_CONFIG['template_dir'] = ROOT .'/module/news/templates';
$Page = new SmarterTemplate( "news_comments.tpl" );
$Page->assign('language', $languagetext);
$Page->assign('corelanguage', $corelanguage);
$Page->assign('newslist', $newslist);

/* Template ausgeben */
if (DEBUG == 1) { $Page->debug(); 
						echo "Debug Array from Database Query | <pre>",print_r($newslist),"</pre>"; 
			} else { $Page->output(); }
			
/* Wenn der jeweilige User die Rechte zum "adden eines Comments" hat,
   dann Formular ausgeben */
if (User::mayAddComment()): ?>

        <h2><a name="addcomment">Add Comment</a></h2>

        <?php if (post('preview')): ?>
        <div class="comment">
            <div class="header">PREVIEW</div>
            <div class="body"><?php echo nl2br(htmlspecialchars(post('body'))); ?></div>
        </div>
        <?php endif; ?>
        
        <form action="news_comments.php?news_id=<?php echo get('news_id'); ?>#addcomment" method="post">
            <p>
                Nickname:
                <?php if (User::isLoggedIn()): ?>
                    <strong><?php echo User::getNick(); ?></strong>
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
            <?php if (!User::isLoggedIn()): ?>
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

    <?php endif; 		
/* Footer mit Überschrift einbinden */
include_footer(); ?>