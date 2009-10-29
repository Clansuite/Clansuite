{* DEBUG OUTPUT of assigned Arrays:
   {$downloads|@var_dump}
   {$downloadcategories|@var_dump}
   {$downloads|@var_dump}
*}

<div class="content" id="downloads_show">
	<div class="download_top_widget">
    	<div class="widget" id="widget_downloads">{load_module name="downloads" action="widget_topfiles" item=5}</div>					<!-- float left -->
    	<div class="widget" id="widget_downloads">{load_module name="downloads" action="widget_latestfiles" item=5}</div>					<!-- float left -->
        <div style="clear:both"></div>																								<!-- clear floating -->
    </div>
    <div class="download_categories">
    	{* {foreach item=download from=$downloads}
    	<span class="download_cat">{$downloads.downloadcategories_id}</span>														<!-- float left, fixed width, Download-Categories Strukture needed to be included here for navigational issues -->
		{/foreach} *}
        <div style="clear:both"></div>																								<!-- clear floating -->
    </div>

</div>