{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

<!-- Start Widget Nextmatches from Module Matches //-->

<div class="news_widget" id="widget_nextmatches" width="100%">

    <h2 class="td_header">{t}Next Matches{/t}</h2>

 	<table class="nextmatches">
		<tr>
		{*
    		<td><span class="nextmatches_team1">{$matches.teamname1}</span></td>
    		<td><span class="team_divider"> - </span></td>
    		<td><span class="nextmatches_team2">{$matches.teamname2}</span></td>
    		<td><span class="nextmatches_time">{$matches.matchtime}</span></td>
        *}
  		</tr>
	</table>

</div>
<!-- End Widget Nextmatches //-->