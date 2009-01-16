{doctype doctype=XHTML level=Transitional}

{* display cache time as comment *}
<!--
    This Page was cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}.
 -->
<html>
<head>

    {* jQuery *}

    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.ui.js"></script>

    {* Mootools *}

    <script src="{$www_root_themes_core}/javascript/mootools/mootools.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}/javascript/mootools/mootools-more.js" type="text/javascript"></script>

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
    <meta name="keywords" content="{$meta.keywords}" />

     {* Favicon *}

    <link rel="shortcut icon" href="{$www_root_themes_core}/images/Clansuite-Favicon-16.ico" />
    <link rel="icon" href="{$www_root_themes_core}/images/Clansuite-Favicon-16.ico" type="image/gif" />

    {* Cascading Style Sheets *}
    
    <link rel="stylesheet" type="text/css" href="{$www_root_themes}/admin/admin.css" />

    {* Pagetitle *}
    <title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

</head>

<body>
<div class="header">
    <a href="index.php?mod=controlcenter"><img alt="Clansuite CMS Minilogo - 80x15px" style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/clansuite-80x15.png" border="0" /></a> - Control Center
    <span>{$smarty.now|date_format:"%e %B %Y - %A | %H:%M"}</span>
</div>

{include file="menu/templates/adminmenu.tpl"}

<table class="adminForm" cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin-top: 20px">
    <thead>
        <tr>
            <td class="admin_header">

                {include file="tools/breadcrumbs.tpl"}

                {literal}
                    <script type="text/javascript">
                    window.addEvent('domready', function() {
                        var mySlide = new Fx.Slide('help', {
                            duration: 500,
                            transition: Fx.Transitions.Pow.easeOut,
                            wait: false
                        });
                        mySlide.hide();
                        //alert(mySlide.open);

                        $('help-toggler').addEvent('click', function() {
                            mySlide.toggle('vertical');
                        });
                    }, 'javascript');

                    </script>
                {/literal}

                <div id="help-toggler" style="float: right; font-family: tahoma,verdana,arial,sans-serif; font-size: 11px; cursor: pointer;">
                    <img style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/icons/help.png" alt="Help Toggle" />
                    {t}Help{/t}
                </div>

            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="admin_content" width="100%">
                <div style="float: left; width: 100%;">
                    {$content}
                </div>
                <div style="position: absolute; float: right; right: 22px; margin-top: -9px;">
                    <div id="help" class="admin_help" style="float: right; z-index: 99;">
                          <p><strong>Help-Topics</strong>&nbsp;Lorem Ipsum</p>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>

<!-- Start Footer with Theme-Copyright and Server-Stats // -->
<div id="footer" class="admin_content_seperated" style="height: auto; margin-top: 10px; padding: 10px; clear:both;">
    {include file="tools/breadcrumbs.tpl"}
    <div style="font-size: 10px; text-align: center;">
    <br/>
    Frontpage-Theme: {$smarty.session.user.theme} {* by {$theme_copyright} *}
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