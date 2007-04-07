<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td align="center">
            {translate}You're logged in as {/translate}<b>{$smarty.session.user.nick}</b>
        </td>
    </tr>
    {if $smarty.session.user.rights.cc_access==1}
    <tr>
        <td align="center">
            <a href="index.php?mod=admin">{translate}Control Center{/translate}</a>
        </td>
    </tr>
    {/if}
    <tr>
        <td align="center">
            <a href="index.php?mod=account&amp;sub=options">{translate}Options{/translate}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=account&amp;sub=profile">{translate}Profile{/translate}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=messaging&amp;action=show">{if $smarty.session.user.rights.use_messaging_system == 1}{translate}Messages{/translate} ({mod name="messaging" func="get_new_messages_count"}){/if}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=account&amp;action=logout">{translate}Logout{/translate}</a>
        </td>
    </tr>
</table>
