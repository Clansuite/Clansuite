{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Shoutbox {html_alt_table loop=$shoutbox}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$shoutbox|@var_dump}
    <hr>
    {$shoutboxcategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Shoutbox</div>
<div class="ModuleHeadingSmall">Sie können Shoutboxbeiträge hinzufügen, verändern und löschen.</div>