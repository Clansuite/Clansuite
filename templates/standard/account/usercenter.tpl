<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td align="center">
            {translate}You're logged in as {/translate}<b>{$smarty.session.user.nick}</b>
        </td>
    </tr>
    {if $smarty.session.user.rights.access_acp==1}
        <tr>
            <td align="center">
                <a href="index.php?mod=admin">{translate}Admin Control Panel{/translate}</a>
            </td>
        </tr>
    {/if}
    <tr>
        <td align="center">
            <a href="index.php?mod=account&sub=options">{translate}Options{/translate}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=account&sub=profile">{translate}Profile{/translate}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=account&action=logout">{translate}Logout{/translate}</a>
        </td>
    </tr>
</table>
