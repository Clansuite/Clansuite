<?php
require '../../shared/prepend.php';

$news_id = (int) get('news_id');
$title = htmlspecialchars(post('title'));
$body = post('body');
$preview = post('preview');

$newsrecord = $Db->getRow("SELECT * FROM " . DB_PREFIX . "news WHERE news_id = ?", $news_id);
if (!$newsrecord) { include ROOT.'/shared/error.php'; }

if ($User->isLoggedIn()) $pseudo = null;

if (!$preview && $User->mayEditNews() && ($User->isLoggedIn() || $pseudo) && $title && $body) {
     $Db->execute("UPDATE " . DB_PREFIX . "news SET news_title = ?, news_body = ? WHERE news_id = ?", $title, $body, $news_id);
    if ($pseudo) {
        setcookie('pseudo', $pseudo, time()+3600*24*365, WWW_ROOT.'/');
    }
    header('Location: index.php');
    exit;
} else {
    $title = $newsrecord['news_title'];
    $body = $newsrecord['news_body'];
}

?>

<?php $TITLE = 'Edit News'; ?>
<?php include '../shared/header.tpl'; ?>

    <h1>Edit News</h1>

    <form action="edit.php?news_id=<?php echo $news_id; ?>" method="post">

        <label for="title">Title:</label>
        <input size="50" type="text" name="title" id="title" value="<?php echo unhtmlspecialchars($title); ?>">

        <div class="label">Body:</div>
        <textarea cols="80" rows="20" name="body" id="body"><?php echo htmlspecialchars($body); ?></textarea>

        <div class="submit">
            <input type="submit" name="submit" value="Edit news" onclick="return validateForm(this.form);">
            <input type="button" value="Cancel" onclick="location='index.php'">
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