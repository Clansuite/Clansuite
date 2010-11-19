{* {$themes|var_dump} *}

<table width="100%" cellspacing="0" cellpadding="0" border="0">

    <tbody>
        <tr>
            <td class="td_header_small">#</td>
            <th class="td_header_small">Theme</th>
            <th class="td_header_small">Actions</th>
        </tr>

       {foreach $themes as $theme}
        <tr>
            <td>{$theme@iteration}</td>
            <td>{$theme.name}</td>
            <td>Scan Language Tags</td>
        </tr>
       {/foreach}

   </tbody>

</table>