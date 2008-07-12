{doc_info DOCTYPE=XHTML LEVEL=Transitional}

{* everything in doc_raw is moved "as is" to header *}

{doc_raw}
    {* jQuery *}
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.js"></script>
    <script type="text/javascript" src="{$www_root_themes_core}/javascript/jquery/jquery.ui.js"></script>

    {* Mootools *}
    <script src='{$www_root_themes_core}/javascript/mootools/mootools.js' type='text/javascript'></script>
    <script src='{$www_root_themes_core}/javascript/mootools/mootools-more.js' type='text/javascript'></script>
    
    <meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />

    <meta name="author" content="{$meta.author}" />
    <meta name="description" content="{$meta.description}" />
    <meta name="keywords" content="{$meta.keywords}" />

    <link rel="shortcut icon" href="{$www_root_themes_core}/images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/admin/admin.css" />

    {* <script src="{$www_root_themes_core}/javascript/clip.js" type="application/javascript"></script> *}

    {if isset($additional_head)}{$additional_head}{/if}
    {if isset($redirect)}{$redirect}{/if}
    <title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

    <!--
    page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
    -->

{/doc_raw}
<a accesskey="t" name="top"/>
<div class="header">
    <a href="index.php?mod=admin"><img alt="Clansuite CMS Minilogo - 80x15px" style="margin-bottom: -3px;" src="{$www_root_themes_core}/images/clansuite-80x15.png" border="0" /></a> - Control Center
    <span>{$smarty.now|date_format:"%e %B %Y - %A | %H:%M"}</span>
</div>
{include file="admin/adminmenu/adminmenu.tpl"}
<table cellpadding="0" cellspacing="0" border="0" style="width: 100%; margin-top: 20px">
    <thead>
        <tr>
            <td class="admin_header">

                <div style="float: left">
                    {breadcrumbs heading="1" trail=$trail separator=" &raquo; " length=30}
                </div>
                {literal}
                <script type="text/javascript">
                window.addEvent('domready', function() {
                    var mySlide = new Fx.Slide('help', {
                        duration: 1000,
                        transition: Fx.Transitions.Pow.easeOut
                    });
                    mySlide.hide();
                    //alert(mySlide.open);

                    $('help-toggler').addEvent('click', function() {
                        mySlide.toggle('vertical');
                    });
                }, 'javascript');
                
                </script>
                {/literal}
                <div id="help-toggler" style="float: right; font-size: 10px;cursor: pointer;">
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
                    <div id="help" class="admin_help" style="float: right;">
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>
                          bla  WTFFFFFFFF<p>&nbsp;</p>

                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<div id="footer" class="admin_content">
<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
     {$copyright}
    <br/> Frontpage-Theme: {$smarty.session.user.theme} by {* {$theme_copyright} *}
    <br/> {include file='server_stats.tpl'}
    <div style="text-align: right;"><a href="#top">&uArr; {t} Nach oben{/t}</a> | <a href="index.php">&rArr; {t}Show Frontpage{/t}</a></div>
</div>

{* Ajax Notification *}
<div id="notification" style="vertical-align:middle;display:none;z-index:99;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" alt="Ajax Notification Toggle" />
    &nbsp; Wait - while processing your request...
</div>