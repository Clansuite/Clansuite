<!-- Start of Template: \trunk\modules\news\templates\action_admin_create.tpl -->

{* DEBUG OUTPUT of assigned Arrays:
    {$smarty.session|@var_dump}
    {if $smarty.const.DEBUG eq "1"} Debug of Newsarchiv {html_alt_table loop=$news}   {/if}
    {$pagination_links|@var_dump}
    <hr>
    {$news|@var_dump}
*}

{modulenavigation}
<div class="ModuleHeading">{t}News - Administration{/t}</div>
<div class="ModuleHeadingSmall">{t}You can write a new Article.{/t}</div>

{$form}

<!-- End of Template -->