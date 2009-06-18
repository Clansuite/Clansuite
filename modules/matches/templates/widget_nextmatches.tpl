{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

<!--
teamname1
teamname2
matchtime
//-->


<!-- Start Widget Nextmatches //-->
<div id="widget_nextmatches">Nextmatches
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