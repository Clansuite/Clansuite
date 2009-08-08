 {*  {$widget_nextmatches|@var_dump} *}


<!-- Start Widget Nextmatches from Module Matches //-->

<div class="news_widget" id="widget_nextmatches" width="100%">

{literal}
    <script type="text/javascript">
	
    </script>
{/literal}
	
    <h2 class="td_header">{t}Next Matches{/t}</h2>

<!-- Start Nextmatches-Slider from Module Matches //-->
<div id="nextmatches_slider">

	<ul>
		{foreach item=match from=$widget_nextmatches}
		<li>
			<table>
				<tr>
					<td>{$match.team1name}<br><img class="logo_left" href="{$match.team1logo}"></td>
					<td><span class="team_divider"> vs </span><br>{$match.matchtime}</td>
					<td>{$match.team1name}<br><img class="logo_right" href="{$match.team1logo}"></td>
				</tr>
			</table>
		</li>
		{/foreach}
	</ul>
 	
</div>
<!-- End Nextmatches-Slider from Module Matches //-->
</div>

<!-- End Widget Nextmatches //-->