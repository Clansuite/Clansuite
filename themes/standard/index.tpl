{doc_info DOCTYPE=XHTML LEVEL=Transitional}
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

<link rel="shortcut icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" type="image/gif" />
<link rel="stylesheet" type="text/css" href="{$css}" />
<script  src="{$www_root_themes_core}/javascript/XulMenu.js" type="application/javascript"></script>
<script src="{$javascript}" language="javascript" type="application/javascript"></script>
<script src="{$www_root_themes_core}/javascript/clip.js" type="application/javascript"></script>

<!--[if IE]>
<link rel="stylesheet" href="{$www_root_themes_core}/css/IEhack.css" type="text/css" />
<script type="application/javascript" src="{$www_root_themes_core}/javascript/catfish.js">
<![endif]-->

{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}

<!-- BrowserCheck // -->
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

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td height="180" align="center">
        <img alt="Clansuite Header" src="{$www_root_themes}/images/clansuite-header.png" />
    </td>
</tr>
</table>
<script type="application/javscript">
//<![CDATA[
    var arrow1 = new Image(4, 7);
    arrow1.src = "{$www_root_themes}/images/arrow1.gif";
    var arrow2 = new Image(4, 7);
    arrow2.src = "{$www_root_themes}/images/arrow2.gif";
//]]>
</script>

<table cellspacing="0" cellpadding="0" width="100%">
<tr class="tr_header">
    <td width="10">Menu</td>
    <td>{include file='tools/breadcrumbs.tpl'}</td>
    <td width="200">Infos</td>
</tr>

<tr>
    <td class="cell1" width="100" height="300">
        <div class="left_menu">

            <table id="menu1" cellspacing="0" cellpadding="0" class="XulMenu">
            <tr>
                <td>

                    <a class="button" href="javascript:void(0)">Public<img class="arrow" src="{$www_root_themes}/images/arrow1.gif" width="4" height="7" alt="" /></a>

                    <div class="section">
                        <a class="item" href="javascript:void(0)"><img class="pic" src="{$www_root_themes}/images/icons/modules.png" border="0" width="16" height="16" alt="" />Modules<img class="arrow" src="{$www_root_themes}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php">Main</a>
                              <a class="item" href="index.php?mod=news"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt=""/>News</a>
                              <a class="item" href="index.php?mod=news&amp;action=archiv"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Newsarchiv</a>
                              <a class="item" href="index.php?mod=guestbook"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Guestbook</a>
                              <a class="item" href="index.php?mod=board"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Board</a>
                              <a class="item" href="index.php?mod=serverlist"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Serverlist</a>
                              <a class="item" href="index.php?mod=userslist">Userslist</a>
                              <a class="item" href="index.php?mod=staticpages&amp;page=credits"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Credits</a>
                              <a class="item" href="index.php?mod=staticpages&amp;action=overview"><img class="pic" src="{$www_root_themes}/images/icons/news.png" border="0" width="16" height="16" alt="" />Static Pages Overview</a>
                           </div>

                        <a class="item" href="index.php?mod=users"><img class="pic" src="{$www_root_themes}/images/icons/users.png" border="0" width="16" height="16" alt=""/>Users<img class="arrow" src="{$www_root_themes}/images/arrow1.gif" width="4" height="7" alt="" /></a>
                          <div class="section">
                              <a class="item" href="index.php?mod=account">Login</a>
                              <a class="item" href="index.php?mod=account"><img class="pic" src="{$www_root_themes}/images/icons/logout.png" border="0" width="16" height="16" alt=""/>Logout</a>
                          </div>
                    </div>

                    <a class="button" href="index.php?mod=admin">Admin</a>

                </td>
            </tr>
            </table>

        </div>
    </td>

    <td class="cell1">
                        {$content}
    </td>

    <td class="cell1" style="padding: 0px;">
        <div style="margin-top: 10px">
           {* {mod name="account" func="login"} *}
        </div>
        <div style="margin-top: 10px">
		   {* {mod name="shoutbox" func="show"} *}
		   {load_module name="news" action="widget_news" params="2"}
		</div>
        <div style="margin-top: 10px">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
                <tr>
                    <td class="td_header" colspan="2">{t}Statistics{/t}</td>
                </tr>
                <tr> {* {$stats|@var_dump} *}
                    <td class="cell1">Online: {$stats.online} <br/>
                                      - Users : {$stats.authed_users}
                                      - Guests : {$stats.guest_users} <br/>
                                      Today: {$stats.today_impressions} <br/>
                                      Yesterday: {$stats.yesterday_impressions} <br/>
                                      Month: {$stats.month_impressions} <br/>
                                      <hr />
                                      This Page: {$stats.page_impressions} <br/>
                                      Total Impressions: {$stats.all_impressions} <br/>
                    </td>
                </tr>
          </table>
        </div>
    </td>
</tr>
</table>

<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
<p style="text-align:center;clear:both;margin-top:20px;" class="copyright">
                {$copyright}
                <br/> Theme: {$smarty.session.user.theme} by {* {$theme_copyright} *}
                <br/> {include file='server_stats.tpl'}
    </p>

<script type="application/javascript">
//<![CDATA[
    var menu1 = new XulMenu("menu1");
    menu1.type = "vertical";
    menu1.position.level1.top = 0;
    menu1.arrow1 = "{$www_root_themes}/images/arrow1.gif";
    menu1.arrow2 = "{$www_root_themes}/images/arrow2.gif";
    menu1.init();
//]]>
</script>

{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" style="vertical-align: middle;" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>