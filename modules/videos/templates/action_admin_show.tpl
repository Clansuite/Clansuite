{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Videos {html_alt_table loop=$videos}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$videos|@var_dump}
    <hr>
    {$videoscategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Videos</div>
<div class="ModuleHeadingSmall">Sie können Videos hinzufügen, verändern und löschen.</div>