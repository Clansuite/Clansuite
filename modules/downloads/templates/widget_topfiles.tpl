{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$downloads_topfiles_widget|@var_dump}
*} 

<h2 class="td_header">{t}TopFiles{/t}</h2>
<div class="widget_topfiles" id="widget_topfiles">
    <div class="widget_list">
    {foreach item=downloads from=$downloads_widget_topfiles}
		<span class="widget_downloadname">{$downloads.name}</span>
        <span class="widget_downloadrating">{$downloads.download_rating}</span>
    {/foreach}
    </div>
</div>