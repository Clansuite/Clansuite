{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$news|@var_dump}
   <hr>
   {$pagination_links|@var_dump}
*}

{if !empty($news)}
    {pagination}

    <!-- News Fullarchiv Wrap -->

    <table width="100%">
        <tr>
            <td>{columnsort html='Datum'}</td>
            <td>{columnsort html='Titel'}</td>
            <td>{columnsort html='Kategorie'}</td>
            <td>{columnsort html='Author'}</td>
            <td>{columnsort html='Kommentare'}</td>
        </tr>

        {foreach item=singlenews from=$news}
        <!-- Anker-Sprungmarke für {$singlenews.news_id}-->
        <a name="news-{$singlenews.news_id}"></a>
        <tr>
            <td>{$singlenews.created_at|date_format:"%d.%m.%Y"}</td>
            <td><a href='index.php?mod=news&action=showone&id={$singlenews.news_title}'>{$singlenews.news_title}</a></td>
            <td><a href='index.php?mod=news&action=show&cat={$singlenews.CsCategories.cat_id}'>{$singlenews.CsCategories.name}</a></td>
            <td><a href='index.php?mod=users&amp;id={$singlenews.CsUsers.user_id}'>{$singlenews.CsUsers.nick}</a></td>
            <td><a href='index.php?mod=news&amp;action=showone&amp;id={$singlenews.news_id}'>{$singlenews.nr_news_comments}</a></td>
        </tr>
        {/foreach}
    </table>

{else}
{t}There are no news archived.{/t}
{/if}