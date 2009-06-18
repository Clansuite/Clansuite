 DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Categories {html_alt_table loop=$categories}   {/if}
    <hr>
    {$categories|@var_dump}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall">Sie können Categories hinzufügen, verändern und löschen.</div>

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