{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}
<!--
teamlogo1
teamlogo2
matchtime
//-->

<!-- Start Widget Topmatch //-->
<div id="widget_topmatch">
	<div class="teamlogo1">{$matches.teamlogo1}</div>				<!-- float left 30% | Logo muss automatisch verkleinert werden!! //-->
	<div class="team_divider">vs</div>				
	<div class="teamlogo2">{$matches.teamlogo2}</div>				<!-- float left rest | Logo muss automatisch verkleinert werden!! //-->
	<div style="clear:both"></div>									<!-- break float //-->
	<div class="matchtime">{$matches.matchtime}</div>				
</div>
<!-- End Widget Topmatch //-->
