{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$newsarchiv}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$news|@var_dump}
    <hr>
    {$newscategories|@var_dump}
*}

<!-- start jq confirm dialog -->
{jqconfirm}
<!-- end jq confirm dialog -->

{modulenavigation}

{$datagrid}

