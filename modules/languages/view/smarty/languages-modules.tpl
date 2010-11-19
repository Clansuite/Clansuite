{* {$modules|var_dump} *}

<table width="100%" cellspacing="0" cellpadding="0" border="0">

    <tbody>
        <tr>
            <td class="td_header_small">#</td>
            <th class="td_header_small">Module</th>
            <th class="td_header_small">Actions</th>
        </tr>

       {foreach $modules as $module}
        <tr>
            <td>{$module@iteration}</td>
            <td>{$module.name}</td>
            <td>Scan Language Tags</td>
        </tr>
       {/foreach}

   </tbody>

</table>