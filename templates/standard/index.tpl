<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<html>
<head>
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-language" content="{$language}">
<meta name="author" content="{$author}">
<meta http-equiv="reply-to" content="{$email}">
<meta name="description" content="{$description}">
<meta name="keywords" content="{$keywords}">
<meta name="creation-date" content="{$creation_date}">
<meta name="revisit-after" content="5 days">
<title>{$std_page_title} - {$mod_page_title}</title>
<link rel="stylesheet" type="text/css" href="{$css}">
{$additional_head}
<script src="{$javascript}" type="text/javascript" language="javascript"></script>
</head>
<body>
{* This calls the method "time" from the redistered module "index" and gives 2 parameters: "english" and "-" seperated by "|" *}
{mod name="index" func="time" params="english|-"}<br>
{$content}
<br><a href="http://www.clansuite.com"><span class="copyright">{$copyright}</span></a>
</body>
</html>