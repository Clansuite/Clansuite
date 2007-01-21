{$chmod_tpl}
<table cellspacing="0" cellpadding="0" border="0" width="100%">
{foreach key=schluessel item=wert from=$content.whitelisted}
<tr>

    <td class="cell1" align="center"  width="120px">
    	<b>
    		<a href="index.php?mod=admin&sub=modules&action=export&details_name={$wert.name}">{$wert.title}</a>
    		</b>
    </td>
</tr>
{/foreach}
</table>