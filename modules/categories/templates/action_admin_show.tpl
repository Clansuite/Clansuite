{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Categories {html_alt_table loop=$categories}   {/if}
    <hr>
    {$categories|@var_dump}
*}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall">{t}You can create, edit and delete Categories.{/t}</div>

<div class="content" id="categories_admin_show">
<table>

    <!-- Header of Table -->
    <tr class="td_header">
        <th>{columnsort html='Module'}</th>
        <th>{columnsort selected_class="selected" html='Name'}</th>
        <th>Description</th>
        <th>Image</th>
		<th>Icon</th>
		<th>Color</th>
		<th>Action</th>
        <th>Select</th>
    </tr>

    <!-- Open Form -->
    <form action="index.php?mod=categories&sub=admin&amp;action=delete" method="post" accept-charset="UTF-8">
    {foreach item=category from=$categories}
	<tr class="tr_row1">
		<td>{$category.module|capitalize}</td>
		<td>{$category.name}</td>
		<td>{$category.description}</td>
		<td><img src="{$category.image}" /></td>
		<td><img src="{$category.icon}" /></td>
		<td>{$category.color}<div style="width:5px; height:5px; border:1px solid #000000; background-color:{$category.color};"></div></td>
        <td><a class="ButtonOrange" href="index.php?mod=categories&amp;sub=admin&amp;action=edit&amp;id={$category.cat_id}" />{t}Edit{/t}</a></td>
        <td align="center" width="1%">
            <input type="hidden" name="ids[]" value="{$category.cat_id}" />
            <input name="delete[]" type="checkbox" value="{$category.cat_id}" />
        </td>
	</tr>
	{/foreach}
        <!-- Form Buttons -->
        <tr class="tr_row1">
            <td height="20" colspan="8" align="right">
                <a class="ButtonGreen" href="index.php?mod=categories&amp;sub=admin&amp;action=create" />{t}Create Category{/t}</a>
                <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected Categories{/t}" />
            </td>
        </tr>
    </form>
    <!-- Close Form -->

</table>
</div>