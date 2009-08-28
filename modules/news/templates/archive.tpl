{* DEBUG OUTPUT of assigned Arrays: 
   {$smarty.session|@var_dump}
   <hr>
   {$news|@var_dump}
   <hr>
   {$pagination_links|@var_dump} 
*}

{move_to target="pre_head_close"}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}/javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"> </script>
{/move_to}

{if !empty($news)}
    {pagination}

    {foreach item=news from=$news}

    <!-- Anker-Sprungmarke für {$news.news_id}-->
    <a name="news-{$news.news_id}"></a>

    <!-- News Wrap -->
    <table border="1" cellspacing="1" cellpadding="3" style="width:99%">
        <tr>
            <td height="20" ><b>{$news.news_title} - {$news.CsCategories.name}</b></td>
            <td rowspan="3" valign="top"><img src="{$news.CsCategories.image}" alt="Category-Image: {$news.CsCategories.name} " /></td>
        </tr>

        <tr>
            <td valign="top"><font size="1">{t}written by{/t} <a href='index.php?mod=users&amp;id={$news.CsUser.user_id}'>{$news.CsUser.nick}</a> {t}at{/t} {$news.created_at} - <a href='index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}'>{$news.CsNewsComments.nr_news_comments}{t} comments{/t}</a></font></td>
        </tr>

        <tr>
            <td height="175" width="75%" valign="top">{$news.news_body}</td>
        </tr>

        <tr>
             <td>
                <strong>&raquo;</strong>
                <a href="index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}">{$news.CsNewsComments.nr_news_comments} Comments</a>
                {if isset($news.CsNewsComments.CsUser.lastcomment_by) }<span> : {$news.CsNewsComments.CsUser.lastcomment_by}</span>{/if}
            </td>
    	    <td>
    	    {if isset($smarty.session.user.rights.permission_edit_news) AND isset($smarty.session.user.rights.permission_access)}

                <form action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post">
                    <input type="hidden" value="{$news.news_id}" name="delete[]" />
                    <input type="hidden" value="{$news.news_id}" name="ids[]" />
                    <input type="button" value="{t}Edit news{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={/literal}{$news.news_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});{/literal}' /> <input type="submit" name="submit" value="{t}Delete{/t}" />
                </form>
            {/if}
    	    </td>
        </tr>
    </table>
    <br />

    <div>{if isset($news.image)} <img src="{php} print BASE_URL; {/php}{$news.CsCategories.image}" alt="{$news.CsCategories.image}"/> {/if}</div>


    {/foreach}
{else}
{t}There is no news archived.{/t}
{/if}