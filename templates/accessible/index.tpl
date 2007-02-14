{doc_info DOCTYPE=XHTML1.1 LEVEL=Normal}
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
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_tpl_root}/images/favicon.ico" />
<link rel="icon" href="{$www_tpl_root}/images/animated_favicon.gif" type="image/gif" />

<link rel="stylesheet" type="text/css" href="{$css}" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/XulMenu.js"></script>
<script src="{$javascript}" type="text/javascript"></script>

<!--[if IE]>
<link rel="stylesheet" href="{$www_core_tpl_root}/css/IEhack.css" type="text/css" />
<script type="text/javascript" src="{$www_core_tpl_root}/javascript/catfish.js">
<![endif]-->

{if $additional_head} {$additional_head} {/if}
{$redirect}
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->
{/doc_raw}

<div id="header"></div>
<script type="text/javscript">
    var arrow1 = new Image(4, 7);
    arrow1.src = "{$www_tpl_root}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src = "{$www_tpl_root}/images/arrow2.gif";
</script>

<div id="box">
	<div id="left">
		<h3>Menu</h3>
		<div id="menu1" class="XulMenu">
			<a class="button" href="javascript:void(0)">
				Public
				<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" alt="" />
			</a>
			<div class="section">
				<a class="item" href="javascript:void(0)">
					<img class="pic" src="{$www_tpl_root}/images/icons/modules.png" alt="" />
					Modules
					<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" alt="" />
				</a>
				<div class="section">
					<a class="item" href="index.php">Main</a>
					<a class="item" href="index.php?mod=news">
						<img class="pic" src="{$www_tpl_root}/images/icons/news.png" alt="" />
						News
					</a>
					<a class="item" href="index.php?mod=news&amp;action=archiv">
						<img class="pic" src="{$www_tpl_root}/images/icons/news.png" alt="" />
						Newsarchiv
					</a>
					<a class="item" href="index.php?mod=serverlist">
						<img class="pic" src="{$www_tpl_root}/images/icons/serverlist.png" alt="" />
						Serverlist
					</a>
					<a class="item" href="index.php?mod=static&amp;page=credits">
						<img class="pic" src="{$www_tpl_root}/images/icons/news.png" alt="" />
						Credits
					</a>
					<a class="item" href="index.php?mod=static&amp;action=overview">
						<img class="pic" src="{$www_tpl_root}/images/icons/news.png" alt="" />
						Static Pages Overview
					</a>
				</div>
				<a class="item" href="index.php?mod=users">
					<img class="pic" src="{$www_tpl_root}/images/icons/users.png" alt="" />
					Users
					<img class="arrow" src="{$www_tpl_root}/images/arrow1.gif" alt="" />
				</a>
				<div class="section">
					<a class="item" href="index.php?mod=account">Login</a>
					<a class="item" href="index.php?mod=account">
						<img class="pic" src="{$www_tpl_root}/images/icons/logout.png" alt="" />
						Logout
					</a>
				</div>
			</div>
			<a class="button" href="index.php?mod=admin">Admin</a>
		</div>
	</div>
	<div id="right">
		<h3>Infos</h3>
		{mod name="account" func="login"}
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
		{include file='breadcrumbs.tpl'}
		{$content}
	</div>
</div>
<div id="footer">
	{$copyright}
	Queries: {$query_counter}
	<br />
	<img src="{$www_tpl_root}/images/clansuite-80x15.png" alt="Copyright Clansuite." />
</div>
<script type="text/javascript">
    var menu1 = new XulMenu("menu1");
    menu1.type = "vertical";
    menu1.position.level1.top = 0;
    menu1.arrow1 = "{$www_tpl_root}/images/arrow1.gif";
    menu1.arrow2 = "{$www_tpl_root}/images/arrow2.gif";
    menu1.init();
</script>
{* Ajax Notification *}
<div id="notification" style="margin-top:20px;vertical-align:middle;display:none">
    <img src="{$www_core_tpl_root}/images/ajax/2.gif" alt="Ajax Notification Image" />
    &nbsp; Wait - while processing your request...
</div>
</body>
</html>