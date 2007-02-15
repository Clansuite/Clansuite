{* DEBUG OUTPUT of assigned Arrays:
	{$news|@var_dump}
	{$paginate|@var_dump}
*}

{* display pagination header *}
{if $paginate.size gt 1}
	 Items {$paginate.first}-{$paginate.last} of {$paginate.total} displayed.
{else}
	 Item {$paginate.first} of {$paginate.total} displayed.
{/if}

{* display results *}
{section name=res loop=$results}
	{$results[res]}
{/section}

{* display pagination info *}
{paginate_prev text="&lt;&lt;"} {paginate_middle format="page"}  {paginate_next text="&gt;&gt;"}

{foreach item=news from=$news}
<!-- Anker-Sprungmarke für {$news.news_id}-->
<a id="news-{$news.news_id}"></a>
<div class="newsbox">
	<div class="news_cat_img">
		<img src="{$news.image}" alt="Category-Image: {$news.cat_name}" />
	</div>
	<h4 class="news_head">{$news.news_title} - {$news.cat_name}</h4>
	<div class="news_author_comments">
		Geschrieben von <a href='index.php?mod=users&amp;id={$news.user_id}'>{$news.nick}</a> am {$news.news_added} - <a href='index.php?mod=news&amp;sub=newscomments&amp;id={$news.news_id}'>{$news.nr_news_comments} comments</a>
	</div>
	<div class="news_content">
		{if isset($news.image)}<img src="{php} print BASE_URL; {/php}{$news.cat_image_url}" alt="{$news.cat_image_url}" />{/if}
		{$news.news_body}
	</div>
</div>

{/foreach}