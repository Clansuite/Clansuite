{literal}
	<style type="text/css">
	/* only testing, die styles müssen später noch ins stylesheet */
	#show_shoutbox {
		width: auto;
		height: 300px;
		overflow: auto;
		padding: 8px;
	}
	#show_shoutbox .entry_even {
	    background-color: #CCCCCE;
	}
	#show_shoutbox .entry_uneven 
		background-color: #CCCCCF; 
	}

	#show_shoutox ul {
	   list-style-type: none;
	}

	#show_shoutox ul li {
		display: inline;
	}
	</style>
{/literal}

<div id="show_shoutbox">
	
	{* Falls Einträge vorhanden sind *}
	{if $shoutbox_isEmpty == false}
		
		{* Einträge ausgeben *}
		{foreach from=$shoutbox_entries item=row key=key name=shoutbox}
		    
		    <div id="entry{$key}" class="{cycle values="entry_even,entry_uneven"}">
				{$key} - {$row.time}
				<ul>
				    <li class="name">{$row.name}</li>
                    <li class="msg">{$row.msg}</li>
				</ul>
			</div>
		
		{/foreach}
	{else}
	
	{* Es sind keine Einträge vorhanden *}
		{$no_entries_msg}
	{/if}	
	
	
	{* Das Formular includen *}
	{include file='shoutbox/show_form.tpl'}
</div>