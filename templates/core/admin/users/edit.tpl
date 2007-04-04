<div class="admin">
    <div class="user_settings">
    <form method="post" action="index.php?mod=admin&amp;sub=users&amp;action=edit_standard">

        <input type="hidden" name="info[user_id]" value="{$user.user_id}" />
        <table cellspacing="0" border="0" align="center" width="400">
            <tr class="tr_header">
                <td align="center" width="30%">{translate}Description{/translate}</td>
                <td align="center" width="70%">{translate}Input{/translate}</td>
            </tr>
            <tr class="tr_row1">
                <td>{translate}Nick{/translate}</td>
                <td><input type="text" value="{$user.nick|escape:"html"}" class="input_text" name="info[nick]" /></td>
            </tr>
            <tr class="tr_row1">
                <td>{translate}eMail{/translate}</td>
                <td><input type="text" value="{$user.email|escape:"html"}" class="input_text" name="info[email]" /></td>
            </tr>
            <tr class="tr_row1">
                <td>{translate}Password{/translate}</td>
                <td>
                    <input type="text" value="" class="input_text" name="info[password]" /><br />
                    {translate}Leave it blank if you do not want to change the password!{/translate}
                </td>
            </tr>
            <tr class="tr_row1">
                <td>{translate}Activated{/translate}</td>
                <td><input type="checkbox" value="1" class="input_text" name="info[activated]" {if $user.activated==1}checked="checked"{/if} /></td>
            </tr>
            <tr class="tr_row1">
                <td>{translate}Disabled/Banned{/translate}</td>
                <td><input type="checkbox" value="1" class="input_text" name="info[disabled]" {if $user.disabled==1}checked="checked"{/if} /></td>
            </tr>
            <tr class="tr_row1">
                <td>{translate}Groups{/translate}</td>
                <td>
                    <table cellspacing="0" border="0" width="100%">
                    {foreach item=item key=key from=$all_groups}
                        <tr class="tr_row1">
                            <td width="1%">
                                <input type="checkbox" value="{$item.group_id}" class="input_text" name="info[groups][]" {if in_array($item.group_id, $groups)}checked="checked"{/if}/>
                            </td>
                            <td align="left">
                                <a href="index.php?mod=admin&amp;sub=groups&amp;action=edit&amp;id={$item.group_id}" target="_blank">{$item.name|escape:"html"}</a>
                            </td>
                        </tr>
                    {/foreach}
                    </table>
                </td>
            </tr>
            <tr class="tr_row1">
                <td colspan="2" align="right">
                    <input class="ButtonRed" type="button" onclick="Dialog.okCallback()" value="{translate}Abort{/translate}"/>
                    <input class="ButtonGreen" type="submit" name="submit" value="{translate}Edit the user{/translate}" />
                    <input class="ButtonGrey" type="reset" value="{translate}Reset{/translate}" />
                </td>
            </tr>
        </table>

    </form>
    </div>
</div>