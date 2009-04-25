{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Coverages {html_alt_table loop=$coverages}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$coverages|@var_dump}
    <hr>
    {$coveragescategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Coverages</div>
<div class="ModuleHeadingSmall">Sie können Coverages hinzufügen, bearbeiten und verwalten.</div>