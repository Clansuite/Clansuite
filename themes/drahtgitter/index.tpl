{doc_info DOCTYPE=XHTML LEVEL=Transitional}
{* everything in doc_raw is moved "as is" to header *}
{doc_raw}


<!-- Favicon -->
<link rel="shortcut icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" />
<link rel="icon" href="{$www_root_themes}/images/Clansuite-Favicon-16.ico" type="image/gif" />

<title>{$std_page_title} - {breadcrumbs title="1" trail=$trail separator=" &raquo; " length=30}</title>
<!-- page cached on {$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}  -->
{/doc_raw}

This is "Drahtgitter" - an theme for pure development purposes - no gimmicks!

<!-- Main Content Table -->
<table cellspacing="0" cellpadding="0" width="100%" border="1">
<tr>
    <td><strong>Menuitems</strong>
        <ul>
            <li><a href="?mod=index">Index</a></li>
            <li><a href="?mod=index&action=mvc">Index MVC Test</a></li>
            <li><a href="?mod=index&action=smarty_error_example">Index smarty_error_example</a></li>
            <li><a href="?mod=news">News</a></li>
            <hr style="width:80%">
            <li><a href="?mod=admin">--ACP--</a></li>            
        </ul> 
    </td>
    <td>{$content}</td>
    <td>    </td>
</tr>
<tr>
    <td width=12%><strong>Themes</strong>
        <ul>
            <li><a href="?theme=accessible">Accessible</a></li>
            <li><a href="?theme=andreas01">Andreas01</a></li>
            <li><a href="?theme=shades_of_gray">Shades of Gray</a></li>
            <li><a href="?theme=standard">standard</a></li>
        </ul>        
        <br />        
    </td>
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