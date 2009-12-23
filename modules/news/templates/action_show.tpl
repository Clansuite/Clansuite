{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$news|@var_dump}
*}
<table border="0" cellspacing="1" cellpadding="3" style="width:99%">
<tr>
    <td class="td_header">News</td>
</tr>
<tr>
    <td class="td_header_small">

        <!-- RSS Icon -->
        <div style="float:right;">
            <a href="index.php?mod=news&amp;action=getfeed"> {icon name="rss" alt="Clansuite RSS News Feed"} </a>
        </div>

        {pagination}

    </td>
</tr>
</table>

<br />

{foreach item=singlenews from=$news}

<!-- Anker-Sprungmarke f�r {$singlenews.news_id}--> <a name="news-{$singlenews.news_id}"></a>
<table border="1" cellspacing="1" cellpadding="3" style="width:99%">
    <tr>
        <td height="20" ><b>{$singlenews.news_title} {icon name="category"} {$singlenews.CsCategories.name} {icon name="tag"} No Tags applied yet!</b></td>
        <td rowspan="3" valign="top"><img src="{$singlenews.CsCategories.image}" alt="Category-Image: {$singlenews.CsCategories.name} " /></td>
    </tr>

    <tr>
        <td valign="top" class="dunkler">
            <span class="writtenby">The article was written by <a href='index.php?mod=users&amp;id={$singlenews.CsUsers.user_id}'>{$singlenews.CsUsers.nick}</a> on {$singlenews.created_at|date_format}.
            <br/>
            <span class="comments">{icon name="comment"}Until now, it has <a href='index.php?mod=news&amp;action=showone&amp;id={$singlenews.news_id}'>{$singlenews.nr_news_comments} comments.</a></span>
        </td>
    </tr>

    <tr>
        <td height="175" width="75%" valign="top">{$singlenews.news_body}</td>
    </tr>

    <tr>
         <td>
            <strong>&raquo;</strong>
            <a href="index.php?mod=news&amp;action=showone&amp;id={$singlenews.news_id}">{$singlenews.nr_news_comments} Comments</a>
            {if isset($news.CsComments.CsUsers.lastcomment_by)}<span> : {$singlenews.CsComments.CsUsers.lastcomment_by}</span>{/if}
        </td>
        <td>
        {if isset($smarty.session.user.rights.permission_edit_news) AND isset($smarty.session.user.rights.permission_access)}

            <form id="deleteForm" name="deleteForm" action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post">
                <input type="hidden" value="{$singlenews.news_id}" name="delete[]" />
                <input type="hidden" value="{$singlenews.news_id}" name="ids[]" />
                <input class="ButtonGreen" type="button" value="{t}Edit news{/t}" />
                <input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
            </form>
        {/if}
        </td>
    </tr>
</table>
<br />

<div class="image">{if isset($news.image)} <img src="{php} print BASE_URL; {/php}{$singlenews.CsCategories.image}" alt="{$singlenews.CsCategories.image}"/> {/if}</div>

{/foreach}