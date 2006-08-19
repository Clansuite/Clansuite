{literal}
	<style type="text/css">
	/* only testing, die styles m�ssen sp�ter noch ins stylesheet */
	#show_shoutbox {
		width: 150px;
		height: 300px;
		overflow: scroll;
	}
	#show_shoutbox .entry_gerade {
		
	}
	#show_shoutbox .entry_ungerade 
		
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
			{assign var="i" value={smarty.foreach.shoutbox.iteration}}
			<div id="entry{$i}" {strip} class="
				{* css-Klasse bestimmen *}
				{if $i is even}
					entry_gerade
				{else}
					entry_ungerade
				{/if}
				{/strip}
				
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