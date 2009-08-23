<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

    {* display cache time as comment *}
    <!-- This Page was cached on {$smarty.now|dateformat}. -->

    {* Include the Clansuite Header Notice *}
    {include file='clansuite_header_notice.tpl'}

    {* Pagetitle *}
    <title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

    {* Dublin Core Metatags *}

    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
    <meta name="DC.Title" content="Clansuite - just an eSport CMS" />
    <meta name="DC.Creator" content="Jens-Andre Koch" />
    <meta name="DC.Date" content="20080101" />
    <meta name="DC.Identifier" content="http://www.clansuite.com/" />
    <meta name="DC.Subject" content="Subject" />
    <meta name="DC.Subject.Keyword " content="Subject.Keyword" />
    <meta name="DC.Subject.Keyword" content="Subject.Keyword" />
    <meta name="DC.Description" content="Description" />
    <meta name="DC.Publisher" content="Publisher" />
    <meta name="DC.Coverage" content="Coverage" />

    {* Metatags *}

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta name="author" content="{$meta.author}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />
    <meta name="description" content="{$meta.description}" />
    <meta name="keywords" content="{$meta.keywords}" />
    <meta name="generator" content="Clansuite - just an eSports CMS" />

    {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    {* Clip *}

    <script src="{$www_root_themes_core}/javascript/clip.js" type="text/javascript"></script>


    {* Cascading Style Sheets *}

    <link rel="stylesheet" type="text/css" href="{$css}" />
    <link rel="alternate"  type="application/rss+xml" href="{$www_root}/cache/photo.rss" title="" id="gallery" />

</head>

<body>

	<div id="header">
    	<div id="logowrapp">
        	<img class="logo" src="{$www_root_theme}/images/cs_logo.gif" />
        </div>
	</div>
    <div id="navigation">
    	<div class="center">
    	<ul>
        	<li><a class="navlink" href="{$www_root}/index.php">Home</a></li>
        	<li><a class="navlink" href="{$www_root}/index.php?mod=news">News</a></li>
            <li><a class="navlink" href="{$www_root}/index.php?mod=newsarchiv">Newsarchiv</a></li>
		</ul>
    	</div>
    </div>
    <div class="center">
    	<div id="left">
			{* {if isset($smarty.session.user.user_id) && $smarty.session.user.user_id == 0 &&
        	isset($smarty.session.user.authed) && $smarty.session.user.authed == 1 } *}
        	<div class="widget" id="widget_login">{load_module name="account" action="widget_login"}</div>
    		{*{else}
        	<div class="widget" id="widget_usercenter">{load_module name="user" action="widget_usercenter"}</div>
    		{/if} *}
        
        	<div class="widget" id="widget_news">{load_module name="news" action="widget_news" items="2"}</div>
        	<div class="widget" id="widget_categories">{load_module name="news" action="widget_newscats"}</div>
    	</div>
    	<div id="main">{$content}</div>
	</div>
    <div id="foot">{include file='copyright.tpl'}</div>

</body>
</html>
