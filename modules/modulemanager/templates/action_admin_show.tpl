{* DEBUG OUTPUT of assigned Arrays:
   {$smarty.session|@var_dump}
   <hr>
    {$modules|@var_dump}
*}

{modulenavigation}
<div class="ModuleHeading">{t}Modulemanager{/t}</div>
<div class="ModuleHeadingSmall">{t}Administrate the modules. You can add, delete, activate, deactivate modules.{/t}</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <th class="td_header_small">{t}Modulename{/t}</th>
        <th class="td_header_small">{t}Version{/t}</th>
        <th class="td_header_small">{t}Description{/t}</th>
        <th class="td_header_small">{t}Actions{/t}</th>
    </tr>
    {foreach from=$modules item=module}
    <tr>
        <td class="cell1" width="150" align="center">{* {image url="`$module.icon`"} *} {$module.name|capitalize}</td>
        <td class="cell2"></td>
        <td class="cell1"></td>
        <td class="cell2">
            <a href="" type="button" class="delete" title="{$module.name}" />Activate</a>
            <a href="" type="button" class="delete" title="{$module.name}" />Delete</a>
            <a href="" type="button" class="delete" title="{$module.name}" />Options</a>
        </td>
    </tr>
    {/foreach}
</table>