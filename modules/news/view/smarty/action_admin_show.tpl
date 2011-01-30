{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
    {$pagination_links|var_dump}
    <hr>
    {$news|var_dump}
    <hr>
    {$newscategories|var_dump}
*}

{jqconfirm}

{modulenavigation}
<div class="ModuleHeading">{t}News - Show{/t}</div>
<div class="ModuleHeadingSmall">{t}You can edit, delete, search and comment on news entries.{/t}</div>

{$datagrid}