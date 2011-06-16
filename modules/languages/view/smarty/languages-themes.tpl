{* {$themes|var_dump} *}

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="Datagrid">
<tbody>
    <tr>
        <th class="ColHeader">#</td>
        <th class="ColHeader">Theme</th>
        <th class="ColHeader">Actions</th>
    </tr>

   {foreach $themes as $theme}
    <tr>
        <td align="center">{$theme@iteration}</td>
        <td><h3 class="name">{$theme.name}</h3></td>
        <td><a href="index.php?mod=languages&sub=admin&action=scantheme&type={$theme.type}&name={$theme.name}">Scan Language Tags</a></td>
    </tr>
   {/foreach}

</tbody>
</table>