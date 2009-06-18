{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Categories {html_alt_table loop=$categories}   {/if}
    <hr>
    {$categories|@var_dump}
*}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall">Sie können Categories hinzufügen, verändern und löschen.</div>

<div class="content" id="categories_admin_show">
<table>
    {foreach item=category from=$categories}
	<tr>
		<td>{$category.cat_id}</td>
		<td>{$category.module_id}</td>
		<td>{$category.sortorder}</td>
		<td>{$category.name}</td>
		<td>{$category.description}</td>
		<td>{$category.image}</td>
		<td>{$category.icon}</td>
		<td>{$category.color}</td>
	</tr>
	{/foreach}
</table>
</div>