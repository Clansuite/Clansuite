<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<link rel="stylesheet" type="text/css" href="{$css}">
<title>{translate}Please confirm!{/translate}</title>
</head>
<body>
<form action="{$link}" method="POST">

<table class="redirect" cellpadding="0" border="0" cellspacing="0">
<tr>
    <td>
    {$message}<br />
    <input type="submit" class="ButtonGrey" name="confirm" value="{translate}Confirm{/translate}">&nbsp;<input type="submit" class="ButtonGrey" name="abort" value="{translate}Abort{/translate}" />
    </td>
</tr>
</table>
</form>
</body>
</html>