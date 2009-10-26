{* {$widget_newscategories_list|@var_dump} *}

<!-- Start News-Categories Selection Widget from Theme Newscore /-->

<div class="widget_head">
	<span class="widget_title">News Categories</span>
</div>
<ul>
	{foreach item=widget_newscategories_list from=$widget_newscategories_list}
	<li>
		<a href="{$www_root}/index.php?mod=news&action=show&page=1&cat={$widget_newscategories_list.cat_id}"> {$widget_newscategories_list.CsCategories.name} ({$widget_newscategories_list.sum})</a>
	</li>
	{/foreach}
</ul>
<!-- Ende News-Categories Selection Widget from Theme Newscore /-->