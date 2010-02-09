{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Categories {html_alt_table loop=$categories}   {/if}
    <hr>
    {$categories|@var_dump}
*}

{jqconfirm}

{modulenavigation}
<!-- Module Heading -->
<div class="ModuleHeading">Categories</div>
<div class="ModuleHeadingSmall">{t}You can create, edit and delete Categories.{/t}</div>

{$datagrid}