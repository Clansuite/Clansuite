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

<table class="admintable" summary="Administration of News - Show" border="1" cellpadding="0" cellspacing="0" style="width:99%">

    <caption class="td_header">News</caption>

    <tr class="tr_header_small">
        <td>
                {t}Write News{/t}
        </td>
        <td>
            {* Pagination needed: {$pagination_needed} *}
            <br />
            {$pagination_links} - Seite {$paginate_currentpage}/{$paginate_lastpage}.

            {* display pagination header *}
            Items {$paginate_currentpage} to {$paginate_resultsperpage} displayed.
        </td>
    </tr>

    <tr>
         <td colspan="2">Kategorie-Auswahl:

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

</table>

<br/>

<table border="1" cellspacing="1" cellpadding="3" style="width:99%">

    <tr class="tr_header_small">
        <th>{columnsort html="Datum"}</th>
        <th>{columnsort selected_class="selected"
                        html='Title'}</th>
        <th>{columnsort html='Kategorie'}</th>
        <th>{columnsort html='Verfasser'}</th>
        <th>Draft</th>
        <th>Action</th>
    </tr>

    {foreach item=news from=$newsarchiv}
    <tr>
            <td>{$news.news_added}</td>
            <td>{$news.news_title}</td>
            <td>{$news.CsCategories.name}</td>
            <td><a href='index.php?mod=users&amp;id={$news.CsUsers.user_id}'>{$news.CsUsers.nick}</a></td>
            <td>published</td>
            <td>add edit</td>
    </tr>
    {/foreach}

</table>