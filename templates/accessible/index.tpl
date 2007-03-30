{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

{* Dublin Core Metatags *}
<meta name="DC.Title" content="Clansuite - just an eSport CMS" />
<meta name="DC.Creator" content="Florian Wolf, Jens-Andre Koch" />
<meta name="DC.Date" content="20070101" />
<meta name="DC.Identifier" content="http://www.clansuite.com/" />
<meta name="DC.Subject" content="Subject" />
<meta name="DC.Subject.Keyword " content="Subject.Keyword" />
<meta name="DC.Subject.Keyword" content="Subject.Keyword" />
<meta name="DC.Description" content="Description" />
<meta name="DC.Publisher" content="Publisher" />
<meta name="DC.Coverage" content="Coverage" />

<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_tpl_root}/images/favicon.ico" />
<link rel="icon" href="{$www_tpl_root}/images/animated_favicon.gif" type="image/gif" />

<link rel="stylesheet" type="text/css" href="{$css}" />
<script src="{$javascript}" type="text/javascript"></script>
{literal}
<!--[if lte IE 6]>
<style type="text/css">
table { width: 99%; }
</style>
<![endif]-->
{/literal}
{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
{/doc_raw}

<div id="header">
	<ul id="navigation">
		<li><a href="index.php?mod=news">News</a></li>
		<li><a href="index.php?mod=news&amp;action=archiv">Newsarchiv</a></li>
		<li><a href="index.php?mod=guestbook">Guestbook</a></li>
		<li><a href="index.php?mod=serverlist">Serverlist</a></li>
		<li><a href="index.php?mod=staticpages&amp;page=credits">Credits</a></li>
		<li><a href="index.php?mod=staticpages&amp;action=overview">Static Pages Overview</a></li>
	</ul>
</div>
{include file='tools/breadcrumbs.tpl'}
<div id="box">
	<div id="left">
		{mod name="account" func="login"}
	</div>
	<div id="right">
		{mod name="shoutbox" func="show"}
		<h3>{translate}Statistics{/translate}</h3>
		<ul id="counter">
			<li>
				<strong>Online:</strong>{* {$stats|@var_dump} *} {$stats.online}
				<ul>
					<li><strong>Users:</strong> {$stats.authed_users}</li>
					<li><strong>Guests:</strong> {$stats.guest_users}</li>
				</ul>
			</li>
			<li><strong>Today:</strong> {$stats.today_impressions}</li>
			<li><strong>Yesterday:</strong> {$stats.yesterday_impressions}</li>
			<li><strong>Month:</strong> {$stats.month_impressions}</li>
			<li><strong>This Page:</strong> {$stats.page_impressions}</li>
			<li><strong>Total Impressions:</strong> {$stats.all_impressions}</li>
		</ul>
	</div>
	<div id="content">
		{$content}
	</div>
</div>
<div id="footer">
<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
	{$copyright}<br />
	Theme: {* {$theme-copyright} *} | &nbsp;Queries: {$query_counter}
</div>
{* Ajax Notification *}
<div id="notification" style="margin-top:20px;vertical-align:middle;display:none">
    <img src="{$www_core_tpl_root}/images/ajax/2.gif" alt="Ajax Notification Image" />
    &nbsp; Wait - while processing your request...
</div>