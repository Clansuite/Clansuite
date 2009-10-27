 {* {$widget_newscategories_list|@var_dump} *}
  
<div class="widget_head">
	<span class="widget_title">News Categories</span>
</div>
<ul>
	{foreach item=newscategory from=$widget_newscategories_list}
	<li>
		<a href="{$www_root}/index.php?mod=news&action=show&page=1&cat={$newscategory.cat_id}"> {$newscategory.CsCategories.name} ({$newscategory.sum})</a>
	</li>
	{/foreach}
</ul>
 