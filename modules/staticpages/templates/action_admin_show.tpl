{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Staticpages {html_alt_table loop=$staticpages}   {/if}
    <hr>
    {$staticpages|@var_dump}
*}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Staticpages</div>
<div class="ModuleHeadingSmall">Sie k�nnen Staticpages hinzuf�gen, ver�ndern und l�schen.</div>

<div class="content" id="staticpages_admin_show">
<table>
    {foreach item=staticpages from=$staticpages}
	<tr>
		<td>{$staticpages.id}</td>
		<td>{$staticpages.title}</td>
		<td>{$staticpages.description}</td>
		<td>{$staticpages.url}</td>
		<td>{$staticpages.html}</td>
		<td>{$staticpages.iframe}</td>
		<td>{$staticpages.iframe_height}</td>
	</tr>
	{/foreach}
</table>
</div>