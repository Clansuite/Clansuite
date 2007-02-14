<form action="index.php?mod=messaging&action=create" method="POST">
<table cellpadding="5" cellspacing="0" border="0" width="100%">
    <tr class="tr_header">
            <td width="140px">
                {translate}Options{/translate}
            </td>
            <td>
                {translate}Results{/translate}
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
                            {translate}No message given.{/translate}<br />
                        {/if}
                        {if $message_errors.no_users == 1}
                            {translate}No users given.{/translate}<br />
                        {/if}
                        {if $message_errors.no_headline == 1}
                            {translate}No headline given.{/translate}<br />
                        {/if}
                        {if $message_errors.no_infos == 1}
                            {translate}You haven't supplied any informations at all.{/translate}<br />
                        {/if}
                        {if $message_errors.users_not_found == 1}
                            {translate}The following users couldn't be found in the database:{/translate}<br />
                            <b>{$message_errors.users}</b>
                        {/if}
                    </td>
                </tr>
            </table>
            {/if}

            <table cellpadding="5" cellspacing="0" border="0" width="400px">
                <tr>
                    <td class="cell2">
                        {translate}To{/translate}:
                    </td>
                    <td class="cell1">
                        <input type="text" value="{if isset($message_infos.from_user)}{$message_infos.from_user}{else}{$smarty.post.info.to|escape:"html"}{/if}" class="input_text" name="info[to]" />
                    </td>
                </tr>
                <tr>
                    <td class="cell2">
                        {translate}Headline{/translate}:
                    </td>
                    <td class="cell1">
                        <input type="text" value="{if isset($message_infos.headline)}Re: {$message_infos.headline}{else}{$smarty.post.info.headline|escape:"html"}{/if}" class="input_text" name="info[headline]" />
                    </td>
                </tr>
                <tr>
                    <td class="cell2">
                        {translate}Message{/translate}:
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
                        <input type="submit" name="submit" class="ButtonGreen" value="{translate}Send message{/translate}" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>