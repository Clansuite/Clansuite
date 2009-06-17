{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

<!--
teamname1
teamname2
team1_score
team2_score
//-->


<!-- Start Widget Latestmatches //-->
<div id="widget_latestmatches">
 	<table class="latestmatches">
		<tr>
    		<td><span class="latestmatches_team1">{$matches.teamname1}</span></td>
    		<td><span class="team_divider"> - </span></td>
    		<td><span class="latestmatches_team2">{$matches.teamname2}</span></td>
    		<td><span class="latestmatches_result1">{$matches.team1_score}</span></td>
			<td><span class="score_divider"> : </span></td>
			<td><span class="latestmatches_result2">{$matches.team2_score}</span></td>
  		</tr>
	</table>
</div>
<!-- End Widget Latestmatches //-->