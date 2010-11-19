{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Games {html_alt_table loop=$games}   {/if}
    <hr>
    {$games|var_dump}
*}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Games</div>
<div class="ModuleHeadingSmall">Sie k�nnen Games hinzuf�gen, ver�ndern und l�schen.</div>

<div class="content" id="games_admin_show">
<table>
    {foreach item=games from=$games}
	<tr>
		<td>{$games.games_id}</td>
		<td>{$games.name}</td>
		<td>{$games.description}</td>
		<td>{$games.image}</td>
		<td>{$games.icon}</td>
	</tr>
	{/foreach}
</table>
</div>