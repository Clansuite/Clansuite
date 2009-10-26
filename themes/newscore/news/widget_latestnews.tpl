 {* {$widget_latestnews|var_dump} *}

<!-- Start latestNews Widget from Theme Newscore /-->

<div class="widget_head">
	<span class="widget_title">Latest News</span>
</div>
<ul>
	{foreach item=widget_latestnews from=$widget_latestnews}
	<li>
    	<div class="link"><a class="newslink" href="index.php?mod=news&action=showone&id={$widget_latestnews.news_id}">{$widget_latestnews.news_title}</a></div>
        <div class="time">{$widget_latestnews.created_at|date_format}</div>
    </li>
    {/foreach}
</ul>

<!-- Ende latestNews Widget //-->