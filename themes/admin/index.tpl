<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>

    {* display cache time as comment *}

    <!-- This Page was cached on {$smarty.now|dateformat}. -->

    {* jQuery *}

    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.ui.js"></script>

    {* Clip *}

    <script src="{$www_root_themes_core}/javascript/clip.js" type="text/javascript"></script>

    {* Metatags *}

    <meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />

    <meta name="author" content="{$meta.author}" />
    <meta name="description" content="{$meta.description}" />

    {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}/images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    {* Cascading Style Sheets *}

    <link rel="stylesheet" type="text/css" href="{$www_root_themes}/admin/admin.css" />

    {* Pagetitle *}

    <title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

</head><body>

{* Header with Logo *}

<div class="header">
    <a href="index.php?mod=controlcenter"><img alt="Clansuite CMS Minilogo - 80x15px" style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/clansuite_logos/clansuite-80x15.png" border="0" /></a> - Control Center
    <span>{$smarty.now|date_format:"%e %B %Y - %A | %H:%M"}</span>
</div>

{* Adminmenu Navigation *}
{include file="menu/templates/adminmenu.tpl"}

{* Main Table *}

<table class="adminForm" cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin-top: 20px">

    {* Breadcrumb Navigation and Help *}

    <thead>
        <tr>
            <td class="admin_header">
                {include file='breadcrumbs.tpl'}
                {include file='help_button.tpl'}

                {*

                <!-- Update Icon -->
                <div id="update-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer; margin-right: 5px;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/icons/warning.png" alt="Updates" />
                    {t}Update{/t}
                </div>

                <!-- Bugreport Icon -->
                <div id="bugreport-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer; margin-right: 5px;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/icons/error.png" alt="Updates" />
                    {t}Bugreport{/t}
                </div>

                *}

            </td>
        </tr>
    </thead>

    {* Content *}

    <tbody>
        <tr>
            <td class="admin_content">
                <!-- Maincontent -->
                {$content}
            </td>
        </tr>
    </tbody>
</table>

<!-- Start Footer with Theme-Copyright and Server-Stats // -->
<div id="footer" class="admin_content_seperated" style="height: auto; margin-top: 10px; padding: 10px; clear:both;">
    {include file='breadcrumbs.tpl'}
    <div style="font-size: 10px; text-align: center;">
    <br/>
    Frontend-Theme: {$smarty.session.user.theme} {* by {$theme_copyright} *}
    <br/>
    Backend-Theme: {$smarty.session.user.backendtheme} {* by {$theme_copyright} *}
    <br/>
    {include file='server_stats.tpl'}
    </div>
    <div style="font-size: 10px; text-align: right;">
        <a href="#top">&uArr; {t} Nach oben{/t}</a> | <a href="index.php?mod=controlcenter">&rArr; {t} Administration {/t}</a> | <a href="index.php">&rArr; {t}Show Frontpage{/t}</a>
    </div>
</div>

<!-- Start Copyright-Footer  // -->
<div id="footer" class="admin_content_seperated" style="height: auto; margin-top: 10px; padding: 10px; clear:both;">
{include file='copyright.tpl'}
</div>

{* Ajax Notification *}
<div id="notification" style="vertical-align:middle;display:none;z-index:99;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" alt="Ajax Notification Toggle" />
    &nbsp; Wait - while processing your request...
</div>

</body>
</html>