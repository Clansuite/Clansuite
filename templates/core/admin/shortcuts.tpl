<div align="center">
<table border="0" cellspacing="10" cellpadding="0" width="1024">
{foreach key=row item=image from=$shortcuts}
    <tr>
    {foreach key=col item=data from=$image}
        <td align="center">
            <a href="{$www_root}{$data.href}">
                <img src="{$www_core_tpl_root}/images/symbols/{$data.file_name}" border="0">
                <br />
                <div style="padding-top: 15px;">{translate}{$data.title}{/translate}</div>
            </a>
        </td>
    {/foreach}
    </tr>
    <tr>
        <td align="center" colspan="4">
            <hr />        
        </td>
    </tr>
{/foreach}
</table>
</center>