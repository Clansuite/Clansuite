<center>
<table cellspacing="20" cellpadding="10">
{foreach key=row item=image from=$shortcuts}
    <tr>
    {foreach key=col item=data from=$image}
        <td align="center">
            <a href="{$data.href}">
                <img alt="Shortcut Icon" src="{$www_core_tpl_root}/images/symbols/{$data.file_name}">
                <br />
                <div style="padding-top: 10px;">{translate}{$data.title}{/translate}</div>
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