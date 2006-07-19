<h2>News</h2>

Debugausgabe des Arrays:
{html_alt_table loop=$news}

{* OLD TABLELESS DIVS
<!-- BEGIN news -->
<h1>News</h1>

{foreach item=news from=$news}

<div class="news">
  <a name="news-{$news.news_id}"></a>
  <div class="title">{$news.news_title} - {$cat_name} </div>
  <div class="image">{if isset($news.cat_image_url)} <img src="{php} print BASE_URL; {/php}{$news.cat_image_url}" alt="{$news.cat_image_url}"/> {/if}</div>
  <div class="submitted">{$modullanguage.submittedby} <a href="users/view.php?user_id={$news.user_id}">{$nick}</a> {$corelanguage.on} {$news.news_added}</div>
  <div class="body">{$news.news_body}</div>
  <div class="comments">
      <strong>&raquo;</strong>
      <a href="news_comments.php?news_id={$news.news_id}">{$news.nr_news_comments} {$corelanguage.comments}</a>
      <!-- IF lastcomment_by -->
	<div>{$modullanguage.lastcomment}: <span>{$news.lastcomment_by}</span></div>
      <!-- ENDIF lastcomment_by -->
  </div>
</div>

{/foreach}
<!-- END news -->

*}