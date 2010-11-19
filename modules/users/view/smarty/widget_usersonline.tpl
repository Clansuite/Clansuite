{* Debug: *}

{$usersonline|var_dump}

<!-- Start: widget_usersonline @ module users // -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
    <tr>
        <td class="td_header" colspan="2">{t}User Online{/t}</td>
    </tr>
    <tr>
        <td class="cell1">
            {*    {$usersonline.number_guests}
                <br />
                {$usersonline.number_registered}
                <br />
                {$usersonline.nicks}
                <br />
                {gravatar email="`$random_user.email`"}
                <br />
                {$random_user.country}    
             *}            
        </td>
    </tr>

</table>

<!-- End: users_online widget // -->