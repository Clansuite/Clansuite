 DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Categories {html_alt_table loop=$categories}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$categories|@var_dump}
    <hr>
    {$categories|@var_dump}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall">Sie k�nnen Categories hinzuf�gen, ver�ndern und l�schen.</div>

<div class="content" id="categories_admin_show">
<table>
	<tr>
		<td>{$categories.cat_id}</td>
		<td>{$categories.module_id}</td>
		<td>{$categories_sortorder}</td>
		<td>{$categories.name}</td>
		<td>{$categories.description}</td>
		<td>{$categories.images}</td>
		<td>{$categories.icon}</td>
		<td>{$categories.color}</td>
	</tr>
</table>
</div>