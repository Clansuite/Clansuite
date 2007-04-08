{doc_info DOCTYPE=XHTML LEVEL=Transitional}

{* everything in doc_raw is moved "as is" to header *}
{doc_raw}
    {* Prototype + Scriptaculous + Smarty_Ajax *}
    <script type="text/javascript" src="{$www_root_tpl_core}/javascript/prototype/prototype.js" ></script>
    <script type="text/javascript" src="{$www_root_tpl_core}/javascript/scriptaculous/scriptaculous.js"></script>
    <script type="text/javascript" src="{$www_root_tpl_core}/javascript/smarty_ajax.js"></script>

    <meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="content-language" content="{$meta.language}" />
    <meta http-equiv="reply-to" content="{$meta.email}" />

    <meta name="author" content="{$meta.author}" />
    <meta name="description" content="{$meta.description}" />
    <meta name="keywords" content="{$meta.keywords}" />

    <link rel="shortcut icon" href="{$www_root_tpl_core}/images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="{$www_root_tpl_core}/admin/admin.css" />

    {if isset($additional_head)}{$additional_head}{/if}
    {$redirect}
    <title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

    <!--
    page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
    -->

{/doc_raw}
    <div class="header">
        <a href="index.php?mod=admin"><img alt="Clansuite CMS Minilogo - 80x15px" style="margin-bottom: -3px;" src="{$www_root_tpl_core}/images/clansuite-80x15.png" border="0" /></a> - Control Center
        <span>{$smarty.now|date_format:"%e %B %Y - %A | %H:%M"}</span>
    </div>
    {include file="admin/adminmenu/adminmenu.tpl"}
    <p>&nbsp;</p>
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <td class="admin_header">
                <div style="float: left">
                    {breadcrumbs heading="1" trail=$trail separator=" &raquo; " length=30}
                </div>
                {literal}
                    <script type="text/javascript">
                        function help_toggler()
                        {

                            if ( $('help_td').style.display == 'none' )
                            {
                                // Show Stuff
                                new Effect.Scale('help_toggle', 100, {scaleContent: false, scaleFrom: 0, scaleMode: { originalWidth: 350 }});
                                new Effect.Scale('help_toggle_2', 100, {scaleContent: false, scaleFrom: 0, scaleMode: { originalWidth: 350 }});
                                new Effect.Appear( 'help_td' );
                                new Effect.Appear( 'help_td_2' );
                            }
                            else
                            {
                                // Hide Stuff
                                new Effect.Scale('help_toggle', 0, {scaleContent: false, scaleFrom: 100, scaleMode: { originalWidth: 350 }});
                                new Effect.Scale('help_toggle_2', 0, {scaleContent: false, scaleFrom: 100, scaleMode: { originalWidth: 350 }});
                                new Effect.Fade( 'help_td' );
                                new Effect.Fade( 'help_td_2' );
                            }
                        }
                    </script>
                {/literal}
                <div style="float: right; font-size: 10px;" onclick="help_toggler(); return false;">
                    <img style="margin-bottom: -3px;" src="{$www_root_tpl_core}/images/icons/help.png" alt="Help Toggle" />
                    {translate}Help{/translate}
                </div>
                </td>
                <td id="help_td" class="admin_header_help" style="width: 1px; display: none;">
                   <div id="help_toggle" style="height: 18px; width: 0px; overflow: hidden;">&raquo; {translate}Help{/translate}</div>
                </td>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td class="admin_content" width="100%">
                {$content}
            </td>
            <td id="help_td_2" class="admin_help" style="padding: 0px; display: none;">
                <div id="help_toggle_2" style="height: 100%; padding: 0px; width: 0px; overflow: hidden;">{mod name="admin" sub="help" func="instant_show"}</div>

            </td>
        </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
<div id="footer" class="admin_content">
<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
    {$copyright}<br />
    Theme: {* {$theme-copyright} *} | &nbsp; Queries: {$query_counter}
</div>
{* Ajax Notification *}
<div id="notification" style="vertical-align:middle;display:none;z-index:99;">
    <img src="{$www_root_tpl_core}/images/ajax/2.gif" alt="Ajax Notification Toggle" />
    &nbsp; Wait - while processing your request...
</div>