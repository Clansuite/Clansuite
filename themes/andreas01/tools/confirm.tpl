<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<link rel="stylesheet" type="text/css" href="{$css}">
<title>{t}Please confirm!{/t}</title>
</head>
<body>
<form action="{$link}" method="post">
    <center>
    <div class='redirect'>
    {$message}<br />
    <input type="submit" class="ButtonGrey" name="confirm" value="{t}Confirm{/t}"><input type="submit" class="ButtonGrey" name="abort" value="{t}Abort{/t}">
    </div>
    <center>
</form>
</body>
</html>