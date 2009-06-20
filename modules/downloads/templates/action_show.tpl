{* DEBUG OUTPUT of assigned Arrays:
   {downloads|@var_dump}
   {downloadcategories|@var_dump}
*}

<div class="content" id="downloads_show">
	<div class="download_top_widget">
    	<div class="widget" id="widget_downloads">{load_module name="downloads" action="widget_top_files"}</div>					<!-- float left //-->
    	<div class="widget" id="widget_downloads">{load_module name="downloads" action="widget_latest_files"}</div>					<!-- float left //-->
        <div style="clear:both"></div>																								<!-- clear floating //-->
    </div>
    <div class="download_categories">
    	{foreach name=downloads item="downloads" from=$downloads}
    	<span class="download_cat">{$downloads.downloadcategories_id}</span>																	<!-- float left, fixed width //-->
		{/foreach}
        <div style="clear:both"></div>																								<!-- clear floating //-->
    </div>
    
</div>