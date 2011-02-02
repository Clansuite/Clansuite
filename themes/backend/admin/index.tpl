<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head>
    <title>{if isset($pagetitle)}{$pagetitle} - {/if}{breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

    {* Metatags *}

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />
    <meta name="author" content="{$meta.author}" />
    <meta name="description" content="{$meta.description}" />

    {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}images/clansuite_logos/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}images/clansuite_logos/Clansuite-Favicon-16.ico" type="image/gif" />

    <!-- This Page was processed on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}. -->

    <!-- jQuery -->
    <script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.js"></script>

    <!-- jQuery UI -->
    <script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.ui.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_root_themes}core/css/jquery/ui/pepper-grinder/jquery-ui-1.8.9.custom.css" />

    <!-- jQuery Pines Notify -->
    <script type="text/javascript" src="{$www_root_themes_core}javascript/jquery/jquery.pnotify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{$www_root_themes}core/css/jquery/pnotify/default.css" />

    {* Clansuite Cascading Style Sheets *}

    <link rel="stylesheet" type="text/css" href="{$www_root_themes_backend}admin/css/import.css" />
    {* <script type="text/javascript" src="{$www_root_themes_backend}admin/javascript/admin.js"></script> *}

    {* Clansuite Javascripts *}

    <script src="{$www_root_themes_core}javascript/clip.js" type="text/javascript"></script>

</head>
<body class="{$modulename} {$actionname}">

{* Header with Logo *}

<div class="header">
    <a href="{link_to href="controlcenter"}"><img alt="Clansuite CMS Minilogo - 80x15px" style="margin-bottom: -3px;" src="{$www_root_themes_core}images/clansuite_logos/clansuite-80x15.png" border="0" /></a> - Control Center
    <span>{$smarty.now|date_format:"%e %B %Y - %A | %H:%M"}</span>
</div>

{* Adminmenu Navigation *}
{include file="menu/view/smarty/adminmenu.tpl"}

{flashmessages}

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
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}images/icons/system-software-update.png" alt="{t}Show Updates{/t}" />
                    {t}Update{/t}
                </div>
                *}
                {*
                <!-- Tracking Icon -->
                <div id="tracking-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer; margin-right: 5px;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}images/icons/warning.png" alt="{t}The Support Tracking is active.{/t}" />
                    {t}Tracking{/t}
                </div>

                <!-- Bugreport Icon -->
                <div id="bugreport-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer; margin-right: 5px;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}images/icons/bug.png" alt="{t}Report Bug{/t}" />
                    {t}Bugreport{/t}
                </div>
                *}

                <!-- Debug Mode Icon -->
                {if $smarty.const.DEBUG}
                <div id="debug-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer; margin-right: 5px;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}images/icons/error.png" alt="DEBUG" title="Clansuite is in DEBUG MODE" />
                    DEBUG
                </div>
                {/if}

                <!-- Development Mode Icon -->
                {if $smarty.const.DEBUG and $smarty.const.DEVELOPMENT}
                <div id="development-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer; margin-right: 5px;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}images/icons/error.png" alt="DEVELOPMENT MODE ACTIVE" title="Clansuite is in DEVELOPMENT MODE (RAD)" />
                    DEVELOPMENT
                </div>
                {/if}
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
    Frontend-Theme: {$smarty.session.user.frontend_theme} {* by {$theme_copyright} *}
    <br/>
    Backend-Theme: {$smarty.session.user.backend_theme} {* by {$theme_copyright} *}
    <br/>
    {include file='server_stats.tpl'}
    </div>
    <div style="font-size: 10px; text-align: right;">
        <a href="#top">&uArr; {t} Nach oben{/t}</a> | <a href="{$www_root}controlcenter">&rArr; {t} Administration {/t}</a> | <a href="{$www_root}index.php">&rArr; {t}Show Frontpage{/t}</a>
    </div>
</div>

<!-- Start Copyright-Footer  // -->
<div id="footer2" class="admin_content_seperated" style="height: auto; margin-top: 10px; padding: 10px; clear:both;">
{include file='copyright.tpl'}
</div>

{* Ajax Notification *}
<div id="notification" style="vertical-align:middle;display:none;z-index:99;">
    <img src="{$www_root_themes_core}images/ajax/2.gif" alt="Ajax Notification Toggle" />
    &nbsp; Wait - while processing your request...
</div>

</body>
</html>