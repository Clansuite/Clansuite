{* {$last_registered_users|var_dump} *}

<!-- Start: last_registered_users widget @ Standard Theme // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2">{t}Last registered users{/t}</td>
    </tr>
    {foreach item=lastuser from=$last_registered_users}
    <tr>
        <td>
            {$lastuser.user_id}
            {$lastuser.email}
            {$lastuser.nick}
            {$lastuser.country}
        </td>
    </tr>
    {/foreach}
</table>

<!-- End: last_registered_users widget // -->