{if $shoutbox_is_empty == false}
{* Einträge ausgeben *}
{foreach from=$shoutbox_entries item=row name=shoutbox}
<div class="shoutbox" title="{$row.time|date_format:"%d.%m.%Y - %H:%M"}">
	<div class="shout-header">
		#{$row.id} - <strong>{$row.name}:</strong>
	</div>
	<div class="shout-content">
		{$row.msg|nl2br|wordwrap:20:"<br />\n":true}
	</div>
</div>
<br />
{/foreach}
{else}
{$no_entries_msg}
{/if}