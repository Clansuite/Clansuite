{* {$cronjobs|var_dump} *}
{modulenavigation}
<div class="ModuleHeading">{t}Cronjobs - timed and repetitive Tasks{/t}</div>
<div class="ModuleHeadingSmall">{t}Administrate the timed tasks. You can add, delete, activate, deactivate tasks.{/t}</div>

<table cellspacing="0" cellpadding="0" border="0" align="center">

    <tr class="tr_header">
        <td align="center">Status</td>
        <td align="center">Name of Task</td>
        <td align="center">Description of Task</td>
        <td align="center">Last Run</td>
        <td align="center">Next Run</td>
        <td align="center">Run Frequency</td>
        <td align="center">Action</td>
    </tr>

    {foreach item=cronjob from=$cronjobs}
    <tr class="tr_row1">
        <td align="center">{$cronjob.status}</td>
        <td align="center">{$cronjob.name}</td>
        <td align="center">{$cronjob.description}</td>
        <td align="center">{$cronjob.lastrun}</td>
        <td align="center">{$cronjob.nextrun}</td>
        <td align="center">{$cronjob.runfrequency}</td>
        <td align="center">
                            <input class="ButtonGreen"  type="button" value="Activate"/>
                            <input class="ButtonOrange" type="button" value="Modifiy"/>
                            <input class="ButtonRed"    type="button" value="Deactivate"/>
        </td>
    </tr>
    {/foreach}

</table>