{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Reviews {html_alt_table loop=$reviews}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$reviews|@var_dump}
    <hr>
    {$reviewcategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Reviews</div>
<div class="ModuleHeadingSmall">Sie können Reviews hinzufügen, verändern und löschen.</div>