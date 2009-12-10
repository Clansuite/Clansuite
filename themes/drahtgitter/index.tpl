{doc_info DOCTYPE=XHTML LEVEL=Transitional}

{move_to target="pre_head_close"}
{* everything in doc_raw is moved "as is" to header *}
<!-- Clip -->
<script src="{$www_root_themes_core}/javascript/clip.js" type="application/javascript"></script>
<!-- Favicon -->
<link rel="shortcut icon" href="{$www_root_theme}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_theme}/images/Clansuite-Favicon-16.ico" type="image/gif" />
<title>{$pagetitle} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/move_to}

<p align="center">This is "Drahtgitter" - an theme for pure development purposes - no gimmicks!</p>

<!-- Main Content Table -->
<table cellspacing="0" cellpadding="0" width="100%" border="1">
<colgroup>
    <col width="20%"/>
    <col width="60%"/>
    <col width="20%"/>
</colgroup>
<tr>
    <td valign="top"><strong>Menuitems</strong>
        <ul>
            <li><a href="?mod=index">Index</a></li>
            <li><a href="?mod=news">News</a></li>
            <li><a href="?mod=board">Board</a></li>
            <li><a href="?mod=guestbook">Guestbook</a></li>
            <li><a href="?mod=users">Userslist</a></li>
            <li><a href="?mod=staticpages">Static Pages</a></li>
            <li><a href="?mod=staticpages&amp;page=credits">Credits</a></li>
            <li><a href="?mod=controlcenter">Control Center</a></li>
        </ul>
    </td>
    <td valign="top">
        <div id="breadcrumbs">{breadcrumbs trail=$trail separator=" &raquo; " length=30}</div>
        <br />
        {$content}
    </td>
    <td valign="top">
        <div style="margin-top: 10px">
           {$cs->loadModule("account")}
           {$account->block_login()}
        {*
            {load_module name="account" action="login"}
            {load_module name="shoutbox" action="show"}
        *}
        </div>
        </td>
</tr>
<tr>
    <td valign="top"><strong>Themes</strong>
        <ul>
            {php} $url = strstr($_SERVER['REQUEST_URI'], '?theme', true); {/php}
            <li><a href="{$www_root}{php} echo $url; {/php}?theme=accessible">Accessible</a></li>
            <li><a href="{$www_root}{php} echo $url; {/php}?theme=standard">standard</a></li>
        </ul>
        <br />
    </td>
    <td valign="top">&nbsp;</td>
    <td>&nbsp;</td>
</tr>
</table>


<!-- Footer with Copyright, Theme-Copyright, tpl-timeing and db-querycount // -->
<p style="text-align:center;clear:both;margin-top:20px;" class="copyright">
    {$copyright}
    <br/> Theme: {$smarty.session.user.theme} by {* {$theme_copyright} *}
    <br/> {include file='server_stats.tpl'}
</p>


{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" style="vertical-align: middle;" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>