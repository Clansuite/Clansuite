<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

    {* display cache time as comment *}
    <!-- This Page was cached on {$smarty.now|dateformat}. -->

    {* Include the Clansuite Header Notice *}
    {include file='clansuite_header_notice.tpl'}

    {* Pagetitle *}
    <title>{$pagetitle} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

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

</head><body>

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
<script type="text/javascript"></script>
{* Header Table *}
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="180" align="center">
        <img alt="Clansuite Header" src="{$www_root_themes_core}/images/clansuite-header.png" width="760" height="175" />
    </td>
</tr>
</table>

<!-- Main Table -->
<table cellspacing="0" cellpadding="0" width="100%">

<!-- TableHeader + Breadcrumbs -->
<tr class="tr_header">
    <td colspan="3">{include file='breadcrumbs.tpl'}</td>
</tr>

<!-- Middle/Center Part of Table -->
<tr>
    <!-- Left Widget Bar -->
    <td id="left_widget_bar" class="cell1">
        <div class="widget" id="widget_menu">{load_module name="menu" action="widget_menu"}</div>
        <div class="widget" id="widget_latestnews">{load_module name="news" action="widget_latestnews"}</div>  
        <div class="widget" id="widget_newscategories_list">{load_module name="news" action="widget_newscategories_list"}</div>
        <div class="widget" id="widget_newscategories_dropdown">{load_module name="news" action="widget_newscategories_dropdown"}</div>
        <div class="widget" id="widget_newsfeeds">{load_module name="news" action="widget_newsfeeds"}</div>    
        <div class="widget" id="widget_newsarchive">{load_module name="news" action="widget_archive"}</div>
        <div class="widget" id="widget_gallery">{load_module name="gallery" action="widget_gallery"}</div>
        <div class="widget" id="widget_nextmatches">{load_module name="matches" action="widget_nextmatches" items="3"}</div>
        <div class="widget" id="widget_latestmatches">{load_module name="matches" action="widget_latestmatches" items="3"}</div>
        <div class="widget" id="widget_topmatch">{load_module name="matches" action="widget_topmatch"}</div>
        <div class="widget" id="widget_shoutbox">{load_module name="shoutbox" action="widget_shoutbox"}</div>        
    </td>

    <!-- Middle + Center = Main Content -->
    <td class="cell1" width="99%">
        {$content}
    </td>

    <!-- Right Widget Bar -->
    <td id="right_widget_bar" class="cell1">
   {* {if isset($smarty.session.user.user_id) && $smarty.session.user.user_id == 0 &&
          isset($smarty.session.user.authed) && $smarty.session.user.authed == 1 } *}
        <div class="widget" id="widget_login">{load_module name="account" action="widget_login"}</div>
    {*{else}
        <div class="widget" id="widget_usercenter">{load_module name="user" action="widget_usercenter"}</div>
    {/if} *}

        <div class="widget" id="widget_tsviewer">{load_module name="teamspeakviewer" action="widget_tsviewer"}</div>
        <div class="widget" id="widget_tsministatus">{load_module name="teamspeakviewer" action="widget_tsministatus"}</div>
        <div class="widget" id="widget_shockvoiceviewer">{load_module name="shockvoiceviewer" action="widget_shockvoiceviewer"}</div>
    </td>
</tr>
<tr>
    <!-- Bottom Widget Bar -->
    <td id="bottom_widget_bar" class="cell1" width="100%" colspan="3" align="center" valign="top">
        <div class="widget" id="widget_quotes">{load_module name="quotes" action="widget_quotes"}</div>
        <div class="widget" id="widget_lastregistered">{load_module name="users" action="widget_lastregisteredusers"}</div>
        <div class="widget" id="widget_randomuser">{load_module name="users" action="widget_randomuser"}</div>
        <div class="widget" id="widget_usersonline">{load_module name="users" action="widget_usersonline"}</div>
        <div class="widget" id="widget_stats">{load_module name="statistics" action="widget_statistics"}</div>
    </td>
</tr>
</table>

<!-- Footer with Copyright and Theme-Copyright -->
<p style="float:left; text-align:left;">
    <br/> Theme: {$smarty.session.user.theme} by {* {$theme_copyright} *}
</p>

<p style="text-align:right;">
    <br /> {include file='server_stats.tpl'}
</p>

{include file='copyright.tpl'}

{* debug *}

</body>
</html>