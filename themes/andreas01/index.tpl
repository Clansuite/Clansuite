<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="content-language" content="{$meta.language}">
<meta name="robots" content="index, follow" />
<meta name="author" content="{$meta.author}">
<meta http-equiv="reply-to" content="{$meta.email}">
<meta name="description" content="{$meta.description}">
<meta name="keywords" content="{$meta.keywords}">
<link rel="stylesheet" type="text/css" href="{$www_root_themes}/andreas01.css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="{$www_root_themes}/print.css" media="print" />
<link rel="stylesheet" type="text/css" href="{$css}">
<script src="{$javascript}" type="text/javascript" language="javascript"></script>
{$additional_head}
{$redirect}
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
</head>
<body>

<!-- BrowserCheck //-->
 <h2 class="oops">{t}
	You shouldn't be able to read this, because this site uses complex stylesheets to 
	display the information - your browser doesn't support these new standards. However, all 
	is not lost, you can upgrade your browser absolutely free, so please 
	
	UPGRADE NOW to a <a href="http://www.webstandards.org/upgrade/"  
	title="Download a browser that complies with Web standards.">
	standards-compliant browser</a>. If you decide against doing so, then 
	this and other similar sites will be lost to you. Remember...upgrading is free, and it 
	enhances your view of the Web.{/t}
</h2>

<div id="wrap">

<div class="header">
<h1>andreas01</h1>
<p><strong>"I can see you fly. You are an angel with wings, high above the ground!"</strong><br />(traditional haiku poem)</p>
</div>

<img id="frontphoto" src="{$www_root_themes}/images/front.jpg" width="760" height="175" alt="" />

<div id="avmenu">
<h2 class="hide">Menu:</h2>
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="index.php?mod=account">Account</a></li>
<li><a href="index.php?mod=admin">ACPs</a></li>
<li><a href="index.php?mod=downloads">Downloads</a></li>
<li><a href="index.php?mod=gallery">Gallery</a></li>
<li><a href="index.php?mod=gb">Guestbook</a></li>
<li><a href="index.php?mod=impressum">Impressum</a></li>
</ul>

<div class="announce">
<h3>Latest news:</h3>
<p><strong>Nov 28, 2005:</strong><br />
Updated to v1.3, fixed links.</p>
<p><strong>Oct 21, 2005:</strong><br />
Updated to v1.2, with minor adjustments and corrections.</p>
<p><strong>June 25, 2005:</strong><br />
v1.0 released on OSWD.org.</p>
<p class="textright"><a href="#">Read more...</a></p>
</div>

</div>

<div id="extras">

{mod name="account" func="login"}
		
{mod name="shoutbox" func="show"}

<h3>More info:</h3>
<p>This is the third column, which can be used in many different ways. For example, it can be used for comments, site news, external links, ads or for more navigation links. It is all up to you!</p>

<h3>Links:</h3>
<p>
- <a href="http://andreasviklund.com/templates">Free website templates</a><br />
- <a href="http://openwebdesign.org/">Open Web Design</a><br />
- <a href="http://oswd.org/">OSWD.org</a><br />
- <a href="http://validator.w3.org/check/referer">Valid XHTML</a><br />
- <a href="http://jigsaw.w3.org/css-validator/check/referer">Valid CSS</a>
</p>

<h3>Version:</h3>
<p>andreas01 v1.3</p>

<h3>Stats</h3>
Online: {$stats_online}<br />

Siteimpressions: {$stats_page_impressions}<br />

All Impressions: {$stats_all_impressions}<br />



</div>

<div class="content">
<h2>Welcome to andreas01!</h2>

<p>
{$content}
</p>
</div>

<div id="footer">
Copyright &copy; 2005 <a href="http://www.clansuite.com"><span class="copyright">{$copyright}</span></a>. Design by <a href="http://andreasviklund.com">Andreas Viklund</a>. Queries: {$query_counter}
</div>

</div>
</body>
</html>