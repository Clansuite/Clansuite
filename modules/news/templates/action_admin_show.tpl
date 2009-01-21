{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$newsarchiv|@var_dump}
    <hr>
    {$newscategories|@var_dump}
*}

<style type="text/css">
{literal}
    .selected { color:green; }
{/literal}
</style>

<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">News</caption>

    <tr class="tr_row2">
         <td colspan="8" align="right">Kategorie-Auswahl:

            <form method="post" name="news_list" action="/index.php?mod=news&amp;sub=admin&amp;action=show">
            <select name="cat_id" class="form">
                <option value="0">----</option>

                {foreach item=cats from=$newscategories}
                <option value="{$cats.cat_id}">{$cats.name}</option>
                {/foreach}

            </select>

             <input type="submit" name="submit" value="Anzeigen" class="form"/></form>
        </td>
    </tr>

    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
            {include file="tools/paginate.tpl"}
        </td>
    </tr>

    <tr class="td_header">
        <th>{columnsort html='Datum'}</th>
        <th>{columnsort selected_class="selected"
                        html='Title'}</th>
        <th>{columnsort html='Kategorie'}</th>
        <th>{columnsort html='Verfasser'}</th>
        <th>Draft</th>
        <th>Action</th>
        <th>Select</th>
    </tr>

    {foreach item=news from=$newsarchiv}
    <tr class="tr_row1">
            <td>{$news.news_added}</td>
            <td>{$news.news_title}</td>
            <td>{$news.CsCategory.name}</td>
            <td><a href='index.php?mod=users&amp;id={$news.CsUser.user_id}'>{$news.CsUser.nick}</a></td>
            <td>published</td>
            <td>add edit</td>
            <td align="center" width="1%">
                        <input type="hidden" name="ids[]" value="{$news.news_id.0}" />
                        <input name="delete[]" type="checkbox" value="{$news.news_id.0}" />
            </td>
    </tr>
    {/foreach}


    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
            <input class="ButtonGreen" type="button" value="{t}Create News{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=create", options: {method: "get"}}, {className: "alphacube", width:370, height: 250});{/literal}' />
            <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
            <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected News{/t}" />
        </td>
    </tr>
    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
            {include file="tools/paginate.tpl"}
        </td>
    </tr>
</table>