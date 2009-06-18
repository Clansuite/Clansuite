{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$latestmatches|@var_dump}
*} 

<!--
teamname1
teamname2
team1_score
team2_score
//-->


<!-- Start Widget Latestmatches //-->
<div id="widget_latestmatches">Latest Matches
 	<table class="latestmatches">
		<tr>
		{*
    		<td><span class="latestmatches_team1">{$match.teamname1}</span></td>
    		<td><span class="team_divider"> - </span></td>
    		<td><span class="latestmatches_team2">{$match.teamname2}</span></td>
    		<td><span class="latestmatches_result1">{$match.team1_score}</span></td>
			<td><span class="score_divider"> : </span></td>
			<td><span class="latestmatches_result2">{$match.team2_score}</span></td>
	    *}
  		</tr>
	</table>
</div>
<!-- End Widget Latestmatches //-->