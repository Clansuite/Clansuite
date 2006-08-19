{literal}
	<style type="text/css">
	/* only testing, die styles m�ssen sp�ter noch ins stylesheet */
	#show_shoutbox {
		width: auto;
		height: 300px;
		overflow: auto;
	}
	#show_shoutbox .entry_even {
	    background-color: #CCCCCC;
	}
	#show_shoutbox .entry_uneven 
		background-color: #CCDDCC; 
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
	
	{* Falls Eintr�ge vorhanden sind *}
	{if $shoutbox_isEmpty == false}
		
		{* Eintr�ge ausgeben *}
		{foreach from=$shoutbox_entries item=row name=shoutbox}
			
		    <div id="entry{$smarty.foreach.customers.iteration}" class="{cycle values="entry_even,entry_uneven"}">
					
				{* Eintrag als Liste *}
				<ul>
					<li class="name">{$row.name}</li>
					<li class="msg">{$row.msg}</li>
				</ul>
				
			</div>
		{/foreach}
	{else}
	
	{* Es sind keine Eintr�ge vorhanden *}
		{$no_entries_msg}
	{/if}	
	
	
	{* Das Formular includen *}
	{include file='shoutbox/show_form.tpl'}
</div>