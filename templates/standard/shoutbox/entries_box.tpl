<table cellpadding="0" cellspacing="0" border="0" align="center" id="show_shoutbox">
	{if $shoutbox_is_empty == false}
		
		{* Einträge ausgeben *}
		{foreach from=$shoutbox_entries item=row key=key name=shoutbox}
            <tr>
                <td class="cell3">
                    #{$row.id} - <b>{$row.name}:</b>
                </td>
            <tr>
            
                <td class="cell1">
                <div class="small_grey">{$row.time|date_format:"%d.%m.%Y - %H:%M"}</div>
                    {$row.msg|nl2br|wordwrap:20:"<br />\n":true}
                </td>
            </tr>
            <tr>
                <td height="10">&nbsp;</td>
            </tr>		
		{/foreach}
	{else}
		{$no_entries_msg}
	{/if}
</table>