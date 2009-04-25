{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Blog {html_alt_table loop=$blog}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$blog|@var_dump}
    <hr>
    {$blogcategories|@var_dump}
*}
{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Blog</div>
<div class="ModuleHeadingSmall">Sie können Ihren Blog verwalten.</div>