{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
   {$matches|@var_dump}
*}

<!--
latest		(man muss einstellen können wieviele Matches angezeigt werden, am Besten im Backend)
//-->


<!-- Start Widget Nextmatches //-->
<div id="widget_nextmatches">
	<span class="nextmatches_row">{$matches.next}</span>
</div>
<!-- End Widget Nextmatches //-->