{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Clancash {html_alt_table loop=$clancash}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$clancash|@var_dump}
    <hr>
    {$clancashcategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Clankasse</div>
<div class="ModuleHeadingSmall">Sie können Clanbeiträge hinzufügen, verändern und löschen.</div>