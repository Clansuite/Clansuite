{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Quotes {html_alt_table loop=$quotes}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$quotes|@var_dump}
    <hr>
    {$quotescategories|@var_dump}
*}

<!-- Module Heading -->
<div class="ModuleHeading">Quotes</div>
<div class="ModuleHeadingSmall">Sie können Quotes hinzufügen, verändern und löschen.</div>