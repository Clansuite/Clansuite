{* {$last_registered_users|var_dump} *}

<!-- Start: last_registered_users widget @ Standard Theme // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2">{t}Last registered users{/t}</td>
    </tr>
    <tr>
        <td class="cell1">
            {foreach item=lastuser from=$last_registered_users}
                {* {$lastuser.user_id} *}
                {$lastuser.nick}
                {* {$lastuser.email} *}           
                {* {$lastuser.country} *}
                ( {$lastuser.joined|duration} ago )              
                <br />
            {/foreach}
        </td>
    </tr>
    
</table>

<!-- End: last_registered_users widget // -->