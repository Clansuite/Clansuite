{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Matches {html_alt_table loop=$matches}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$matches|@var_dump}
    <hr>
    {$matchescategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Matches</div>
<div class="ModuleHeadingSmall">Sie k�nnen Matches hinzuf�gen, ver�ndern und l�schen.</div>