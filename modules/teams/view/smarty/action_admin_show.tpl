{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Teams {html_alt_table loop=$teams}   {/if}
    {$pagination_links|var_dump}
    <hr>
    {$teams|var_dump}
    <hr>
    {$teamscategories|var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Teams</div>
<div class="ModuleHeadingSmall">Sie können teams hinzufügen, verändern und löschen.</div>