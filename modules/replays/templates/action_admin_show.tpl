{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Replays {html_alt_table loop=$replays}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$replays|@var_dump}
    <hr>
    {$replayscategories|@var_dump}
*}

<!-- Module Heading -->
<div class="ModuleHeading">Replays</div>
<div class="ModuleHeadingSmall">Sie können Replays hinzufügen, verändern und löschen.</div>