<h1>{t}News{/t}</h1>
{* DEBUG OUTPUT of assigned Arrays:
	{$news|@var_dump}
	{$paginate|@var_dump}
*}
    <script type="text/javascript" src="{$www_root}/core/fckeditor/fckeditor.js"></script>
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

{include file="tools/paginate.tpl"}

{foreach item=news from=$news}
<!-- Anker-Sprungmarke für {$news.news_id}-->
<a id="news-{$news.news_id}"></a>
<div class="newsbox">
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
        {if $smarty.session.user.rights.cc_edit_news == 1 AND $smarty.session.user.rights.cc_access == 1}

            <form action="index.php?mod=news&amp;sub=admin&amp;action=delete&amp;front=1" method="post" accept-charset="UTF-8">
                <input type="hidden" value="{$news.news_id}" name="delete[]" />
                <input type="hidden" value="{$news.news_id}" name="ids[]" />
                <input class="ButtonGreen" type="button" value="{t}Edit news{/t}" onclick='{literal}Dialog.info({url: "index.php?mod=news&amp;sub=admin&amp;action=edit&amp;id={/literal}{$news.news_id}{literal}&amp;front=1", options: {method: "get"}}, {className: "alphacube", width:900, height: 600});{/literal}' /> <input class="ButtonRed" type="submit" name="submit" value="{t}Delete{/t}" />
            </form>
        {/if}
	</div>
</div>
{/foreach}