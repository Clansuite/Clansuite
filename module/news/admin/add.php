<?php
require '../../shared/prepend.php';

$title = htmlspecialchars(post('title'));
$body = post('body');
$preview = post('preview');

if ($User->isLoggedIn()) $pseudo = null;

if (!$preview && $User->mayAddComment() && ($User->isLoggedIn() || $pseudo) && $title && $body) {
     $Db->execute("INSERT INTO " . DB_PREFIX . "news SET news_title = ?, news_body = ?, news_added = NOW(), user_id = ?", $title, $body, $User->getId());
    if ($pseudo) {
        setcookie('pseudo', $pseudo, time()+3600*24*365, WWW_ROOT.'/');
    }
    header('Location: index.php');
    exit;
}

?>

<?php $TITLE = 'Add News'; ?>
<?php include '../shared/header.tpl'; ?>

    <h1>Add News</h1>

    <?php if (post('preview')): ?>
        <div class="news">
        <div class="header">PREVIEW</div>
        <div class="title"><?php echo $title; ?></div>
        <div class="submitted">
            Submitted by <a href="users/view.php?user_id=<?php echo $User->getId() ?>"><?php echo $User->getNick($User->getId()) ?></a>
            on <?php echo date("Y-m-d H:i"); ?>
        </div>
        <div class="body"><?php echo $body; ?></div>
   </div>
        <?php endif; ?>
    
          <script type="text/javascript">
	function cookieSave(name, text) {
		document.cookie = name + "=" + escape(text);
	}

	function cookieLoad(name) {
		var search = name + "=";
		if (document.cookie.length > 0) {
			offset = document.cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = document.cookie.indexOf(";", offset);
				if (end == -1) {
					end = document.cookie.length;
				}
				return unescape(document.cookie.substring(offset, end));
			}
		}
	}
</script>
    
    
    
    <form action="add.php" method="post">

        <label for="title">Title:</label>
        <input size="50" type="text" name="title" id="title" value="<?php echo htmlspecialchars($title); ?>">

        <div class="label">Body:</div>
        <textarea cols="80" rows="20" name="body" id="body" onkeyup="cookieSave('body',this.value);"><?php echo htmlspecialchars($body); ?></textarea>
        <script type="text/javascript">document.getElementById('body').value=cookieLoad('body');</script>
       
        <div class="submit">
            <input type="submit" name="submit" value="Add news" onclick="return validateForm(this.form);">
            <input type="button" value="Cancel" onclick="location='index.php'">
            <input type="checkbox" name="preview" value="1" checked="checked">preview
        </div>

    </form>

    <style type="text/css">@import url("../shared/texteditor/SimpleTextEditor.css");</style>
    <script type="text/javascript" src="../shared/texteditor/SimpleTextEditor.js"></script>
    <script type="text/javascript">
    var ste = new SimpleTextEditor("body", "ste");
    ste.path = "<?php echo WWW_ROOT.'/admin/shared/texteditor/'; ?>";
    ste.cssFile = "<?php echo WWW_ROOT.'/shared/style.css'; ?>";
    ste.init();
    </script>

    <script type="text/javascript" src="../../shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        ste.submit();
        var title = form.elements["title"];
        var body = form.elements["body"];
        title.value = title.value.trim();
        body.value = body.value.trim();
        if (!title.value) { alert("Title is required"); return false; }
        if (!body.value) { alert("Body is required"); return false; }
        return true;
    }
    </script>

<?php include '../shared/footer.tpl'; ?>