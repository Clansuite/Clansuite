{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}

<meta http-equiv="expires" content="Fri, Jan 01 1900 00:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="{$meta.language}" />
<meta name="author" content="{$meta.author}" />
<meta http-equiv="reply-to" content="{$meta.email}" />
<meta name="description" content="{$meta.description}" />
<meta name="keywords" content="{$meta.keywords}" />
<link rel="shortcut icon" href="{$www_core_tpl_root}/images/icons/favicon.ico" />
<link rel="stylesheet" type="text/css" href="{$www_core_tpl_root}/admin/admin.css" />

{$additional_head}
{$redirect}
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>

<!--
page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}
-->

{/doc_raw}
<body>

    <div class="header">
        <a href="index.php?mod=admin"><img style="margin-bottom: -3px;" src="{$www_tpl_root}/images/clansuite-80x15.png" alt="Clansuite CMS" border="0"></a> - Control Center
        <span>{$smarty.now|date_format:"%e %B %Y - %A | %H:%M"}</span>
    </div>

    {include file="admin/adminmenu/adminmenu.tpl"}


    <p>&nbsp;</p>
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <tr>
            <td class="admin_header">
            
            <div style="float: left">
                {breadcrumbs heading="1" trail=$trail separator=" &raquo; " length=30}
            </div>
            
            {literal}
            <script type="text/javascript">
            function help_toggler()
            {
             if ( document.getElementById('help_toggle').style.display == 'none' )
             { // Show Stuff
              new Effect.Appear('help_toggle');
              new Effect.Appear('help_toggle_2');
             }
             else
             { // Hide Stuff
              new Effect.Fade('help_toggle');
              new Effect.Fade('help_toggle_2');
             }
            }
            </script>
            {/literal}
            
            <div style="float: right; font-size: xx-small;">
            <img onclick="help_toggler(); return false;" style="margin-bottom: -3px;" src="{$www_core_tpl_root}/images/icons/help.png" alt="Help" border="0">

                Help
            </div>
            
            </td>
            <td id="help_toggle" class="admin_header_help">
               &raquo; {translate}Help{/translate}
                
            </td>
        </tr>

        <tr>
            <td width="80%" class="admin_content">
               
                {$content}
            </td>
            
            <td id="help_toggle_2" width="20%" class="admin_help" style="padding: 0px">
                {mod name="admin" sub="help" func="instant_show"}
            </td>
           
        </tr>
    </table>
    <p>&nbsp;</p>

{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_core_tpl_root}/images/ajax/2.gif" align="absmiddle" />
    &nbsp; Wait - while processing your request...
</div>

</body>
</html>