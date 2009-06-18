{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$topmatch|@var_dump}
*}
<!--
teamlogo1
teamlogo2
matchtime
//-->

<!-- Start Widget Topmatch //-->
<div id="widget_topmatch">Topmatch
    {*
    {$topmatch.team1name}
    {$topmatch.team2name}
	<div class="teamlogo1">{$topmatch.teamlogo1}</div>				<!-- float left 30% | Logo muss automatisch verkleinert werden!! //-->
	<div class="team_divider">vs</div>
	<div class="teamlogo2">{$topmatch.teamlogo2}</div>				<!-- float left rest | Logo muss automatisch verkleinert werden!! //-->
	<div style="clear:both"></div>									<!-- break float //-->
	<div class="matchtime">{$topmatch.matchtime}</div>
	*}
</div>
<!-- End Widget Topmatch //-->
