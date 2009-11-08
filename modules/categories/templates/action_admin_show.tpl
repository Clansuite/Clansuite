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

    <!-- Header of Table -->
    <tr class="td_header">
        <th>{columnsort html='Module'}</th>
        <th>{columnsort selected_class="selected" html='Name'}</th>
        <th>{columnsort html='Description'}</th>
        <th>{columnsort html='Image'}</th>
		<th>{columnsort html='Icon'}</th>
		<th>{columnsort html='Color'}</th>
		<th>Action</th>
        <th>Select</th>
    </tr>

    {foreach item=category from=$categories}
	<tr class="tr_row1">
		<td>{$category.module|capitalize}</td>
		<td>{$category.name}</td>
		<td>{$category.description}</td>
		<td><img src="{$category.image}" /></td>
		<td>{$category.icon}</td>
		<td>{$category.color}<div style="width:5px; height:5px; border:1px solid #000000; background-color:{$category.color};"></div></td>
        <td><a class="ButtonOrange" href="index.php?mod=categories&amp;sub=admin&amp;action=edit&amp;id={$category.cat_id}" />{t}Edit{/t}</a></td>
        <td align="center" width="1%">
            <input type="hidden" name="ids[]" value="{$category.cat_id}" />
            <input name="delete[]" type="checkbox" value="{$category.cat_id}" />
        </td>
	</tr>
	{/foreach}
	

</table>
</div>