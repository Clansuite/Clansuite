{doc_info DOCTYPE=XHTML LEVEL=Transitional}

{doc_raw}
{* everything in doc_raw is moved "as is" to header *}
<!-- Clip -->
<script src="{$www_root_themes_core}/javascript/clip.js" type="application/javascript"></script>  
<!-- Favicon -->
<link rel="shortcut icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" type="image/gif" />
<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}

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
            <li><a href="?mod=guestbook">Guestbook</a></li>
            <li><a href="?mod=userslist">Userslist</a></li>
            <li><a href="?mod=admin">Clansuite Controlpanel</a></li>
        </ul>
    </td>
    <td valign="top">
        <div id="breadcrumbs">{breadcrumbs trail=$trail separator=" &raquo; " length=30}</div>
        <br />
        {$content}
    </td>
    <td valign="top">&nbsp;</td>
</tr>
<tr>
    <td valign="top"><strong>Themes</strong>
        <ul>
            <li><a href="?theme=accessible">Accessible</a></li>
            <li><a href="?theme=andreas01">Andreas01</a></li>
            <li><a href="?theme=shades_of_gray">Shades of Gray</a></li>
            <li><a href="?theme=standard">standard</a></li>
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
    <br/> Theme: {* {$theme-copyright} *}
    <br/> {include file='server_stats.tpl'}
</p>


{* Ajax Notification *}
<div id="notification" style="display: none;">
    <img src="{$www_root_themes_core}/images/ajax/2.gif" style="vertical-align: middle;" alt="Ajax Notification Image"/>
    &nbsp; Wait - while processing your request...
</div>