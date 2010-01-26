{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Categories {html_alt_table loop=$categories}   {/if}
    <hr>
    {$categories|@var_dump}
*}

{jqconfirm}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall">{t}You can create, edit and delete Categories.{/t}</div>

<table cellspacing="0" cellpadding="0" border="0" align="center">

    <!-- Header of Table -->
    <tr class="tr_header">
        <th>{columnsort html='#'}</th>
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
    <form id="deleteForm" name="deleteForm" action="index.php?mod=categories&sub=admin&amp;action=delete" method="post" accept-charset="UTF-8">
    {foreach item=category from=$categories}
    <tr class="tr_row1">
        <td align="center"> {$category.cat_id}</td>
        <td align="center"> {$category.module|capitalize}</td>
        <td align="center"> <b><font color="{$category.color}">{$category.name}</font></b></td>
        <td align="center"> {$category.description}</td>
        <td align="center"> {icon src="`$category.image`"}</td>
        <td align="center"> {icon src="`$category.icon`"}</td>
        <td align="center"> {$category.color}<div style="width:5px; height:5px; border:1px solid #000000; background-color:{$category.color};"></div></td>
        <td align="center">
            <a class="ui-button ui-button-check ui-widget ui-state-default ui-corner-all ui-button-size-small ui-button-orientation-l" href="index.php?mod=categories&amp;sub=admin&amp;action=edit&amp;id={$category.cat_id}" tabindex="0">
            <span class="ui-button-icon">
                <span class="ui-icon ui-icon-pencil"></span>
            </span>
            <span class="ui-button-label" unselectable="on" style="-moz-user-select: none;">Edit</span>
            </a>
        </td>
        <td align="center" width="1%">
            <input type="hidden" name="ids[]" value="{$category.cat_id}" />
            <input id="delete" name="delete[]" type="checkbox" value="{$category.cat_id}" />
        </td>
    </tr>
    {/foreach}

    <!-- Form Buttons -->
    <tr class="tr_row1">
        <td height="20" colspan="9" align="right">
            <a class="ButtonGreen" href="index.php?mod=categories&amp;sub=admin&amp;action=create" />{t}Create Category{/t}</a>
            <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
            <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected Categories{/t}" />
        </td>
    </tr>

    </form>
    <!-- Close Form -->

</table>