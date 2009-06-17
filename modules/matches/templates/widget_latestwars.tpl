{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

<!--
latest		(man muss einstellen können wieviele Matches angezeigt werden, am Besten im Backend)
//-->


<!-- Start Widget Latestmatches //-->
<div id="widget_latestmatches">
	<span class="latestmatches_row">{$matches.latest}</span>
</div>
<!-- End Widget Latestmatches //-->