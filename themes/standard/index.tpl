{doc_info DOCTYPE=XHTML LEVEL=Transitional}
<base href="{$meta.domain}" />

{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

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

<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />

<link rel="shortcut icon" href="{$www_root_theme}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_theme}/images/Clansuite-Favicon-16.ico" type="image/gif" />
<link rel="stylesheet" type="text/css" href="{$css}" />

<script src="{$javascript}" language="javascript" type="text/javascript"></script>
<script src="{$www_root_themes_core}/javascript/clip.js" type="text/javascript"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools.js"></script>
<script type="text/javascript" src="{$www_root_themes_core}/javascript/mootools/mootools-more.js"></script>

<!--[if IE]>
<link rel="stylesheet" href="{$www_root_themes_core}/css/IEhack.css" type="text/css" />
<script type="application/javascript" src="{$www_root_themes_core}/javascript/catfish.js">
<![endif]-->

{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}

{* BrowserCheck *}
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

{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" style="vertical-align: middle;" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>

{* IE FIX! *}
<script language="JavaScript" type="text/javascript"></script>
{* Header Table *}
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="180" align="center">
        <img alt="Clansuite Header" src="{$www_root_theme}/images/clansuite-header.png" width="760" height="175" />
    </td>
</tr>
</table>

<!-- Main Table //-->
<table cellspacing="0" cellpadding="0" width="100%">

<!-- TableHeader + Breadcrumbs //-->
<tr class="tr_header">
    <td width="200">Left widget bar</td>
    <td>{include file='tools/breadcrumbs.tpl'}</td>
    <td width="200">Right widget bar</td>
</tr>

<!-- Middle/Center Part of Table //-->
<tr>
    <!-- Left Widget Bar //-->
    <td id="left_widget_bar" class="cell1">
        <span class="widget" id="widget_menu">{load_module name="menu"     action="widget_menu"}</span>
        <span class="widget" id="widget_news">{load_module name="news"      action="widget_news" items="2"}</span>
        <span class="widget" id="widget_gallery">{load_module name="gallery"  action="widget_gallery"}</span>
    </td>

    <!-- Middle + Center = Main Content //-->
    <td class="cell1" width="99%">
        {$content}
    </td>

    <!-- Right Widget Bar //-->
    <td id="right_widget_bar" class="cell1">
        <span class="widget" id="widget_tsviewer">{load_module name="tsviewer" action="widget_tsviewer"}</span>
    </td>
</tr>
<tr>
    <!-- Bottom Widget Bar //-->
    <td id="bottom_widget_bar" class="cell1" width="100%" colspan="3" align="center" valign="top">
        <span class="widget" id="widget_quotes">{load_module name="quotes"    action="widget_quotes"}</span>
        <span class="widget" id="widget_users">{load_module name="users"     action="widget_lastregisteredusers"}</span>
        <span class="widget" id="widget_wwwstats">{load_module name="wwwstats"  action="widget_wwwstats"}</span>
    </td>
</tr>
</table>

<!-- Footer with Copyright and Theme-Copyright //-->
<p style="float:left; text-align:left;">
    <br/> Theme: {$smarty.session.user.theme} by {* {$theme_copyright} *}
</p>
<p style="text-align:right;">
    <br /> {include file='server_stats.tpl'}
</p>

{$copyright}