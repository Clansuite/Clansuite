<form action="index.php?mod=messaging&action=create" method="post">
<table cellpadding="5" cellspacing="0" border="0" width="100%">
    <tr class="tr_header">
            <td width="140px">
                {t}Options{/t}
            </td>
            <td>
                {t}Results{/t}
            </td>
    </tr>
    <tr>
        <td class="cell1">
            {$menu}
        </td>
        <td class="cell1" align="center">
            {if !empty($message_errors)}
            <table cellpadding="5" cellspacing="0" border="0" width="400px">
                <tr>
                    <td class="red_cell">
                        {if $message_errors.no_message == 1}
                            {t}No message given.{/t}<br />
                        {/if}
                        {if $message_errors.no_users == 1}
                            {t}No users given.{/t}<br />
                        {/if}
                        {if $message_errors.no_headline == 1}
                            {t}No headline given.{/t}<br />
                        {/if}
                        {if $message_errors.no_infos == 1}
                            {t}You haven't supplied any informations at all.{/t}<br />
                        {/if}
                        {if $message_errors.users_not_found == 1}
                            {t}The following users couldn't be found in the database:{/t}<br />
                            <b>{$message_errors.users}</b>
                        {/if}
                    </td>
                </tr>
            </table>
            {/if}

            <table cellpadding="5" cellspacing="0" border="0" width="400px">
                <tr>
                    <td class="cell2">
                        {t}To{/t}:
                    </td>
                    <td class="cell1">
                        <input type="text" value="{if isset($message_infos.from_user)}{$message_infos.from_user}{else}{$smarty.post.info.to|escape:"html"}{/if}" class="input_text" name="info[to]" />
                    </td>
                </tr>
                <tr>
                    <td class="cell2">
                        {t}Headline{/t}:
                    </td>
                    <td class="cell1">
                        <input type="text" value="{if isset($message_infos.headline)}Re: {$message_infos.headline}{else}{$smarty.post.info.headline|escape:"html"}{/if}" class="input_text" name="info[headline]" />
                    </td>
                </tr>
                <tr>
                    <td class="cell2">
                        {t}Message{/t}:
                    </td>
                    <td class="cell1">
                        {if isset($message_infos.message) }
                            <textarea cols="50" rows="15" class="input_textarea" name="info[message]">


[quote]
{$message.message}
[/quote]</textarea>
                        {else}
                            <textarea cols="50" rows="15" class="input_textarea" name="info[message]">{$smarty.post.info.message|escape:"html"}</textarea>
                        {/if}
                    </td>
                </tr>
                <tr>
                    <td align="right" colspan="2" class="cell2">
                        <input type="submit" name="submit" class="ButtonGreen" value="{t}Send message{/t}" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>