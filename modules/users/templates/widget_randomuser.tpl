{* {$random_user|var_dump} *}

<!-- Start: random_user widget @ from Module Users // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2">{t}Random User{/t}</td>
    </tr>
    <tr>
        <td class="cell1">
                {$random_user.user_id}
                <br />
                {$random_user.nick}
                <br />
                {$random_user.email} 
                <br />
                {$random_user.country}
                <br />
                {$random_user.joined|duration} ago )
                <br />
        </td>
    </tr>

</table>

<!-- End: random_user widget // -->