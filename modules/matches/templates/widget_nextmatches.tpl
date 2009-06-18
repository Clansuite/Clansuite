{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$nextmatches|@var_dump}
*}

<!-- Start Widget Nextmatches from Module Matches //-->

<div class="news_widget" id="widget_nextmatches" width="100%">

    <h2 class="td_header">{t}Next Matches{/t}</h2>

 	<table class="nextmatches">
		<tr>
		{*
		{foreach item=match from=$nextmatches}
    		<td><span class="nextmatches_team1">{$match.teamname1}</span></td>
    		<td><span class="team_divider"> - </span></td>
    		<td><span class="nextmatches_team2">{$match.teamname2}</span></td>
    		<td><span class="nextmatches_time">{$match.matchtime}</span></td>
        {/foreach}
        *}
  		</tr>
	</table>

</div>
<!-- End Widget Nextmatches //-->