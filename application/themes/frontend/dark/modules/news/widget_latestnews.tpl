{* {$widget_latestnews|var_dump} *}

<!-- Start News Widget from Module News -->

<table class="tborder" align="center" border="0" cellpadding="2" cellspacing="1" width="100%">
<thead>
	<tr>
		<td class="tcat">
			<a href="javascript:animatedcollapse.toggle('collmenu_latestnews')"><img src="{$www_root_themes_frontend}black/images/toggleportal.png" border=0  vspace="0" hspace="4" style="float:left"/>
			<strong>{t}Recent news{/t}</strong></a>
		</td>
	</tr>
</thead>
<tbody>
	<tr valign="top">
		<td class="alt1" style="padding: 0pt;">
			<div id="collmenu_latestnews" style="display:show">

				<table width="100%" cellpadding="0" cellspacing="0">
					<tr style="background: #515151;">
						<td class="td_header_small" style="padding-left: 3px;">Titel</td>
						<td class="td_header_small" width="70">Datum</td>
					</tr>
					{foreach item=news_item from=$widget_latestnews}
					<tr>
						<td class="cell1" style="padding-left: 3px;"><a href="index.php?mod=news&action=showone&id={$news_item.news_id}">{$news_item.news_title}</a></td>
						<td class="cell2" width="70">{$news_item.created_at|date_format}</td>
					</tr>
					{/foreach}
				</table>

			</div>
		</td>
	</tr>
</tbody>
</table>
<script>animatedcollapse.addDiv('collmenu_latestnews', 'persist=1,hide=0');</script>
<br>


<!-- End News Widget -->
