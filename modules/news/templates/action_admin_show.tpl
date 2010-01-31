{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$news|@var_dump}
    <hr>
    {$newscategories|@var_dump}
*} 

<style type="text/css">

    .selected { color:green; }

</style>

<!-- start jq confirm dialog -->
{jqconfirm}
<!-- end jq confirm dialog -->

{modulenavigation}

{$datagrid}


<div class="ModuleHeading">{t}News - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can write Articles, categorize, edit and delete them.{/t}</div>

<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">News</caption>

    <tr class="tr_row2">

         <!-- Modify the View : Drop-Down Selection of the News-Categories -->
         <td colspan="8" align="right">Select Category:
            <form method="post" name="news_category_form" action="/index.php?mod=news&amp;sub=admin&amp;action=show">
            <select name="news_category_form[cat_id]" class="form">
                <option value="0">----</option>
                {foreach item=cats from=$newscategories}
                    <option value="{$cats.CsCategories.cat_id}">{$cats.CsCategories.name} ({$cats.sum_news})</option>
                {/foreach}
            </select>
            <input type="submit" name="submit" value="Change View" class="ButtonOrange"/>
            </form>
        </td>
    </tr>

    <tr class="tr_row1">
        <!-- Table-Head Pagination -->
        <td height="20" colspan="8" align="right">
            {pagination}
        </td>
    </tr>

    <!-- Header of Table -->
    <tr class="tr_header">
        <th>{columnsort html='Date'}</th>
        <th>{columnsort selected_class="selected"
                        html='Title'}</th>
        <th>{columnsort html='Category'}</th>
        <th>{columnsort html='Author'}</th>
        <th>Draft</th>
        <th>Action</th>
        <th>Select</th>
    </tr>

    <!-- Open Form -->
    <form id="deleteForm" name="deleteForm" action="index.php?mod=news&sub=admin&amp;action=delete" method="post" accept-charset="UTF-8">
        <!-- Content of Table -->
        {foreach item=singlenews from=$news}
        <tr class="tr_row1">
                <td>{$singlenews.created_at|date_format:"%d.%m.%y"}</td>
                <td>{$singlenews.news_title}</td>
                <td>{$singlenews.CsCategories.name}</td>
                <td><a href='index.php?mod=users&amp;id={$singlenews.CsUsers.user_id}'>{$singlenews.CsUsers.nick}</a></td>
                <td>published</td>
                
                {* Actions *}
                <td width="5%">
					<a class="ui-button ui-button-check ui-widget ui-state-default ui-corner-all ui-button-size-small ui-button-orientation-l" href="index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={$singlenews.news_id}" tabindex="0">
						<span class="ui-button-icon">
							<span class="ui-icon ui-icon-pencil"></span>
						</span>
						<span class="ui-button-label" unselectable="on" style="-moz-user-select: none;">Edit</span>
					</a>
				</td>
                
                {* Select Checkbox *}
                <td width="1%">
                    <input type="hidden" name="ids[]" value="{$singlenews.news_id}" />
                    <input name="delete[]" type="checkbox" value="{$singlenews.news_id}" style="margin-left: 10px;" />
                </td>
        </tr>
        {/foreach}

        <!-- Form Buttons -->
        <tr class="tr_row1">
            <td height="20" colspan="8" align="right">
                <a class="ButtonGreen" href="index.php?mod=news&amp;sub=admin&amp;action=create" />{t}Create News{/t}</a>
                <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected News{/t}" />
                
                <input class="Button" value="{t}Check all{/t}" />
                <input class="Button" value="{t}Uncheck all{/t}" />
                <img style="margin-right: 10px;" src="{$www_root_themes_core}/images/arrow_rtl.png">
            </td>
        </tr>
    </form>
    <!-- Close Form -->

    <!-- Table-Footer Pagination -->
    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
            {pagination}
        </td>
    </tr>
</table>