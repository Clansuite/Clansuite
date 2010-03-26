{* DEBUG OUTPUT of assigned Arrays:
   {downloads|@var_dump}
   {downloadcategories|@var_dump}
*}

<div class="content" id="downloads_show_single">

	<div class="download_navigation">{$downloads.downloadcategories_id}</div>	<!-- Download-Categories needed to be included here for navigational issues -->
	<div class="download_infos">
    	<span class="download_name">{$downloads.name}</span>
        <span class="download_time">{$downloads_added_date|date_format:"%d.%m.%y, %H:%M:%S"}</span>
        <span class="download_desciption">{$downloads.description}</span>
	</div>
	<div class="download_details">
		<span class="download_filetype">{%downloads.filetype}</span>
        <span class="download_filesize">{$download.filesize}</span>
        <span class="download_rating_id">{$downloads.download_rating}</span>
    </div>
	<div class="download_get">
		<span class="download_select_mirrors">{$downloads.download_mirrors}</span>
		<span class="download_getfile">{$downloads.filepath}</span>
    </div>
	    
</div>