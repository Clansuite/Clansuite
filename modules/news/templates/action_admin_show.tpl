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
{literal}
    .selected { color:green; }
{/literal}
</style>

<div class="ModuleHeading">{t}News - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can write Articles, categorize, edit and delete them.{/t}</div>
 
<table border="0" cellspacing="1" cellpadding="3" style="width:99%">

    <caption class="td_header">News</caption>   

    <tr class="tr_row2">        
    
         <!-- Modify the View : Drop-Down Selection of the News-Categories -->
         <td colspan="8" align="right">Select Categorie:
            <form method="post" name="news_list" action="/index.php?mod=news&amp;sub=admin&amp;action=show">
            <select name="cat_id" class="form">
                <option value="0">----</option>
                {foreach item=cats from=$newscategories}
                <option value="{$cats.cat_id}">{$cats.name}</option>
                {/foreach}                
            </select> 
            <input type="submit" name="submit" value="Change View" class="ButtonOrange"/>
            </form>
        </td>
    </tr>

    <tr class="tr_row1">
        <!-- Table-Head Pagination -->
        <td height="20" colspan="8" align="right">
            {include file="tools/paginate.tpl"}
        </td>
    </tr>    
    
    <!-- Header of Table -->
    <tr class="td_header">
        <th>{columnsort html='Date'}</th>
        <th>{columnsort selected_class="selected"
                        html='Title'}</th>
        <th>{columnsort html='Categorie'}</th>
        <th>{columnsort html='Author'}</th>
        <th>Draft</th>
        <th>Action</th>
        <th>Select</th>
    </tr>

    <!-- Open Form -->
    <form action="index.php?mod=news&sub=admin&amp;action=delete" method="post" accept-charset="UTF-8">
        <!-- Content of Table -->
        {foreach item=news from=$news}
        <tr class="tr_row1">
                <td>{$news.news_added}</td>
                <td>{$news.news_title}</td>
                <td>{$news.CsCategory.name}</td>
                <td><a href='index.php?mod=users&amp;id={$news.CsUser.user_id}'>{$news.CsUser.nick}</a></td>
                <td>published</td>
                <td><input class="ButtonOrange" type="button" value="{t}Edit{/t}" /></td>
                <td align="center" width="1%">
                    <input type="hidden" name="ids[]" value="{$news.news_id}" />
                    <input name="delete[]" type="checkbox" value="{$news.news_id}" />
                </td>
        </tr>
        {/foreach}

        <!-- Form Buttons -->
        <tr class="tr_row1">
            <td height="20" colspan="8" align="right">
                <input class="ButtonGreen" type="button" value="{t}Create News{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=create", options: {method: "get"}}, {className: "alphacube", width:370, height: 250});{/literal}' />
                <input class="Button" name="reset" type="reset" value="{t}Reset{/t}" />
                <input class="ButtonRed" type="submit" name="delete_text" value="{t}Delete Selected News{/t}" />
            </td>
        </tr>
    </form>
    <!-- Close Form -->
    
    <!-- Table-Footer Pagination -->
    <tr class="tr_row1">
        <td height="20" colspan="8" align="right">
            {include file="tools/paginate.tpl"}
        </td>
    </tr>
</table>