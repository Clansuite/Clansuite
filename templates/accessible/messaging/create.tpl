<form action="index.php?mod=messaging&action=create" method="post" accept-charset="UTF-8">
<table cellpadding="5" cellspacing="0" border="0" width="400" style="margin: auto">
    <tr class="tr_header">
            <td>
                {translate}Infos{/translate}
            </td>
            <td>
                {translate}Results{/translate}
            </td>
    </tr>
            {*
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
                            <strong>{$message_errors.users}</strong>
                        {/if}
                    </td>
                </tr>
            </table>
            {/if}
            *}
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
{$message_infos.message}
[/quote]</textarea>
            {else}
                <textarea cols="50" rows="15" class="input_textarea" name="info[message]">{$smarty.post.info.message|escape:"html"}</textarea>
            {/if}
        </td>
    </tr>
    <tr>
        <td align="right" colspan="2" class="cell2">
            <input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="{translate}Abort{/translate}"/>
            <input type="submit" name="submit" class="ButtonGreen" value="{translate}Send message{/translate}" />
        </td>
    </tr>
</table>
</form>