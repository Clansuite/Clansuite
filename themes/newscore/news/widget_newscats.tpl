{* {$widget_newscats|@var_dump} *}

<!-- Start News-Categories Selection Widget from Theme Newscore /-->

<div class="widget_head">
	<span class="widget_title">News Categories</span>
</div>
<ul>
	{foreach item=widget_newscats from=$widget_newscats}
	<li>
		<a href="{$www_root}/index.php?mod=news&action=show&page=1&cat={$widget_newscats.cat_id}"> {$widget_newscats.CsCategories.name} ({$widget_newscats.sum})</a>
	</li>
	{/foreach}
</ul>
<!-- Ende News-Categories Selection Widget from Theme Newscore /-->