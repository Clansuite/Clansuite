{if $err.no_special_chars == 1}
    {error title="Special Chars"}
        No special chars except '_' are allowed.
    {/error}
{/if}

{if $err.fill_form == 1}
    {error title="Fill form"}
        Please fill all fields.
    {/error}
{/if}

{if $err.nick_already == 1}
    {error title="Nick already stored"}
        The nick you have entred is already in the database.
    {/error}
{/if}

{if $err.email_already == 1}
    {error title="eMail already stored"}
        The eMail you have entered is already in the database.
    {/error}
{/if}

{if $err.email_wrong == 1}
   {error title="Mail wrong!"}
        The email you entered is not valid.
    {/error}
{/if}

<form target="_self" method="post" action="index.php?mod=admin&sub=users&action=create">

    <table cellpadding="0" cellspacing="0" border="0" align="center" width="350">
        <tr class="tr_header">
            <td align="center" width="20%">{translate}Description{/translate}</td>
            <td align="center" width="80%">{translate}Input{/translate}</td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Nick{/translate}</td>
            <td><input type="text" value="{$smarty.post.info.nick|escape:"html"}" class="input_text" name="info[nick]" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}eMail{/translate}</td>
            <td><input type="text" value="{$smarty.post.info.email|escape:"html"}" class="input_text" name="info[email]" /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Password{/translate}</td>
            <td>
                <input type="text" value="{$smarty.post.info.password|escape:"html"}" class="input_text" name="info[password]" />
            </td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Activated{/translate}</td>
            <td><input type="checkbox" value="1" class="input_text" name="info[activated]" {if $smarty.post.info.activated==1}checked="checked"{/if} /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Disabled/Banned{/translate}</td>
            <td><input type="checkbox" value="1" class="input_text" name="info[disabled]" {if $smarty.post.info.disabled==1}checked="checked"{/if} /></td>
        </tr>
        <tr class="tr_row1">
            <td>{translate}Groups{/translate}</td>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                {foreach item=item key=key from=$all_groups}
                    <tr class="tr_row1">
                        <td width="1%">
                            <input type="checkbox" value="{$item.group_id}" class="input_text" name="info[groups][]" {if in_array($item.group_id, $smarty.post.info.groups)}checked="checked"{/if}/>
                        </td>
                        <td>
                            <a href="index.php?mod=admin&sub=groups&action=edit&id={$item.group_id}" target="_blank">{$item.name|escape:"html"}</a>
                        </td>
                    </tr>
                {/foreach}
                </table>
            </td>
        </tr>
        <tr class="tr_row1">
            <td colspan="2" align="right">
                <input type="button" value="{translate}Abort{/translate}" class="ButtonRed" onclick="Dialog.okCallback()" />
                <input class="ButtonGreen" type="submit" name="submit" value="{translate}Create the user{/translate}" />
                <input class="ButtonGrey" type="reset" value="{translate}Reset{/translate}" />
            </td>
    </table>
</form>