{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$widget_latestfiles|@var_dump}
*}

<h2 class="td_header">{t}LatestFiles{/t}</h2>
<div class="widget_latestfiles" id="widget_latestfiles">
    <div class="widget_list">
    {foreach item=downloads from=$widget_latestfiles}
		<span class="widget_downloadname">{$downloads.name}</span>
        <span class="widget_added_date">{$downloads.added_date}</span>
    {/foreach}
    </div>
</div>