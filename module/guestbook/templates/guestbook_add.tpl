<h1>Add Guestbook</h1>

<script type="text/javascript">
<!--
function checkLen()
{
maxLen=300;
var txt=document.forms[0].gbook_text.value;
if(txt.length>maxLen)
    {
      alert("Bitte maximal "+maxLen+" Zeichen eingeben!");
      document.forms[0].gbook_text.value=txt.substring(0,maxLen);
      document.forms[0].zaehler.value=0;
    }
else
    {
    document.forms[0].zaehler.value=maxLen-txt.length;
    }
}

//-->
</script>

    <form action="guestbook_add.php" method="post">

        <label for="gbook_nick">Nick:</label>
        <input size="20" type="text" name="gbook_nick" id="gbook_nick" value="<?php echo htmlspecialchars($gbook_nick); ?>" />
        <label for="gbook_email">gbook_email:</label> 
        <input size="20" type="text" name="gbook_email" id="gbook_email" value="<?php echo htmlspecialchars($gbook_email); ?>" />
        <label for="gbook_icq">gbook_icq:</label>
        <input size="20" type="text" name="gbook_icq" id="gbook_icq" value="<?php echo htmlspecialchars($gbook_icq); ?>" />
		  <label for="gbook_website">gbook_website:</label>
		  <input size="20" type="text" name="gbook_website" id="gbook_website" value="<?php echo htmlspecialchars($gbook_website); ?>" />
        <label for="gbook_town">gbook_town:</label>
        <input size="20" type="text" name="gbook_town" id="gbook_town" value="<?php echo htmlspecialchars($gbook_town); ?>" />

        <div class="label">Text:</div>
        <textarea cols="80" rows="20" name="gbook_text" id="gbook_text" onkeyup="checkLen();"><?php echo htmlspecialchars($gbook_text); ?></textarea>
        <div>verbleibende Zeichen: <input type="text" name="zaehler" value="300" size="3" readonly="readonly" /></div>
        
        <div class="submit">
            <input type="submit" name="submit" value="Add Guestbook" onclick="return validateForm(this.form);" />
            <input type="button" value="Cancel" onclick="location='index.php'" />
        </div>

    </form>

    <style type="text/css">@import url("../../shared/texteditor/SimpleTextEditor.css");</style>
    <script type="text/javascript" src="../../shared/texteditor/SimpleTextEditor.js"></script>
    <script type="text/javascript">
    var ste = new SimpleTextEditor("gbook_text", "ste");
    ste.path = "../../shared/texteditor/";
    ste.cssFile = "../../shared/style.css";
    ste.init();
    </script>

    <script type="text/javascript" src="../../shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        ste.submit();
        var gbook_nick = form.elements["gbook_nick"];
        var gbook_text = form.elements["gbook_text"];
        gbook_nick.value = gbook_nick.value.trim();
        gbook_text.value = gbook_text.value.trim();
        if (!gbook_nick.value) { alert("nick is required"); return false; }
        if (!gbook_text.value) { alert("text is required"); return false; }
        return true;
    }
    </script>