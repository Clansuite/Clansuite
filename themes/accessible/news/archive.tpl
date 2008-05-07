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
{/doc_raw}

{if !empty($news)}
{include file="tools/paginate.tpl"}
{foreach item=news from=$news}
<div id="news-{$news.news_id}" class="newsbox">
	<h4 class="news-head">{$news.news_title} - {$news.CsCategories.name}</h4>
	<div class="news-author-comments">
		{t}written by{/t} <a href='index.php?mod=users&amp;id={$news.CsUsers.user_id}'>{$news.CsUsers.nick}</a> {t}am{/t} {$news.news_added} - <a href='index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}'>{$news.CsNewsComments.nr_news_comments} {t}comments{/t}</a>
	</div>
	<div class="news-content">
		<div class="news-cat-img">
			<img src="{$news.CsCategories.image}" alt="Category-Image: {$news.CsCategories.name}" />
		</div>
		{if isset($news.image)}<img src="{php} print BASE_URL; {/php}{$news.CsCategories.image}" alt="{$news.CsCategories.image}" />{/if}
		{$news.news_body}<br />
       {if isset($smarty.session.user.rights.cc_edit_news) AND isset($smarty.session.user.rights.cc_access)}
            <form action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post" accept-charset="UTF-8">
                <input type="hidden" value="{$news.news_id}" name="delete[]" />
                <input type="hidden" value="{$news.news_id}" name="ids[]" />
                <input type="button" value="{t}Edit news{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={/literal}{$news.news_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});{/literal}' /> <input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
            </form>
        {/if}
	</div>
</div>
{/foreach}
{else}
{t}There is no news archived.{/t}
{/if}