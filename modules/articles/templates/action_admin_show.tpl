{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Articles {html_alt_table loop=$articles}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$articles|@var_dump}
    <hr>
    {$articlecategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Artikel</div>
<div class="ModuleHeadingSmall">Sie können Artikel hinzufügen, verändern und löschen.</div>