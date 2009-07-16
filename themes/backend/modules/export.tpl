{$chmod_tpl}
<table cellspacing="0" cellpadding="0" border="0" width="300px" align="center">
<thead>
    <tr>
        <td class="td_header" style="text-align: center">
            {t}Choose a module/submodule to export{/t}
        </td>
    </tr>
</thead>
{foreach key=schluessel item=wert from=$content.whitelisted}
<tbody>
    <tr>
        <td class="cell1" style="text-align: left">
        		<a style="text-wieght: bold" href="index.php?mod=controlcenter&amp;sub=modules&amp;action=export&amp;details_name={$wert.name}">{$wert.title}</a>
        		<br />
        		{foreach key=key item=item from=$wert.subs}
        		&nbsp;-&nbsp;<a href="index.php?mod=controlcenter&amp;sub=modules&amp;action=export&amp;subdetails_id={$item.submodule_id.0}">{$item.name}</a><br />
        		{/foreach}
        </td>
    </tr>
</tbody>
{/foreach}
</table>