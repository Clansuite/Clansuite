{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

{* Dublin Core Metatags *}
<link rel="schema.DC". href="http://purl.org/dc/elements/1.1/" />
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
<script src="{$javascript}" type="text/javascript" language="javascript"></script>
{if isset($additional_head)} {$additional_head} {/if}
{if isset($redirect)} {$redirect} {/if}

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}

<!-- BrowserCheck // -->
 <h2 class="oops">{translate}
	You shouldn't be able to read this, because this site uses complex stylesheets to
	display the information - your browser doesn't support these new standards. However, all
	is not lost, you can upgrade your browser absolutely free, so please

	UPGRADE NOW to a <a href="http://www.webstandards.org/upgrade/"
	title="Download a browser that complies with Web standards.">
	standards-compliant browser</a>. If you decide against doing so, then
	this and other similar sites will be lost to you. Remember...upgrading is free, and it
	enhances your view of the Web.{/translate}
</h2>

<div id="container">

<!-- div:title // -->
<div id="header">
<img alt="Clansuite Header" src="{$www_tpl_root}/images/clansuite-header.png" />
</div>

<!-- div:middle open // -->
<div id="middle">

    <!-- div:inner open // -->
    <div id="inner">

        <!-- div:left open // -->
        <div id="left">
        <p>Menü</p>

            <dl>
                <dt>Main</dt>
                <dd><a href="index.php">Main</a></dd>
                <dt>Modules</dt>
                <dd><a href="index.php?mod=news"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16" alt=""/>News</a></dd>
                <dd><a href="index.php?mod=news&amp;action=archiv"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Newsarchiv</a></dd>
                <dd><a href="index.php?mod=serverlist"><img class="pic" src="{$www_tpl_root}/images/icons/serverlist.png" border="0" width="16" height="16" alt=""/>Serverlist</a></dd>
                <dd><a href="index.php?mod=staticpages&amp;page=credits"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16" alt=""/>Credits</a></dd>
                <dd><a href="index.php?mod=staticpages&amp;action=overview"><img class="pic" src="{$www_tpl_root}/images/icons/news.png" border="0" width="16" height="16" alt="" />Static Pages Overview</a></dd>
                <dt>Users</dt>
                <dd><a class="item" href="index.php?mod=account">Login</a></dd>
                <dd><a class="item" href="index.php?mod=account"><img class="pic" src="{$www_tpl_root}/images/icons/logout.png" border="0" width="16" height="16" alt=""/>Logout</a></dd>
                <dt>ACP</dt>
                <dd><a class="button" href="index.php?mod=admin">Admin</a></dd>
            </dl>
        </div>
        <!-- div:left close // -->

          <!-- div:right // -->
          <div id="right">
          <p>Menü</p>

          <div style="margin-top: 10px">{mod name="account" func="login"}</div>
          <div style="margin-top: 10px">{mod name="shoutbox" func="show"}</div>
          <div style="margin-top: 10px">
                {translate}Statistics{/translate}
                    {* {$stats|@var_dump} *}
                  Online: {$stats.online} <br/>
                  - Users : {$stats.authed_users}
                  - Guests : {$stats.guest_users} <br/>
                  Today: {$stats.today_impressions} <br/>
                  Yesterday: {$stats.yesterday_impressions} <br/>
                  Month: {$stats.month_impressions} <br/>

                  This Page: {$stats.page_impressions} <br/>
                  Total Impressions: {$stats.all_impressions} <br/>
          </div>
        </div>
        <!-- div:right close // -->

<p>
{include file='breadcrumbs.tpl'}
<br />
{$content}
</p>

    </div><!-- div:inner close // -->
</div><!-- div:middle close // -->

<!-- style clearer // -->
<div class="clearer"></div>

<!-- div:footer open // -->
<div id="footer">
    <!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
    <hr/>
    <p style="text-align:center;clear:both;margin-top:20px;" class="copyright">
                {$copyright}
                <br/> Theme: {* {$theme-copyright} *}
                <br/> {include file='server_stats.tpl'}
    </p>
</div><!-- div:footer close // -->

</div><!-- div:container close // -->

{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_core_tpl_root}/images/ajax/2.gif" align="middle" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>

