{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}


<!-- Favicon -->
<link rel="shortcut icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" type="image/gif" />

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}


<!-- Main Content Table -->
<table cellspacing="0" cellpadding="0" width="100%" border="1">
<tr>
    <td>    </td>
    <td>{$content}</td>
    <td>    </td>
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