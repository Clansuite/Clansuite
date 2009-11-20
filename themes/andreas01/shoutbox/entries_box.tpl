<h2>{t}Shoutbox{/t}</h2>
<div id="show_shoutbox">
	
	{* Falls Einträge vorhanden sind *}
	{if $shoutbox_is_empty == false}
		
		{* Einträge ausgeben *}
		{foreach from=$shoutbox_entries item=row key=key name=shoutbox}
		    
		    <div id="entry{$key}" class="{cycle values="entry_even,entry_uneven"}">
			
				{$row.id} - {$row.time|date_format:"%d.%m.%Y - %H:%M"}
				<ul>
				    <li class="name">{$row.name} wrote:<br /></li>	{* noch übersetzten *}
                    <li class="msg">{$row.msg}</li>
				</ul>
			</div>
		
		{/foreach}
	{else}
	
	    {* Es sind keine Einträge vorhanden *}
		{$no_entries_msg}
	{/if}	
</div>