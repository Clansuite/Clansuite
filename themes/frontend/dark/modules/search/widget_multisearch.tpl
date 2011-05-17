{move_to target="pre_head_close"}
<script type="text/javascript" charset="utf-8">
// <![CDATA[

	function webSearch(mod, modtext, modfields){
		document.getElementById('qmod').value = mod;
		document.getElementById('qfields').value = modfields;
		document.getElementById('selectedsearch').innerHTML=modtext;
	}

// ]]>
</script>
{/move_to}


<div id="searchForm">
	<span class="note">{t}choose modul, type text and enter{/t}</span>
	<h2><img src="{$www_root_theme}images/icons/qsearch.gif" alt="" />{t}QUICKSEARCH{/t}</h2>
	<form name="multisearch" method="post" action="index.php?mod=search&action=multisearch">
		<input type="hidden" id="qfields" name="qfields" value="title,body">
		<input type="hidden" id="qmod" name="qmod" value="news">

		<ul class="dropDown">
			<li>
				<a href="#" title="{t}Search{/t}"><span id="selectedsearch">NEWS</span>
				<ul class='dropDownm'>
					<li><a href="javascript:void(0);" onclick="webSearch('news', 'NEWS', 'title,body,comments');" title="{t}Search News{/t}">{t}NEWS{/t}</a></li>
					<li><a href="javascript:void(0);" onclick="webSearch('forum', 'FORUM', 'themes,posts');" title="{t}Search Forum{/t}">{t}FORUM{/t}</a></li>
					<li><a href="javascript:void(0);" onclick="webSearch('gallery', 'GALLERY', 'title,descripcion,author');" title="{t}Search Gallery{/t}">{t}GALLERY{/t}</a></li>
					<li><a href="javascript:void(0);" onclick="webSearch('events', 'EVENTS', 'title,descripcion,date');" title="{t}Search Events{/t}">{t}EVENTS{/t}</a></li>
					<li><a href="javascript:void(0);" onclick="webSearch('newsletter', 'NEWSLETTER', 'title,body');" title="{t}Search Newsletter{/t}">{t}NEWSLETTER{/t}</a></li>
				</ul>
			</li>
		</ul>
		<input type="text" name="q" value="Suchbegriff eingeben..." onFocus="this.value=''" onblur="if(this.value=='') this.value='Suchbegriff eingeben...';" class="multisearchInput" />

	</form>
</div>
