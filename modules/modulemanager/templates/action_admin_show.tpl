<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <th class="td_header_small">{t}Modulename{/t}</th>
        <th class="td_header_small">{t}Information{/t}</th>
        <th class="td_header_small">{t}Actions{/t}</th>
    </tr>
    {foreach from=$modules item=mod}
    <tr>
        <td class="cell1" width="150" align="center">{$mod.name}</td>
        <td class="cell2"></td>
        <td class="cell1"></td>
    </tr>
    {/foreach}
</table>
