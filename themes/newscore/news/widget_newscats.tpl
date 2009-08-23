  {* {$widget_newscats|@var_dump} *}
  
<div class="widget_head">
	<span class="widget_title">News Categories</span>
</div>
<ul>
	{foreach item=widget_newscats from=$widget_newscats}
	<li>
		{$widget_newscats.CsCategories.name} ({$widget_newscats.sum})
	</li>
	{/foreach}
</ul>
 