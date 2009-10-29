{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$latestmatches|@var_dump}  
*} 

<!-- Start Widget Nextmatches from Module Matches -->

<div class="news_widget" id="widget_latestmatches" width="100%">

    <h2 class="td_header">{t}Latest Matches{/t}</h2>

 	<table class="latestmatches">
		<tr>
		{*
		{foreach item=match from=$latestmatches}
    		<td><span class="latestmatches_team1">{$match.teamname1}</span></td>
    		<td><span class="team_divider"> - </span></td>
    		<td><span class="latestmatches_team2">{$match.teamname2}</span></td>
    		<td><span class="latestmatches_result1">{$match.team1_score}</span></td>
			<td><span class="score_divider"> : </span></td>
			<td><span class="latestmatches_result2">{$match.team2_score}</span></td>
	    {/foreach}
	    *}
  		</tr>
	</table>

</div>
<!-- End Widget Latestmatches -->