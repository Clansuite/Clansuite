{* DEBUG OUTPUT of assigned Arrays: 
   {$smarty.session|@var_dump}
   <hr>
   {$news|@var_dump}
   <hr>
   {$pagination_links|@var_dump} 
*}

{doc_raw}
    <script src="{$www_root_themes_core}/javascript/prototype/prototype.js" type="text/javascript"></script>
    <script src="{$www_root_themes_core}/javascript/lightbox/lightbox.js" type="text/javascript"></script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/scriptaculous/effects.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window.js"> </script>
  	<script type="text/javascript" src="{$www_root_themes_core}/javascript/xilinus/window_effects.js"> </script>
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alphacube.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/alert.css" />
  	<link rel="stylesheet" type="text/css" href="{$www_root_themes_core}/javascript/xilinus/themes/default.css" />
{/doc_raw}

{if !empty($news)}
    {include file="tools/paginate.tpl"}

    {foreach item=news from=$news}

    <!-- Anker-Sprungmarke f�r {$news.news_id}-->
    <a name="news-{$news.news_id}"></a>

    <!-- News Wrap -->
    <table border="1" cellspacing="1" cellpadding="3" style="width:99%">
        <tr>
            <td height="20" ><b>{$news.news_title} - {$news.CsCategories.name}</b></td>
            <td rowspan="3" valign="top"><img src="{$news.CsCategories.image}" alt="Category-Image: {$news.CsCategories.name} " /></td>
        </tr>

        <tr>
            <td valign="top" class="dunkler"><font size="1">{t}written by{/t} <a href='index.php?mod=users&amp;id={$news.CsUsers.user_id}'>{$news.CsUsers.nick}</a> {t}at{/t} {$news.news_added} - <a href='index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}'>{$news.CsNewsComments.nr_news_comments}{t} comments{/t}</a></font></td>
        </tr>

        <tr>
            <td height="175" width="75%" valign="top">{$news.news_body}</td>
        </tr>

        <tr>
             <td>
                <strong>&raquo;</strong>
                <a href="index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}">{$news.CsNewsComments.nr_news_comments} Comments</a>
                {if isset($news.CsNewsComments.CsUsers.lastcomment_by) }<span> : {$news.CsNewsComments.CsUsers.lastcomment_by}</span>{/if}
            </td>
    	    <td>
    	    {if isset($smarty.session.user.rights.cc_edit_news) AND isset($smarty.session.user.rights.cc_access)}

                <form action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post">
                    <input type="hidden" value="{$news.news_id}" name="delete[]" />
                    <input type="hidden" value="{$news.news_id}" name="ids[]" />
                    <input class="ButtonGreen" type="button" value="{t}Edit news{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={/literal}{$news.news_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});{/literal}' /> <input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
                </form>
            {/if}
    	    </td>
        </tr>
    </table>
    <br />

    <div class="image">{if isset($news.image)} <img src="{php} print BASE_URL; {/php}{$news.CsCategories.image}" alt="{$news.CsCategories.image}"/> {/if}</div>


    {/foreach}
{else}
{t}There is no news archived.{/t}
{/if}