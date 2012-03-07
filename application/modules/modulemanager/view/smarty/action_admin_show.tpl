{* {$modules|var_dump} *}
{modulenavigation}
<div class="ModuleHeading">{t}Modulemanager{/t}</div>
<div class="ModuleHeadingSmall">{t}Administrate the modules. You can add, delete, activate, deactivate modules.{/t}</div>

<br />

There are {$modules_summary.counter} Modules installed.

<br /><br />

<table cellpadding="0" cellspacing="0" border="0" width="100%">

    <tr>
        <td class="td_header_small">#</th>
        <th class="td_header_small">{t}Modulename{/t}</th>
        <th class="td_header_small">{t}Version{/t}</th>
        <th class="td_header_small">{t}Description{/t}</th>
        <th class="td_header_small">{t}Actions{/t}</th>
    </tr>

    {foreach $modules as $module}

    <tr>
        <td class="cell1">{$module.id}</td>
        <td class="cell1" width="150" align="center">{* {image url="`$module.icon`"} *} {$module.name|capitalize}</td>
        <td class="cell2">{getarraykey array=$module.info key=version}</td>
        <td class="cell1">{if isset($module.info.description) and empty($module.info.description) == false} {$module.info.description} {else} -- {/if}</td>
        <td class="cell2">

            {* Add User Actions *}
            <a href="/" type="button" class="delete" title="Activate {$module.name}">Activate</a>
            &nbsp;|&nbsp;
            <a href="/" type="button" class="delete" title="Delete {$module.name}">Delete</a>
            &nbsp;|&nbsp;

            {* Add Developer Actions *}

            {if $smarty.const.DEVELOPMENT == true}

            <a href="{$www_root}index.php?mod=modulesettings&sub=admin&action=show&modulename={$module.name}"
               type="button" class="delete" title="Edit {$module.name} Settings">Settings</a>
            {*
            &nbsp;|&nbsp;
            <a href="/" type="button" class="delete" title="Create Pear Packages">Create Pear Package</a>
            &nbsp;|&nbsp;
            <a href="/" type="button" class="delete" title="Create Phar">Create Phar</a>
            &nbsp;|&nbsp;

            *}
            {/if}
        </td>
    </tr>

    {/foreach}

</table>