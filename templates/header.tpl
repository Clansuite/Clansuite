{php} global $_CONFIG  {/php}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta http-equiv="Content-Language" content="en-us" />
	 <meta name="robots" content="all" />
	 <meta name="rating" content="general" />
    <meta name="revisit-after" content="1 day" />
	 <meta name="doc-class" content="living document" />
	 <meta http-equiv="imagetoolbar" content="false" />
	 <meta name="MSSmartTagsPreventParsing" content="true" />
	 <meta name="description" content="clansuite" />
	 <meta name="keywords" content="clansuite" />
	 <meta name="author" content="Jens-Andre Koch" />
	 <meta name="copyright" content="Copyright (c) 1999- 2005 Jens-Andre Koch" />
	 <meta name="ICBM" content="40.428618, -86.913786" />
	 <meta name="DC.title" content="clansuite.knd-squad.de | Clansuite - the e-Sport CMS." />
	 <link rel="Shortcut Icon" href="/favicon.ico" type="image/x-icon" />
	 {* todo: verfeinern nach umstellung von smartertemplate *}
	 <title>{if isset($pagetitle)} $pagetitle {else} {php} echo $_CONFIG['version'] {/php} {/if}
	 </title>
  
	<link rel="stylesheet" type="text/css" href="{php} echo WWW_ROOT.'/shared/style.css'; {/php}" />
</head>
<body topmargin="0" leftmargin="0">
 
<!-- BrowserCheck //-->
 <h2 class="oops">
	You shouldn't be able to read this, because this site uses complex stylesheets to 
	display the information - your browser doesn't support these new standards. However, all 
	is not lost, you can upgrade your browser absolutely free, so please 
	
	UPGRADE NOW to a <a href="http://www.webstandards.org/upgrade/"  
	title="Download a browser that complies with Web standards.">
	standards-compliant browser</a>. If you decide against doing so, then 
	this and other similar sites will be lost to you. Remember...upgrading is free, and it 
	enhances your view of the Web.
</h2>
 
 
 <!-- Main-Container //-->
 <div id="frame" >
 	
 	<!-- start&end: contentheader - banner //-->
	<div id="contentheader"><font color="#c0c0c0" size="7">{php} echo $_CONFIG['version']; {/php}</font></div>
	
	<!-- start: Menu //-->
    <div id="header">
	<a href="{php} echo WWW_ROOT.'/module/news/news.php'; {/php}">News</a> |
	<a href="{php} echo WWW_ROOT.'/module/downloads/downloads.php'; {/php}">Downloads</a> |
	<a href="{php} echo WWW_ROOT.'/module/awards/awards.php'; {/php}">Awards</a> |
	<a href="{php} echo WWW_ROOT.'/module/guestbook/guestbook.php'; {/php}">Guestbook</a> |
	<a href="{php} echo WWW_ROOT.'/module/stats/stats.php'; {/php}">Stats</a> |
	
	{* start if authed *}
	{if isset($authed) and $authed == '1'  }
   
   	{if isset($usergroup) and $usergroup == 'ADMIN'}
   	<a href="{php} echo WWW_ROOT.'/admin/index.php'; {/php}">Admin</a> |
   	{/if}
   	 	
	<a class="account"  href="{php} echo WWW_ROOT.'/users/myaccount.php'; {/php}">My Account</a> |
	<a class="account" href="{php} echo WWW_ROOT.'/users/logout.php'; {/php}">Logout (
	{* unlogisch, weil immer username wenn eingeloggt ... anyway*}
	{if isset($username)} $username	{/if} ) </a>
	
	{else}
	
	<a class="account" href="{php} echo WWW_ROOT.'/users/register.php'; {/php}">Register</a> | 
	<a class="account" href="{php} echo WWW_ROOT.'/users/login.php';{/php}">Login</a>
	
	{/if}
	{* end if authed *}
	
	| {$smarty.now|date_format:"%A, %B %e, %Y"} @ {$smarty.now|date_format:"%H:%M:%S"} 
    </div>
    <!-- End of : Menu- Kopfzeile 2 //-->
 	
 	{include file="left.tpl"}
  	
 	<!-- Hauptseite Mitte Start //-->
    <div id="contentcenter">
    <!-- End of : Header.tpl //-->