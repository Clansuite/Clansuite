{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$downloads|@var_dump}
    <hr>
    {$downloadcategories|@var_dump}
*}

<!-- Module Heading -->
<div class="ModuleHeading">Downloads</div>
<div class="ModuleHeadingSmall">Sie k�nnen Downloads hinzuf�gen, ver�ndern und l�schen.</div>