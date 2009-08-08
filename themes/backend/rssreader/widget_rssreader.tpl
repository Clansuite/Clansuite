{* {$feed|@var_dump} *}

<!-- Start: Rssreader Widget from Module Rssreader  //-->



<div class="rss-inside">
{literal}
<script type="text/javascript">
	$(function() {
		$("#accordion").accordion({
			autoHeight: false
		});
	});
	</script>
{/literal}
<div id="accordion">
	{foreach from=$feed->get_items() item=i}
	
		<h3><a href="#">{$i->get_title()}</a></h3>
		<div>
			<p>
			<span style="float: right; font-size:10px;">{$i->get_date()}</span>
			{$i->get_description()}
			<span style="float:right; font-size:10px;"><a href="{$i->get_link()}" class="more" target="_blank" >[...mehr]</a></span>
			</p>
		</div>
		
	{/foreach}
</div>

</div>

<!-- End: Rssreader Widget Module Template //-->


