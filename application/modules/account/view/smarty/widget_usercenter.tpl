<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
       <td class="td_header" colspan="2">{t}User Panel{/t}</td>
    </tr>
    <tr>
        <td align="center">
            {t}You're logged in as {/t}<b>{$smarty.session.user.nick}</b>
        </td>
    </tr>
    {if isset($smarty.session.user.rights.permission_access) && $smarty.session.user.rights.permission_access == 1}
    <tr>
        <td class="td_header">
            <a href="index.php?mod=controlcenter">{t}Control Center{/t}</a>
        </td>
    </tr>
    {/if}
    <tr>
        <td align="center">
            <a href="index.php?mod=account&amp;sub=options">{t}Options{/t}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=account&amp;sub=profile">{t}Profile{/t}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
       {* {$smarty.session.user.rights|var_dump} *}
            <a href="index.php?mod=messaging&amp;action=show">{if isset($smarty.session.user.rights.use_messaging_system) && $smarty.session.user.rights.use_messaging_system == 1}{t}Messages{/t} ( {* {load_module name="messaging" action="get_new_messages_count"} *} ){/if}</a>
        </td>
    </tr>
    <tr>
        <td align="center">
            <a href="index.php?mod=account&amp;action=logout">{t}Logout{/t}</a>
        </td>
    </tr>
</table>