{* {$news_widget|var_dump} *}

<!-- Start News Widget from Module News /-->

<div class="widget_head">
	<span class="widget_title">Latest News</span>
</div>
<ul>
	{foreach item=news_widget from=$news_widget}
	<li>
    	<div class="link"><a class="newslink" href="index.php?mod=news&action=showone&id={$news_widget.news_id}">{$news_widget.news_title}</a></div>
        <div class="time">{$news_widget.news_added|dateformat}</div>
    </li>
    {/foreach}
</ul>

<!-- Ende News Widget //-->